@extends('layouts.storefront')

@section('title', 'Commande confirmée — ADO PRINT')

@section('content')
    @php
        $money = fn ($v): string => number_format((float) $v, 2, ',', ' ');
    @endphp

    <div class="ap24-wrap">
        @include('partials.crumbs', ['items' => [['label' => 'Commande confirmée']]])

        <div class="ap24-panel mx-auto mb-12 mt-6" style="max-width: 820px; text-align: center;">
            <span class="ap24-empty__icon" aria-hidden="true" style="width: 62px; height: 62px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 30px; height: 30px;"><path d="M20 6 9 17l-5-5"/></svg>
            </span>

            <h1 class="mt-5 text-3xl font-extrabold text-slate-900">Merci {{ $order->meta['customer']['nom'] ?? '' }} !</h1>
            <p class="mt-3 text-slate-600">
                Votre commande <span class="font-extrabold" style="color: var(--ap-accent-dark);">{{ $number }}</span> a bien été enregistrée.
            </p>

            @if ($items)
                <div class="ap24-panel ap24-panel--soft mt-8 text-left">
                    <h2>Détail de la commande</h2>

                    <dl class="mt-1">
                        @foreach ($items as $item)
                            <div class="ap24-line">
                                <dt>
                                    {{ $item['product'] }}
                                    <span class="block text-xs text-slate-500">{{ $item['qty'] }} ex.</span>
                                </dt>
                                <dd>{{ $money($item['total']) }} DH</dd>
                            </div>
                        @endforeach
                        <div class="ap24-line ap24-line--total">
                            <dt>Total HT</dt>
                            <dd>{{ $money($order->total) }} DH</dd>
                        </div>
                    </dl>
                </div>
            @endif

            <div class="mt-8 grid gap-4 text-left sm:grid-cols-3">
                @foreach ([
                    ['Étape 1', 'Notre équipe vérifie vos fichiers et vous appelle pour confirmer.'],
                    ['Étape 2', 'Impression et contrôle qualité sous 24 h après validation.'],
                    ['Étape 3', "Livraison à l'adresse indiquée, paiement à la réception."],
                ] as $step)
                    <div class="ap24-panel">
                        <p class="text-xs font-extrabold uppercase tracking-wider" style="color: var(--ap-accent);">{{ $step[0] }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $step[1] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('products.index') }}" class="ap24-btn ap24-btn--yellow ap24-btn--flat">Continuer mes achats</a>
                <a href="{{ route('contact') }}" class="ap24-btn ap24-btn--ghost ap24-btn--flat">Une question ?</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Conversion : la commande transforme le visiteur anonyme en client identifié.
         L'identifiant PostHog est l'empreinte SHA-256 de l'email : aucune donnée
         directement identifiante n'est transmise. --}}
    @php
        $customerEmail = mb_strtolower(trim((string) ($order->meta['customer']['email'] ?? '')));
        $customerKey = $customerEmail !== '' ? 'ado_'.substr(hash('sha256', $customerEmail), 0, 32) : null;
    @endphp

    <script type="module">
        @if ($customerKey)
            window.adoAnalytics?.identify(@json($customerKey), {
                first_order_number: @json($number),
                delivery_city: @json($order->meta['delivery']['ville'] ?? null),
            });
        @endif

        window.adoAnalytics?.capture('order_placed', {
            order_number: @json($number),
            total: {{ (float) $order->total }},
            items: {{ count($items) }},
            quantity: {{ (int) collect($items)->sum('qty') }},
            products: @json(collect($items)->pluck('product')->values()),
        });
    </script>
@endpush
