<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Widgets\Widget;

/**
 * « Dernières commandes » de l'accueil administration : les 5 commandes les
 * plus récentes avec client extrait du cartouche (meta.customer) et liens
 * vers le détail complet. Tolérant aux bases vides ou indisponibles.
 */
class RecentOrders extends Widget
{
    protected static string $view = 'filament.widgets.recent-orders';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 4;

    protected function getViewData(): array
    {
        try {
            $orders = Order::query()
                ->latest()
                ->limit(5)
                ->get();
        } catch (\Throwable) {
            $orders = collect();
        }

        return [
            'orders' => $orders,
            'ordersUrl' => OrderResource::getUrl('index'),
        ];
    }
}
