@extends('layouts.storefront')

@section('title', $category->name.' — ADO PRINT')
@section('meta_description', $category->description ?: 'Découvrez '.$category->name.' chez ADO PRINT : qualité professionnelle, prix dégressifs et livraison partout au Maroc.')

@section('content')
    @php
        $parent = $category->parent;
        $isFamily = $children->isNotEmpty();
        $floorPrice = fn ($p): float => (float) ($p->priceGrids->min('price') ?: $p->base_price);
        $cheapest = $products->sortBy($floorPrice)->first();
        $fromPrice = $cheapest ? $floorPrice($cheapest) : null;
        $money = fn ($v): string => number_format((float) $v, 2, ',', ' ');
        $cheapestOfFamily = $products->sortBy($floorPrice)->first();
        $fromPriceFamily = $cheapestOfFamily ? $floorPrice($cheapestOfFamily) : null;
    @endphp

    <div class="ap24-wrap">
        {{-- Fil d'Ariane --}}
        <nav class="ap24-crumbs" aria-label="Fil d'Ariane">
            <ol class="ap24-crumbs__list">
                <li><a href="{{ route('home') }}">Accueil</a></li>
                <li class="ap24-crumbs__sep" aria-hidden="true">/</li>
                <li><a href="{{ route('products.index') }}">Catégories</a></li>
                @if ($parent)
                    <li class="ap24-crumbs__sep" aria-hidden="true">/</li>
                    <li><a href="{{ route('category.show', $parent->slug) }}">{{ $parent->name }}</a></li>
                @endif
                <li class="ap24-crumbs__sep" aria-hidden="true">/</li>
                <li aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        {{-- En-tête --}}
        <header class="ap24-pagehead">
            <span class="ap24-pagehead__icon" aria-hidden="true">
                @include('partials.category-icon', ['slug' => $category->slug])
            </span>
            <div>
                <p class="ap24-kicker">{{ $parent ? 'Sous-catégorie' : 'Famille de produits' }}</p>
                <h1>{{ $category->name }}</h1>
                <p class="ap24-pagehead__desc">
                    {{ $category->description ?: 'Découvrez notre gamme '.$category->name.' : qualité professionnelle, prix dégressifs selon la quantité et livraison rapide partout au Maroc.' }}
                </p>
                <p class="ap24-pagehead__meta">
                    <span>{{ $products->count() }} produit{{ $products->count() > 1 ? 's' : '' }} configurable{{ $products->count() > 1 ? 's' : '' }}</span>
                    @if ($isFamily)
                        <span>{{ $children->count() }} sous-catégories</span>
                    @endif
                    @if ($fromPrice)
                        <span>Dès {{ $money($fromPrice) }} DH HT</span>
                    @endif
                </p>
            </div>
        </header>

        {{-- Sous-catégories d'une famille --}}
        @if ($isFamily)
            <section class="ap24-block">
                <div class="ap24-subhead">
                    <div>
                        <h2>Les sous-catégories</h2>
                        <p>Choisissez une sous-catégorie pour voir les produits et les tarifs.</p>
                    </div>
                </div>

                <div class="ap24-ctiles">
                    @foreach ($childTiles as $tile)
                        <a href="{{ route('category.show', $tile['model']->slug) }}" class="ap24-ctile">
                            @if ($tile['photo'])
                                <div class="ap24-ctile__imgwrap">
                                    <img class="ap24-ctile__thumb" src="{{ $tile['photo'] }}" alt="{{ $tile['model']->name }}" width="80" height="80" loading="lazy" decoding="async">
                                    <div class="ap24-ctile__overlay">
                                        <img src="{{ $tile['photo'] }}" alt="{{ $tile['model']->name }}" loading="lazy">
                                    </div>
                                </div>
                            @else
                                <span class="ap24-ctile__icon" aria-hidden="true">
                                    @include('partials.category-icon', ['slug' => $tile['model']->slug])
                                </span>
                            @endif
                            <span class="ap24-ctile__txt">
                                <span class="ap24-ctile__name">{{ $tile['model']->name }}</span>
                                <span class="ap24-ctile__meta">
                                    <span>{{ $tile['count'] }} produit{{ $tile['count'] > 1 ? 's' : '' }}</span>
                                    @if ($tile['fromPrice'])
                                        <span>dès <b>{{ $money($tile['fromPrice']) }} DH</b></span>
                                    @endif
                                </span>
                            </span>
                            <span class="ap24-ctile__voir">Voir la catégorie</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Sous-catégories voisines --}}
        @if ($siblings->isNotEmpty())
            <section class="ap24-block">
                <div class="ap24-subhead">
                    <div>
                        <h2>Autres sous-catégories</h2>
                        <p>{{ $parent->name }}</p>
                    </div>
                </div>

                <div class="ap24-chips">
                    @foreach ($siblings as $sibling)
                        <a href="{{ route('category.show', $sibling->slug) }}"
                           class="ap24-chip {{ $sibling->id === $category->id ? 'is-active' : '' }}"
                           @if ($sibling->id === $category->id) aria-current="page" @endif>
                            {{ $sibling->name }}
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Produits --}}
        <section class="ap24-block">
            <div class="ap24-subhead">
                <div>
                    <h2>{{ $isFamily ? 'Les produits de la gamme' : 'Nos produits' }}</h2>
                    <p>
                        @if ($products->isEmpty())
                            Gamme réalisée sur mesure, sur devis.
                        @else
                            Tarifs dégressifs : le prix unitaire baisse avec la quantité.
                        @endif
                    </p>
                </div>
                <a href="{{ route('products.index') }}" class="ap24-subhead__link">
                    Tout le catalogue
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>

            @if ($products->isEmpty())
                <div class="ap24-empty">
                    <span class="ap24-empty__icon" aria-hidden="true">
                        @include('partials.category-icon', ['slug' => $category->slug])
                    </span>
                    <h2>Cette gamme se configure sur devis</h2>
                    <p>Décrivez votre besoin (dimensions, quantité, finition) : notre équipe vous répond sous 24 h ouvrées.</p>
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

        {{-- Autres familles --}}
        @if ($families->isNotEmpty())
            <section class="ap24-block">
                <div class="ap24-subhead">
                    <div>
                        <h2>Explorer les autres familles</h2>
                        <p>9 familles, plus de 130 sous-catégories.</p>
                    </div>
                </div>

                <div class="ap24-chips">
                    @foreach ($families as $family)
                        @continue($family->id === $category->id || $family->id === $parent?->id)
                        <a href="{{ route('category.show', $family->slug) }}" class="ap24-chip">
                            {{ $family->name }}
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
