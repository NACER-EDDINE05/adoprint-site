<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

/**
 * Graphique « activité » de l'accueil administration : nombre de commandes
 * reçues jour par jour (14 derniers jours, mondiales types confondus).
 * Tolérant aux bases vides ou indisponibles (try/catch), comme les autres
 * widgets de la boutique.
 */
class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Commandes reçues — 14 derniers jours';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => ['stepSize' => 1, 'precision' => 0],
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $labels = [];
        $counts = [];

        try {
            $perDay = Order::query()
                ->where('created_at', '>=', now()->subDays(13)->startOfDay())
                ->get()
                ->groupBy(fn ($order) => $order->created_at->format('Y-m-d'))
                ->map->count();

            for ($i = 13; $i >= 0; $i--) {
                $day = now()->subDays($i);
                $labels[] = $day->translatedFormat('j M');
                $counts[] = $perDay->get($day->format('Y-m-d'), 0);
            }
        } catch (\Throwable) {
            // Base indisponible ou table absente : graphique vide, pas d'erreur.
            $labels[] = '';
            $counts[] = 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Commandes',
                    'data' => $counts,
                    'backgroundColor' => '#00aeef',
                    'hoverBackgroundColor' => '#0089c1',
                    'borderRadius' => 6,
                    'maxBarThickness' => 28,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
