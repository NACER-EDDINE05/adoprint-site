<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DevisController extends Controller
{
    /**
     * Formulaire de devis sur mesure.
     */
    public function show(Request $request)
    {
        return view('devis', [
            'products' => Product::where('active', true)->orderBy('name')->get(),
            'productId' => $request->query('produit'),
        ]);
    }

    /**
     * Enregistre une demande de devis (stockée dans order.meta, statut quote).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'          => 'required|string|max:120',
            'email'        => 'required|email|max:190',
            'telephone'    => 'nullable|string|max:40',
            'societe'      => 'nullable|string|max:120',
            'product_id'   => 'nullable|integer|exists:products,id',
            'quantite'     => 'nullable|integer|min:1|max:1000000',
            'message'      => 'required|string|max:4000',
        ]);

        $product = ! empty($validated['product_id'])
            ? Product::find($validated['product_id'])
            : null;

        try {
            \App\Models\Order::create([
                'user_id' => auth()->id(),
                'status'  => 'quote',
                'total'   => 0,
                'meta'    => [
                    'type'      => 'devis',
                    'nom'       => $validated['nom'],
                    'email'     => $validated['email'],
                    'telephone' => $validated['telephone'] ?? null,
                    'societe'   => $validated['societe'] ?? null,
                    'produit'   => $product?->name,
                    'quantite'  => $validated['quantite'] ?? null,
                    'message'   => $validated['message'],
                ],
            ]);
        } catch (\Throwable $e) {
            // Ne jamais bloquer le visiteur pour un échec d'enregistrement
            Log::warning('Échec enregistrement devis : '.$e->getMessage());
        }

        return redirect()
            ->route('devis')
            ->with('success', 'Votre demande de devis a bien été envoyée. Notre équipe vous répondra dans les plus brefs délais.');
    }

    /**
     * Message simple depuis la page contact.
     */
    public function contact(Request $request)
    {
        $validated = $request->validate([
            'nom'      => 'required|string|max:120',
            'email'    => 'required|email|max:190',
            'sujet'    => 'required|string|max:190',
            'message'  => 'required|string|max:4000',
        ]);

        try {
            \App\Models\Order::create([
                'user_id' => auth()->id(),
                'status'  => 'quote',
                'total'   => 0,
                'meta'    => [
                    'type'    => 'contact',
                    'nom'     => $validated['nom'],
                    'email'   => $validated['email'],
                    'sujet'   => $validated['sujet'],
                    'message' => $validated['message'],
                ],
            ]);
        } catch (\Throwable $e) {
            Log::warning('Échec enregistrement contact : '.$e->getMessage());
        }

        return redirect()
            ->route('contact')
            ->with('success', 'Votre message a bien été envoyé. Nous vous répondrons rapidement.');
    }
}
