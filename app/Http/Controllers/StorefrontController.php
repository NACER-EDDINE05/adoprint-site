<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    /**
     * Page d'accueil « Fresh Ink Press » : héro, offres exclusives,
     * produits populaires, univers de produits, histoire + piliers de confiance.
     */
    public function home()
    {
        $categories = Category::where('active', true)->orderBy('name')->get();
        $products   = Product::with(['category', 'priceGrids'])->where('active', true)->get();

        // Prix plancher réel par produit (première ligne de la grille dégressive).
        $floor = function (Product $p): float {
            $min = $p->priceGrids->min('price');

            return (float) ($min ?: $p->base_price);
        };

        // Quantité minimale réelle de la grille (affichage « Dès X dhs · N ex. »).
        $minQty = function (Product $p): int {
            $qty = $p->priceGrids->min('min_qty');

            return (int) ($qty ?: 100);
        };

        // Univers de produits : chaque catégorie avec son produit le moins cher.
        $univers = $categories->map(function (Category $c) use ($products, $floor) {
            $items = $products->where('category_id', $c->id)->values();
            $cheapest = $items->sortBy($floor)->first();

            return [
                'model'     => $c,
                'items'     => $items,
                'cheapest'  => $cheapest,
                'fromPrice' => $cheapest ? $floor($cheapest) : null,
            ];
        })
            // Univers mis en avant sur l'accueil (les autres restent accessibles
            // via « Tous les produits » et le menu « Nos produits »).
            ->reject(fn ($u) => in_array($u['model']->slug, [
                'affiches-posters', 'brochures', 'brochures-catalogues', 'calendriers',
                'cartes-de-visite', 'flyers', 'flyers-depliants', 'grand-format-plv',
            ], true))
            ->values();

        // Offres exclusives : plus forte remise intra-grille (plancher vs plafond),
        // calculée sur les vraies grilles tarifaires — pas d'inventaire.
        $offers = $products
            ->filter(fn ($p) => $p->priceGrids->count() >= 2)
            ->map(function ($p) use ($floor) {
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
            ->filter(fn ($o) => $o['discount'] >= 25)
            ->sortByDesc('discount')
            ->take(4)
            ->values();

        // Héro : produits d'appel (meilleur prix plancher absolu).
        $heroPicks = $products
            ->filter(fn ($p) => $p->priceGrids->isNotEmpty())
            ->sortBy($floor)
            ->take(2)
            ->values();

        return view('home', [
            'categories' => $categories,
            'featured'   => $products->sortBy($floor)->take(8)->values(),
            'offers'     => $offers,
            'univers'    => $univers,
            'heroPicks'  => $heroPicks,
            'floor'      => $floor,
            'minQty'     => $minQty,
            'money'      => fn ($v) => number_format((float) $v, 2, ',', ' '),
        ]);
    }

    /**
     * Tous les produits.
     */
    public function productsIndex()
    {
        $products = Product::with(['category', 'priceGrids'])
            ->where('active', true)
            ->when(request('q'), function ($query, $q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->get();

        return view('products', [
            'products'   => $products,
            'categories' => Category::where('active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Page catégorie : liste des produits de la famille.
     */
    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->where('active', true)->firstOrFail();

        $products = Product::with('category')
            ->where('category_id', $category->id)
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return view('category', [
            'category'   => $category,
            'products'   => $products,
            'categories' => Category::where('active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Page produit : configurateur de prix (grille tarifaire + options).
     */
    public function product(string $slug)
    {
        $product = Product::with('category')
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
            'categories' => Category::where('active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Page contact.
     */
    public function contact()
    {
        return view('contact', [
            'categories' => Category::where('active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * À propos : présentation de l'imprimerie ADO PRINT.
     */
    public function apropos()
    {
        return view('a-propos', [
            'categories' => Category::where('active', true)->orderBy('name')->get(),
        ]);
    }
}
