<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Résumé chiffré affiché sur la page d'accueil de l'administration :
 * commandes reçues, chiffre d'affaires encaissé, catalogue (produits /
 * catégories). Tolérant aux bases vides ou manquantes (try/catch) pour
 * ne jamais laisser un nouvel ADMIN face à un écran d'erreur.
 */
class StorefrontBriefing extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $stats = [];

        try {
            $stats[] = Stat::make('Commandes', Order::query()->count())
                ->description('Commandes reçues du site')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info');
        } catch (\Throwable) {
            // Base indisponible : la stat n'est simplement pas affichée.
        }

        try {
            $total = (float) Order::query()->sum('total');
            $stats[] = Stat::make('Chiffre d’affaires', number_format($total, 2, ',', ' ') . ' MAD')
                ->description('Total des commandes')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success');
        } catch (\Throwable) {
        }

        try {
            $products = Product::query()->count();
            $stat = Stat::make('Produits', $products)
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary');

            if ($products === 0) {
                $stat->description('Commencez par créer un produit')
                    ->descriptionIcon('heroicon-m-cube-transparent')
                    ->color('warning');
            }

            $stats[] = $stat;
        } catch (\Throwable) {
        }

        try {
            $stats[] = Stat::make('Catégories', Category::query()->count())
                ->description('Familles du catalogue')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('gray');
        } catch (\Throwable) {
        }

        return $stats;
    }
}
