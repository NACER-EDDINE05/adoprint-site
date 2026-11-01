<x-filament::widget>
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
            <div>
                <h2 class="text-base font-semibold text-gray-900">Dernières commandes</h2>
                <p class="mt-0.5 text-sm text-gray-500">Les 5 plus récentes, avec le statut en cours.</p>
            </div>
            @if (filled($ordersUrl))
                <a href="{{ $ordersUrl }}"
                    class="hidden rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 no-underline transition hover:border-primary-500 hover:text-primary-600 sm:inline-block"
                >Tout voir</a>
            @endif
        </div>

        @if ($orders->isEmpty())
            <div class="flex flex-col items-center gap-1 px-5 py-8 text-center">
                @svg('heroicon-o-receipt-percent', 'h-7 w-7 text-gray-400')
                <span class="mt-1 text-sm font-semibold text-gray-700">Aucune commande pour le moment</span>
                <span class="text-xs text-gray-500">Les commandes passées depuis la boutique apparaîtront ici.</span>
            </div>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach ($orders as $order)
                    <li class="flex items-center justify-between gap-3 px-5 py-3.5 transition hover:bg-primary-50/40">
                        <div class="min-w-0">
                            <a href="{{ \App\Filament\Resources\OrderResource::getUrl('view', ['record' => $order]) }}"
                                class="block truncate text-sm font-semibold text-gray-900 no-underline hover:text-primary-700"
                            >
                                #{{ $order->id }} — {{ $order->meta['customer']['nom'] ?? $order->user?->name ?? 'Client' }}
                            </a>
                            <span class="mt-0.5 block truncate text-xs text-gray-500">
                                {{ $order->created_at?->isoFormat('Do MMMM YYYY, HH:mm') ?? '—' }}
                                {{ $order->user?->email ?? ($order->meta['customer']['email'] ?? '') }}
                            </span>
                        </div>

                        <div class="flex flex-shrink-0 items-center gap-3">
                            <span class="text-sm font-bold tabular-nums text-gray-900">
                                {{ number_format((float) $order->total, 2, ',', ' ') }} <span class="text-xs font-semibold text-gray-500">MAD</span>
                            </span>
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold whitespace-nowrap {{ \App\Filament\Resources\OrderResource::statusColor($order->status) }}">
                                {{ \App\Filament\Resources\OrderResource::statusLabel($order->status) }}
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-filament::widget>