<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Tunnel de commande : panier → coordonnées + livraison + fichiers → confirmation.
 * Chaque commande est enregistrée comme Order (status 'pending') avec le détail
 * complet dans meta ; les fichiers client sont stockés via le disque 'public'
 * et référencés dans la table media (model_type Order::class).
 */
class CheckoutController extends Controller
{
    public function __construct(protected CartService $cart)
    {
    }

    /** Page panier. */
    public function cart()
    {
        return view('cart', [
            'items' => $this->cart->items(),
            'total' => $this->cart->total(),
        ]);
    }

    /** Ajoute au panier depuis la page produit. */
    public function add(Request $request, CartService $cart)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'qty'        => 'required|integer|min:1|max:1000000',
            'options'    => 'nullable|array',
            'files'      => 'nullable|array',
            'files.*'    => 'file|mimes:pdf,png,jpg,jpeg,ai,eps,zip|max:10240',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Upload immédiat des fichiers de la ligne, puis rattachement après création.
        $uploaded = $this->storeFiles($request);

        $rowId = $cart->add($product, (int) $validated['qty'], $validated['options'] ?? []);

        session()->put("cart_files.{$rowId}", $uploaded);

        return redirect()
            ->route('cart')
            ->with('success', '« '.$product->name.' » a été ajouté à votre panier.');
    }

    /** Met à jour une quantité (formulaire ou AJAX). */
    public function update(Request $request, CartService $cart)
    {
        $validated = $request->validate([
            'row_id' => 'required|string',
            'qty'    => 'required|integer|min:0|max:1000000',
        ]);

        $cart->update($validated['row_id'], (int) $validated['qty']);

        if ($request->wantsJson()) {
            return response()->json([
                'ok'    => true,
                'count' => $cart->count(),
                'total' => number_format($cart->total(), 2, ',', ' '),
            ]);
        }

        return redirect()->route('cart')->with('success', 'Quantité mise à jour.');
    }

    /** Retire une ligne du panier. */
    public function remove(Request $request, CartService $cart)
    {
        $validated = $request->validate(['row_id' => 'required|string']);

        $cart->remove($validated['row_id']);
        session()->forget("cart_files.{$validated['row_id']}");

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'count' => $cart->count()]);
        }

        return redirect()->route('cart')->with('success', 'Article retiré du panier.');
    }

    /** Formulaire de commande (coordonnées + livraison + fichiers). */
    public function show(CartService $cart)
    {
        abort_if($cart->count() === 0, 302, '/panier');

        return view('checkout', [
            'items'   => $cart->items(),
            'total'   => $cart->total(),
            'user'    => auth()->user(),
        ]);
    }

    /**
     * Valide la commande : crée l'Order + les Media, vide le panier,
     * redirige vers la confirmation.
     */
    public function store(Request $request, CartService $cart)
    {
        abort_if($cart->count() === 0, 302, '/panier');

        $validated = $request->validate([
            'nom'        => 'required|string|max:120',
            'email'      => 'required|email|max:190',
            'telephone'  => 'required|string|max:40',
            'societe'    => 'nullable|string|max:120',
            'adresse'    => 'required|string|max:255',
            'ville'      => 'required|string|max:120',
            'code_postal'=> 'nullable|string|max:20',
            'notes'      => 'nullable|string|max:2000',
            'files'      => 'nullable|array',
            'files.*'    => 'file|mimes:pdf,png,jpg,jpeg,ai,eps,zip|max:10240',
        ]);

        $items = $cart->items();
        $total = $cart->total();

        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'status'  => 'pending',
                'total'   => $total,
                'meta'    => [
                    'type'       => 'order',
                    'customer'   => [
                        'nom'       => $validated['nom'],
                        'email'     => $validated['email'],
                        'telephone' => $validated['telephone'],
                        'societe'   => $validated['societe'] ?? null,
                    ],
                    'delivery'   => [
                        'adresse'     => $validated['adresse'],
                        'ville'       => $validated['ville'],
                        'code_postal' => $validated['code_postal'] ?? null,
                    ],
                    'notes'      => $validated['notes'] ?? null,
                    'items'      => collect($items)->map(fn ($i) => [
                        'product' => $i['product']->name,
                        'slug'    => $i['product']->slug,
                        'qty'     => $i['qty'],
                        'options' => $i['options'],
                        'unit'    => $i['unit'],
                        'total'   => $i['total'],
                    ])->all(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Échec enregistrement commande : '.$e->getMessage());

            return redirect()->route('cart')->with('error', 'Une erreur est survenue. Merci de réessayer.');
        }

        // Fichiers ajoutés au moment de la commande + ceux mis de côté depuis le produit.
        $staged = [];
        foreach ($cart->items() as $i) {
            $staged = array_merge($staged, session()->pull("cart_files.{$i['row_id']}", []));
        }
        $this->attachFiles(array_merge($staged, $this->storeFiles($request)), $order);

        $number = 'CMD-'.str_pad((string) $order->id, 5, '0', STR_PAD_LEFT);
        $cart->clear();

        return redirect()
            ->route('checkout.confirmation', $order->id)
            ->with('success', 'Votre commande '.$number.' a bien été enregistrée.');
    }

    /** Page de confirmation avec numéro de commande. */
    public function confirmation(Order $order)
    {
        return view('order-confirmation', [
            'order'  => $order,
            'number' => 'CMD-'.str_pad((string) $order->id, 5, '0', STR_PAD_LEFT),
            'items'  => $order->meta['items'] ?? [],
        ]);
    }

    /** Stocke les fichiers uploadés sur le disque public, retourne les métadonnées. */
    protected function storeFiles(Request $request): array
    {
        $stored = [];

        foreach ($request->file('files', []) as $file) {
            if (! $file->isValid()) {
                continue;
            }

            $path = $file->store('commandes', 'public');

            $stored[] = [
                'path'     => $path,
                'filename' => $file->getClientOriginalName(),
                'mime'     => $file->getClientMimeType(),
                'size'     => $file->getSize(),
            ];
        }

        return $stored;
    }

    /** Rattache des métadonnées de fichiers à une commande (table media). */
    protected function attachFiles(array $files, Order $order): void
    {
        foreach ($files as $meta) {
            try {
                Media::create($meta + [
                    'model_type' => Order::class,
                    'model_id'   => $order->id,
                ]);
            } catch (\Throwable $e) {
                Log::warning('Échec enregistrement média : '.$e->getMessage());
            }
        }
    }
}
