<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use App\Http\Livewire\PricePreview;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('price-preview', PricePreview::class);

        // Variables partagées avec la boutique publique (en-tête / pied de page)
        View::composer(
            ['layouts.storefront', 'home', 'products', 'category', 'product', 'devis', 'contact', 'aide', 'a-propos', 'cart', 'checkout', 'order-confirmation'],
            function ($view) {
                $view->with('siteSettings', \App\Models\Setting::query()->pluck('value', 'key'));
                // Arborescence du catalogue : les 9 familles et leurs sous-catégories
                // (utilisées par l'en-tête, le méga-menu et le pied de page).
                $view->with('navCategories', \App\Models\Category::where('active', true)
                    ->whereNull('parent_id')
                    ->with(['children' => fn ($q) => $q->where('active', true)
                        ->with(['products' => fn ($products) => $products->whereNotNull('photo')->orderBy('name')])])
                    ->orderBy('position')->get());
                $view->with('cartCount', app(\App\Services\CartService::class)->count());
            }
        );
    }
}

