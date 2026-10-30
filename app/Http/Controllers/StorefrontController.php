<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;

class StorefrontController extends Controller
{
    /**
     * Page d'accueil : bandeau d'ouverture, promotions, familles de produits,
     * histoire, services, « Comment ça marche ? », chiffres clés et carrousel final.
     */
    public function home()
    {
        $products = Product::with(['category', 'priceGrids'])->where('active', true)->get();

        // Prix plancher réel d'un produit (première ligne de la grille dégressive).
        $floor = fn (Product $p): float => (float) ($p->priceGrids->min('price') ?: $p->base_price);

        // Quantité minimale réelle de la grille (« Dès X dhs · N ex. »).
        $minQty = fn (Product $p): int => (int) ($p->priceGrids->min('min_qty') ?: 100);

        $families = $this->familiesWithCatalog($products, $floor);

        // Cartes « promotions » : plus fortes remises calculées sur les grilles.
        $promos = $products
            ->filter(fn ($p) => $p->priceGrids->count() >= 2)
            ->map(function (Product $p) use ($floor) {
                $min = $floor($p);
                $max = (float) $p->priceGrids->max('price');

                return [
                    'product'     => $p,
                    'discount'    => $max > 0 ? (int) round((1 - $min / $max) * 100) : 0,
                    'fromPrice'   => $min,
                    'beforePrice' => $max,
                    'minQty'      => (int) ($p->priceGrids->min('min_qty') ?: 100),
                ];
            })
            ->sortByDesc('discount')
            ->take(4)
            ->values();

        // Bandeau d'ouverture : une diapositive par famille illustrée.
        $bannerSlides = $families
            ->filter(fn ($f) => $f['cheapest'] && $f['cheapest']->photoUrl())
            ->map(function ($f) {
                return [
                    'image'   => $f['cheapest']->photoUrl(),
                    'eyebrow' => $f['model']->name,
                    'title'   => $f['model']->name,
                    'text'    => \Illuminate\Support\Str::limit(strip_tags((string) $f['model']->description), 150),
                    'href'    => route('category.show', $f['model']->slug),
                    'cta'     => 'Voir la gamme',
                ];
            })
            ->take(6)
            ->values();

        return view('home', [
            'families'    => $families,
            'promos'      => $promos,
            'bannerSlides' => $bannerSlides,
            'bestDiscount' => $promos->max('discount') ?: 0,
            'productCount' => $products->count(),
            'floor'       => $floor,
            'minQty'      => $minQty,
            'money'       => fn ($v) => number_format((float) $v, 2, ',', ' '),
        ]);
    }

    /**
     * Tous les produits, présentés par famille puis en liste complète.
     */
    public function productsIndex()
    {
        $products = Product::with(['category', 'priceGrids'])
            ->where('active', true)
            ->when(request('q'), function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->get();

        $floor = fn (Product $p): float => (float) ($p->priceGrids->min('price') ?: $p->base_price);

        return view('products', [
            'products' => $products,
            'families' => $this->familiesWithCatalog($products, $floor),
            'search'   => request('q'),
        ]);
    }

    /**
     * Page famille : sous-catégories en tuiles + produits de toute la famille.
     * Page sous-catégorie : produits de la sous-catégorie.
     */
    public function category(string $slug)
    {
        $category = Category::with('parent')->where('slug', $slug)->where('active', true)->firstOrFail();

        $children = $category->children()->where('active', true)->get();

        $products = Product::with(['category', 'priceGrids'])
            ->whereIn('category_id', $category->selfAndChildrenIds())
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $floor = fn (Product $p): float => (float) ($p->priceGrids->min('price') ?: $p->base_price);

        // Chaque sous-catégorie affiche son nombre de produits et son prix d'appel.
        $childTiles = $children->map(function (Category $child) use ($products, $floor) {
            $items    = $products->where('category_id', $child->id)->values();
            $cheapest = $items->sortBy($floor)->first();

            return [
                'model'     => $child,
                'count'     => $items->count(),
                'photo'     => $items->firstWhere(fn ($p) => $p->photoUrl())?->photoUrl(),
                'fromPrice' => $cheapest ? $floor($cheapest) : null,
            ];
        });

        return view('category', [
            'category'   => $category,
            'children'   => $children,
            'childTiles' => $childTiles,
            'products'   => $products,
            'siblings'   => $category->parent
                ? $category->parent->children()->where('active', true)->get()
                : collect(),
            'families'   => Category::where('active', true)->whereNull('parent_id')->orderBy('position')->get(),
        ]);
    }

    /**
     * Page produit : configurateur de prix (grille tarifaire + options).
     */
    public function product(string $slug)
    {
        $product = Product::with(['category.parent', 'options'])
            ->where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $grids = $product->priceGrids()->orderBy('min_qty')->get();

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('active', true)
            ->take(4)
            ->get();

        return view('product', [
            'product'  => $product,
            'grids'    => $grids,
            'related'  => $related,
            'minPrice' => $grids->min('price') ?: $product->base_price,
        ]);
    }

    /**
     * Aide : comment commander + FAQ.
     */
    public function aide()
    {
        return view('aide', [
            'categories' => Category::where('active', true)->orderBy('position')->get(),
        ]);
    }

    /**
     * Page contact.
     */
    public function contact()
    {
        return view('contact', [
            'categories' => Category::where('active', true)->orderBy('position')->get(),
        ]);
    }

    /**
     * À propos : présentation de l'imprimerie ADO PRINT.
     */
    public function apropos()
    {
        return view('a-propos', [
            'categories' => Category::where('active', true)->orderBy('position')->get(),
        ]);
    }

    /**
     * Les 9 familles enrichies : sous-catégories, nombre de produits et prix
     * d'appel réel (sert aux tuiles de l'accueil et de la page produits).
     */
    private function familiesWithCatalog(Collection $products, callable $floor): Collection
    {
        return Category::where('active', true)
            ->whereNull('parent_id')
            ->with(['children' => fn ($q) => $q->where('active', true)])
            ->orderBy('position')
            ->get()
            ->map(function (Category $family) use ($products, $floor) {
                $items = $products
                    ->whereIn('category_id', $family->children->pluck('id')->push($family->id))
                    ->values();

                $cheapest = $items->sortBy($floor)->first();

                return [
                    'model'     => $family,
                    'items'     => $items,
                    'count'     => $items->count(),
                    'children'  => $family->children,
                    'cheapest'  => $cheapest,
                    'fromPrice' => $cheapest ? $floor($cheapest) : null,
                ];
            });
    }
}
