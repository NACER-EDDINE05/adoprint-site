@extends('layouts.storefront')

@section('title', $product->name.' — ADO PRINT')

@section('content')
    @php
        $category = $product->category;
        $family = $category?->parent;
        $maxUnit = (float) $grids->max('price');
        $minUnit = (float) ($grids->min('price') ?: $product->base_price);
        $discount = ($maxUnit > 0 && $minUnit < $maxUnit) ? (int) round((1 - $minUnit / $maxUnit) * 100) : 0;
        $startQty = (int) ($grids->first()?->min_qty ?? 100);
        $presets = $grids->pluck('min_qty')->unique()->sort()->take(4)->values();
        $photo = $product->photoUrl();
    @endphp

    <div class="ap24-wrap">
        {{-- Fil d'Ariane --}}
        <nav class="ap24-crumbs" aria-label="Fil d'Ariane">
            <ol class="ap24-crumbs__list">
                <li><a href="{{ route('home') }}">Accueil</a></li>
                <li class="ap24-crumbs__sep" aria-hidden="true">/</li>
                <li><a href="{{ route('products.index') }}">Catégories</a></li>
                @if ($family)
                    <li class="ap24-crumbs__sep" aria-hidden="true">/</li>
                    <li><a href="{{ route('category.show', $family->slug) }}">{{ $family->name }}</a></li>
                @endif
                @if ($category)
                    <li class="ap24-crumbs__sep" aria-hidden="true">/</li>
                    <li><a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></li>
                @endif
                <li class="ap24-crumbs__sep" aria-hidden="true">/</li>
                <li aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="ap24-pdp">
            {{-- Visuel --}}
            <div class="ap24-pdp__media">
                <div class="ap24-pdp__frame">
                    @if ($discount >= 10)
                        <span class="ap24-pdp__badge">Jusqu'à -{{ $discount }}%</span>
                    @endif

                    @if ($photo)
                        <img src="{{ $photo }}" alt="{{ $product->name }}" loading="lazy">
                    @else
                        <div class="ap24-fallback">
                            <span>@include('partials.category-icon', ['slug' => $category?->slug ?? 'default'])</span>
                        </div>
                    @endif
                </div>

                <p class="ap24-pdp__note">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    Visuel indicatif : un bon à tirer vous est envoyé avant impression.
                </p>
            </div>

            {{-- Configurateur --}}
            <div class="ap24-pdp__right">
                @if ($category)
                    <a href="{{ route('category.show', $category->slug) }}" class="ap24-pdp__cat">
                        @include('partials.category-icon', ['slug' => $category->slug])
                        {{ $category->name }}
                    </a>
                @endif

                <h1 class="ap24-pdp__title">{{ $product->name }}</h1>
                <p class="ap24-pdp__desc">{{ \Illuminate\Support\Str::limit(strip_tags((string) $product->description), 170) }}</p>

                <form action="{{ route('cart.add') }}" method="POST" enctype="multipart/form-data" class="ap24-buy" data-calculator data-product-id="{{ $product->id }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="ap24-buy__top">
                        <div>
                            <p class="ap24-buy__from">Prix unitaire (HT)</p>
                            <span class="ap24-buy__amount" id="unit-price">…</span>
                        </div>
                        @if ($discount > 0)
                            <span class="ap24-buy__save">Jusqu'à −{{ $discount }} %</span>
                        @endif
                    </div>

                    <div class="ap24-buy__row">
                        <span class="ap24-buy__label" id="qty-label">Quantité</span>
                        <div class="ap24-qty">
                            <div class="ap24-qty__ctrl">
                                <button type="button" class="ap24-qty__btn" data-qty-step="-1" aria-label="Diminuer la quantité">−</button>
                                <input id="qty" class="ap24-qty__input" type="number" name="qty" min="1" max="1000000" value="{{ $startQty }}" aria-labelledby="qty-label">
                                <button type="button" class="ap24-qty__btn" data-qty-step="1" aria-label="Augmenter la quantité">+</button>
                            </div>

                            @if ($presets->isNotEmpty())
                                <div class="ap24-qty__presets" role="group" aria-label="Quantités proposées">
                                    @foreach ($presets as $preset)
                                        <button type="button" class="ap24-preset {{ (int) $preset === $startQty ? 'is-active' : '' }}" data-qty-preset="{{ (int) $preset }}">{{ number_format((int) $preset, 0, ',', ' ') }}</button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="ap24-total">
                        <span class="ap24-total__label">
                            Total estimé
                            <small>HT · hors livraison</small>
                        </span>
                        <span class="ap24-total__value" id="total-price">…</span>
                    </div>

                    <div class="ap24-buy__row">
                        <span class="ap24-buy__label">Votre fichier d'impression · optionnel</span>
                        <label for="product-files" class="ap24-file">
                            <span class="ap24-file__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m17 8-5-5-5 5"/><path d="M12 3v12"/></svg>
                            </span>
                            <span>
                                <strong>Joindre mon fichier</strong>
                                <small>PDF, PNG, JPG, AI, EPS, ZIP · 10 Mo max</small>
                                <span class="ap24-file__name" data-file-name></span>
                            </span>
                        </label>
                        <input id="product-files" name="files[]" type="file" multiple accept=".pdf,.png,.jpg,.jpeg,.ai,.eps,.zip" class="sr-only">
                    </div>

                    <div class="ap24-buy__row">
                        <button type="submit" class="ap24-btn ap24-btn--yellow ap24-btn--block ap24-btn--flat">
                            Ajouter au panier
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                        </button>

                        <a href="{{ route('devis', ['produit' => $product->slug]) }}" data-devis-link
                           class="ap24-btn ap24-btn--ghost ap24-btn--block ap24-btn--flat mt-3">
                            Demander un devis pour ce produit
                        </a>

                        <ul class="ap24-reassure">
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                                Fichiers vérifiés gratuitement avant impression
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>
                                Livraison partout au Maroc
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                                Sans paiement en ligne
                            </li>
                        </ul>
                    </div>
                </form>

                @include('partials.product-options')
            </div>
        </div>

        @include('partials.product-extras')
    </div>

    @include('partials.product-calculator-script')
@endsection

@push('scripts')
    {{-- Parcours produit : consultation puis ajout au panier --}}
    <script type="module">
        const product = {
            product: @json($product->name),
            product_slug: @json($product->slug),
            category: @json($product->category?->name),
            family: @json($product->category?->parent?->name),
            base_price: {{ (float) $product->base_price }},
        };

        window.adoAnalytics?.capture('product_viewed', product);

        const form = document.querySelector('[data-calculator]');
        form?.addEventListener('submit', () => {
            if (! form.checkValidity()) return;

            window.adoAnalytics?.capture('add_to_cart', {
                ...product,
                quantity: Number(document.querySelector('#qty')?.value) || 1,
                has_file: Boolean(document.querySelector('#product-files')?.files.length),
            });
        });
    </script>
@endpush