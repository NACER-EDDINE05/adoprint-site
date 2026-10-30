@extends('layouts.storefront')

@section('title', $search ? 'Recherche : '.$search.' — ADO PRINT' : 'Tous nos produits — ADO PRINT')

@section('content')
    @php
        $money = fn ($v): string => number_format((float) $v, 2, ',', ' ');
        $cheapest = $products->sortBy(fn ($p) => (float) ($p->priceGrids->min('price') ?: $p->base_price))->first();
        $fromPrice = $cheapest ? (float) ($cheapest->priceGrids->min('price') ?: $cheapest->base_price) : null;
    @endphp

    <div class="ap24-wrap">
        {{-- Fil d'Ariane --}}
        <nav class="ap24-crumbs" aria-label="Fil d'Ariane">
            <ol class="ap24-crumbs__list">
                <li><a href="{{ route('home') }}">Accueil</a></li>
                <li class="ap24-crumbs__sep" aria-hidden="true">/</li>
                <li aria-current="page">{{ $search ? 'Recherche' : 'Catégories' }}</li>
            </ol>
        </nav>

        {{-- En-tête --}}
        <header class="ap24-pagehead">
            <span class="ap24-pagehead__icon" aria-hidden="true">
                @include('partials.category-icon', ['slug' => $search ? 'default' : 'impression-papier-bureautique'])
            </span>
            <div>
                <p class="ap24-kicker">{{ $search ? 'Résultats de recherche' : 'Catalogue' }}</p>
                <h1>{{ $search ? 'Résultats pour « '.$search.' »' : 'Tous nos produits' }}</h1>
                <p class="ap24-pagehead__desc">
                    @if ($search)
                        {{ $products->count() }} produit{{ $products->count() > 1 ? 's' : '' }} correspond{{ $products->count() > 1 ? 'ent' : '' }} à votre recherche.
                    @else
                        Configurez votre produit en ligne et obtenez votre prix instantanément :
                        9 familles, plus de 130 sous-catégories, livraison partout au Maroc.
                    @endif
                </p>
                @unless ($search)
                    <p class="ap24-pagehead__meta">
                        <span>{{ $products->count() }} produits configurables</span>
                        <span>{{ $families->count() }} familles</span>
                        <span>Prix dégressifs</span>
                    </p>
                @endunless
            </div>
        </header>

        {{-- Recherche --}}
        <form action="{{ route('products.index') }}" method="GET" class="ap24-searchbar" role="search">
            <label class="ap24-input ap24-searchbar__input">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="search" name="q" value="{{ $search }}" placeholder="Rechercher un produit (carte de visite, bâche, mug…)" aria-label="Rechercher un produit">
            </label>
            <button type="submit" class="ap24-btn ap24-btn--yellow ap24-btn--sm">Rechercher</button>
        </form>

        {{-- Familles --}}
        @unless ($search)
            <section class="ap24-block">
                <div class="ap24-subhead">
                    <div>
                        <h2>Les 9 familles de produits</h2>
                        <p>Choisissez une famille pour découvrir ses sous-catégories.</p>
                    </div>
                </div>

                <div class="ap24-ctiles">
                    @foreach ($families as $family)
                        <a href="{{ route('category.show', $family['model']->slug) }}" class="ap24-ctile">
                            @php $familyPhoto = $family['cheapest']?->photoUrl(); @endphp
                            @if ($familyPhoto)
                                <img class="ap24-ctile__thumb" src="{{ $familyPhoto }}" alt="" width="80" height="80" loading="lazy" decoding="async">
                            @else
                                <span class="ap24-ctile__icon" aria-hidden="true">
                                    @include('partials.category-icon', ['slug' => $family['model']->slug])
                                </span>
                            @endif
                            <span class="ap24-ctile__txt">
                                <span class="ap24-ctile__name">{{ $family['model']->name }}</span>
                                <span class="ap24-ctile__meta">
                                    <span>{{ $family['children']->count() }} sous-catégories</span>
                                    <span>{{ $family['count'] }} produit{{ $family['count'] > 1 ? 's' : '' }}</span>
                                    @if ($family['fromPrice'])
                                        <span>dès <b>{{ $money($family['fromPrice']) }} DH</b></span>
                                    @endif
                                </span>
                            </span>
                            <svg class="ap24-ctile__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                    @endforeach
                </div>
            </section>
        @endunless

        {{-- Produits --}}
        <section class="ap24-block">
            <div class="ap24-subhead">
                <div>
                    <h2>{{ $search ? 'Produits trouvés' : 'Tout le catalogue' }}</h2>
                    <p>
                        @if ($search)
                            {{ $products->count() }} produit{{ $products->count() > 1 ? 's' : '' }}
                        @else
                            {{ $products->count() }} produit{{ $products->count() > 1 ? 's' : '' }} configurable{{ $products->count() > 1 ? 's' : '' }} en ligne
                        @endif
                    </p>
                </div>
            </div>

            @if ($products->isEmpty())
                <div class="ap24-empty">
                    <span class="ap24-empty__icon" aria-hidden="true">
                        @include('partials.category-icon', ['slug' => 'default'])
                    </span>
                    <h2>Aucun produit {{ $search ? 'ne correspond à « '.$search.' »' : 'pour le moment' }}</h2>
                    <p>Contactez-nous : nous pouvons le réaliser sur mesure.</p>
                    <a href="{{ route('devis') }}" class="ap24-btn ap24-btn--yellow">Demander un devis</a>
                </div>
            @else
                <div class="ap24-grid ap24-grid--tight">
                    @foreach ($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection
