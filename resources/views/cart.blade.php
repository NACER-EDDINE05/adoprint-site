@extends('layouts.storefront')

@section('title', 'Votre panier — ADO PRINT')

@section('content')
    @php
        $money = fn ($v): string => number_format((float) $v, 2, ',', ' ');
    @endphp

    <div class="ap24-wrap">
        @include('partials.crumbs', ['items' => [['label' => 'Panier']]])

        @include('partials.page-head', [
            'kicker' => 'Votre sélection',
            'title' => 'Votre panier',
            'desc' => 'Vérifiez vos quantités, ajoutez vos fichiers d\'impression puis passez commande. Sans paiement en ligne.',
            'iconSvg' => '<circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>',
            'meta' => count($items) > 0 ? [count($items).' article'.(count($items) > 1 ? 's' : ''), $money($total).' DH HT'] : [],
        ])

        @if (session('success'))
            <div class="ap24-alert ap24-alert--ok mb-6">{{ session('success') }}</div>
        @endif

        @if (count($items) === 0)
            <div class="ap24-empty">
                <span class="ap24-empty__icon" aria-hidden="true">
                    @include('partials.category-icon', ['slug' => 'impression-papier-bureautique'])
                </span>
                <h2>Votre panier est vide</h2>
                <p>Parcourez le catalogue et configurez votre premier produit : le prix se calcule instantanément.</p>
                <a href="{{ route('products.index') }}" class="ap24-btn ap24-btn--yellow">Découvrir nos produits</a>
            </div>
        @else
            <div class="ap24-cart">
                {{-- Lignes du panier --}}
                <div class="space-y-4">
                    @foreach ($items as $item)
                        <article class="ap24-cartline">
                            <span class="ap24-cartline__thumb">
                                @if ($item['product']->photoUrl())
                                    <img src="{{ $item['product']->photoUrl() }}" alt="">
                                @else
                                    @include('partials.category-icon', ['slug' => $item['product']->category?->slug ?? 'default'])
                                @endif
                            </span>

                            <div class="ap24-cartline__main">
                                <a href="{{ route('product.show', $item['product']->slug) }}" class="ap24-cartline__name">
                                    {{ $item['product']->name }}
                                </a>

                                @if ($item['options'])
                                    <p class="ap24-cartline__opts">
                                        {{ collect($item['options'])->map(fn ($v, $k) => $k.' : '.$v)->implode(' · ') }}
                                    </p>
                                @endif

                                <p class="ap24-cartline__unit">
                                    {{ $money($item['unit']) }} DH / ex. · {{ number_format((int) $item['qty'], 0, ',', ' ') }} exemplaires
                                </p>

                                @if (session("cart_files.{$item['row_id']}"))
                                    <p class="mt-2 flex items-center gap-1.5 text-xs font-bold text-green-700">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                                        {{ count(session("cart_files.{$item['row_id']}")) }} fichier(s) joint(s)
                                    </p>
                                @endif
                            </div>

                            <span class="ap24-cartline__total">{{ $money($item['total']) }} DH</span>

                            <div class="ap24-cartline__tools">
                                <form action="{{ route('cart.update') }}" method="POST" class="flex flex-wrap items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="row_id" value="{{ $item['row_id'] }}">
                                    <label class="ap24-label" for="qty-{{ $item['row_id'] }}">Quantité</label>
                                    <input id="qty-{{ $item['row_id'] }}" type="number" name="qty" value="{{ $item['qty'] }}" min="1" max="1000000"
                                           class="ap24-input ap24-input--sm">
                                    <button type="submit" class="ap24-btn ap24-btn--ghost ap24-btn--sm ap24-btn--flat">Mettre à jour</button>
                                </form>

                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="row_id" value="{{ $item['row_id'] }}">
                                    <button type="submit" class="ap24-link-danger">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        Retirer
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach

                    <a href="{{ route('products.index') }}" class="ap24-subhead__link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        Continuer mes achats
                    </a>
                </div>

                {{-- Récapitulatif --}}
                <aside class="ap24-panel ap24-panel--soft">
                    <h2>Récapitulatif</h2>

                    <dl class="mt-2">
                        <div class="ap24-line">
                            <dt>Articles</dt>
                            <dd>{{ count($items) }}</dd>
                        </div>
                        <div class="ap24-line">
                            <dt>Livraison</dt>
                            <dd>Sur devis</dd>
                        </div>
                        <div class="ap24-line ap24-line--total">
                            <dt>Total estimé HT</dt>
                            <dd>{{ $money($total) }} DH</dd>
                        </div>
                    </dl>

                    <a href="{{ route('checkout.show') }}" class="ap24-btn ap24-btn--yellow ap24-btn--block ap24-btn--flat mt-4">
                        Passer la commande
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>

                    <p class="mt-3 text-center text-xs text-slate-500">Paiement à la livraison · Devis confirmé avant impression</p>
                </aside>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    {{-- Parcours panier : contenu consulté avant la commande --}}
    <script type="module">
        window.adoAnalytics?.capture('cart_viewed', {
            items: {{ count($items) }},
            quantity: {{ (int) collect($items)->sum('qty') }},
            total: {{ (float) $total }},
            products: @json(collect($items)->pluck('product.name')->values()),
        });
    </script>
@endpush
