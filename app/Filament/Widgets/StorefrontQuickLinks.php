<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

/**
 * Cartes « accès rapide » de l'accueil administration : chaque carte
 * mène à l'endroit exact où l'on gère un morceau du site (produits,
 * images, prix, commandes, boutique publique).
 */
class StorefrontQuickLinks extends Widget
{
    protected static string $view = 'filament.widgets.storefront-quick-links';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    protected function getViewData(): array
    {
        return [
            'links' => [
                [
                    'icon' => 'heroicon-o-plus-circle',
                    'title' => 'Ajouter un produit',
                    'subtitle' => 'Nom, description, photo et prix',
                    'url' => \App\Filament\Resources\ProductResource::getUrl('create'),
                    'accent' => true,
                ],
                [
                    'icon' => 'heroicon-o-photo',
                    'title' => 'Modifier les images du site',
                    'subtitle' => 'Logo, favicon, photo d’accueil',
                    'url' => \App\Filament\Pages\ManageAppearance::getUrl(),
                ],
                [
                    'icon' => 'heroicon-o-table-cells',
                    'title' => 'Gérer les prix',
                    'subtitle' => 'Grilles quantité / tarif',
                    'url' => \App\Filament\Resources\PriceGridResource::getUrl('index'),
                ],
                [
                    'icon' => 'heroicon-o-receipt-percent',
                    'title' => 'Suivre les commandes',
                    'subtitle' => 'Toutes les commandes du site',
                    'url' => \App\Filament\Resources\OrderResource::getUrl('index'),
                ],
                [
                    'icon' => 'heroicon-o-globe-alt',
                    'title' => 'Voir la boutique',
                    'subtitle' => 'adoprint-site.vercel.app',
                    'url' => route('home'),
                    'external' => true,
                ],
            ],
        ];
    }
}
