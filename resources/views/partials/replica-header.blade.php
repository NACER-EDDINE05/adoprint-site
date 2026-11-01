@php
    $phone = $siteSettings->get('company.phone', '');
    $phoneIntl = $siteSettings->get('company.phone_intl', '');
    $email = $siteSettings->get('company.email', '');
    $whatsapp = preg_replace('/[^0-9]/', '', $siteSettings->get('company.whatsapp', $phoneIntl));

    $navIcons = [
        'impression-papier-bureautique'       => 'fas fa-book',
        'impression-grand-format'             => 'fas fa-image',
        'impression-textile-personnalisation' => 'fas fa-magic',
        'packaging-emballage'                 => 'fas fa-box-open',
        'signaletique-enseignes'              => 'fas fa-flag',
        'objets-publicitaires-cadeaux'        => 'fas fa-gift',
        'impression-evenementielle'           => 'fas fa-ticket-alt',
        'conception-graphique-design'         => 'fas fa-palette',
        'gravure-decoupe-cnc-laser'           => 'fas fa-layer-group',
    ];

    $desc = fn (?string $html, int $n = 110) => \Illuminate\Support\Str::limit(strip_tags((string) $html), $n);
@endphp

{{-- ==========================================================================
     En-tête principal : logo + recherche + boutons action + panier
     ========================================================================== --}}
<style>
.navbar-brand { padding: 0.25rem 0.5rem; }
.navbar-brand img { height: 48px !important; max-height: 48px !important; width: auto !important; max-width: 150px !important; object-fit: contain !important; display: block !important; }
@media (max-width: 576px) {
    .navbar-brand img { height: 40px !important; max-height: 40px !important; max-width: 110px !important; }
}
@media (max-width: 360px) {
    .navbar-brand img { height: 36px !important; max-height: 36px !important; max-width: 95px !important; }
}
</style>
<div class="navbar py-1 top-header navbar-expand-lg navbar-light">
    <div class="container-fluid justify-content-start">
        <button class="navbar-toggler mr-1 border-0 text-dark pl-2" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand mr-0 mr-lg-3" href="{{ route('home') }}" style="display:flex; align-items:center;">
            <img style="height: 48px; max-height: 48px; width: auto; max-width: 140px; object-fit: contain; display:block;" alt="ADO PRINT" src="{{ $siteSettings->get('brand.logo') ? '/storage/'.$siteSettings->get('brand.logo') : '/images/logo.png' }}">
        </a>

        <div class="mb-cart-search ml-auto col-lg w-auto">
            <button class="btn btn-link d-lg-none" id="search-toggle-btn" type="button" aria-label="Toggle Search">
                <i class="fas fa-search fa-lg" aria-hidden="true"></i>
            </button>
            <div class="search-toggle shadow" style="display:none;">
                <div class="header-search" role="search" aria-label="Rechercher un produit facilement ...">
                    <div class="input-group position-relative search-icon">
                        <span class="twitter-typeahead">
                            <form action="{{ route('products.index') }}" method="get" style="width:100%;">
                                <input type="search" name="q" id="top_search" value="{{ request('q') }}"
                                       class="typeahead form-control event_topSearch"
                                       placeholder="Rechercher un produit facilement ..."
                                       autocomplete="off" aria-label="Rechercher un produit facilement ..." />
                            </form>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="top_cms_header d-none d-md-flex align-items-center mr-3">
            {{-- WhatsApp --}}
            <div class="btn-group mr-1">
                <a href="https://wa.me/{{ $whatsapp }}?text=Bonjour%2C%20je%20souhaite%20un%20devis%20ADO%20PRINT%20:"
                   class="btn btn-custom" title="WhatsApp" target="_blank" rel="noopener">
                    <i class="fab fa-whatsapp btn-icon whatsapp-icon"></i><span class="btn-text">WhatsApp</span>
                </a>
            </div>

            {{-- Bouton d'appel --}}
            <div class="btn-group mr-1">
                <button aria-expanded="false" aria-haspopup="true" class="btn btn-custom dropdown-toggle" data-toggle="dropdown" type="button" title="Service client">
                    <i class="fas fa-phone-alt fa-flip-horizontal btn-icon"></i><span class="btn-text">Service client&nbsp;</span>
                </button>
                <div class="dropdown-menu dropdown-menu-wide">
                    <a class="dropdown-item dropdown-item-hover" href="tel:{{ preg_replace('/[^0-9+]/', '', $phoneIntl ?: $phone) }}">{{ $phone }}</a>
                    <a class="dropdown-item dropdown-item-hover" href="https://wa.me/{{ $whatsapp }}">WhatsApp : {{ $phone }}</a>
                    <a class="dropdown-item dropdown-item-hover" href="mailto:{{ $email }}">{{ $email }}</a>
                    <a class="dropdown-item btn-custom-cta" href="{{ route('contact') }}">Demander un rappel</a>
                </div>
            </div>

            {{-- Bouton Devis sur mesure --}}
            <div class="btn-group ml-1">
                <button aria-expanded="false" aria-haspopup="true" class="btn btn-custom dropdown-toggle" data-toggle="dropdown" type="button" title="Devis sur mesure">
                    <i class="fas fa-file-invoice btn-icon"></i><span class="btn-text">Devis sur mesure&nbsp;</span>
                </button>
                <div class="dropdown-menu dropdown-menu-wide">
                    <strong class="dropdown-item dropdown-item-hover">Vous avez un projet particulier pas listé en ligne ?</strong>
                    <hr class="dropdown-divider">
                    <span class="dropdown-item dropdown-item-hover">Envoyez-nous votre demande de devis sur mesure en ligne ou par e-mail sur {{ $email }}</span>
                    <a class="dropdown-item btn-custom-cta" href="{{ route('devis') }}">Envoyer une demande de devis en ligne</a>
                </div>
            </div>

            {{-- Bouton Aide --}}
            <div class="btn-group ml-1">
                <button aria-expanded="false" aria-haspopup="true" class="btn btn-custom dropdown-toggle" data-toggle="dropdown" type="button" title="Aide">
                    <i class="fas fa-question-circle btn-icon"></i><span class="btn-text">Aide&nbsp;</span>
                </button>
                <div class="dropdown-menu dropdown-menu-gray dropdown-menu-wide" id="help-dropdown">
                    <a class="dropdown-item dropdown-item-hover" href="{{ route('contact') }}"><i class="fas fa-map-marker-alt"></i> Adresse showroom</a>
                    <a class="dropdown-item dropdown-item-hover" href="{{ route('aide') }}"><i class="fas fa-question"></i> Comment commander ?</a>
                    <a class="dropdown-item dropdown-item-hover" href="{{ route('guides.index') }}"><i class="fas fa-handshake"></i> Guides d'impression</a>
                    <a class="dropdown-item dropdown-item-hover" href="{{ route('devis') }}"><i class="fas fa-star"></i> Devis sur mesure</a>
                    <a class="dropdown-item dropdown-item-hover" href="{{ route('a-propos') }}"><i class="fas fa-paint-brush"></i> À propos de nous</a>
                </div>
            </div>
        </div>

        <a class="btn btn-link text-primary px-2" href="{{ route('cart') }}" data-placement="bottom" data-container="body" data-trigger="click" title="Panier">
            <i class="fas fa-shopping-cart fa-lg"></i><span class="badge badge-primary">{{ $cartCount }}</span>
        </a>
    </div>
</div>

{{-- ==========================================================================
     Barre de navigation : méga-menus des familles
     ========================================================================== --}}
<nav class="navbar navbar-expand-lg border-bottom px-0 py-0">
    <div class="container-fluid position-relative px-2">
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav w-100 justify-content-between">
                @foreach ($navCategories as $family)
                    @php
                        $fModel = $family;
                        $icon = $navIcons[$fModel->slug] ?? 'far fa-file-alt';
                        $firstChildPhoto = null;
                        $firstChildDesc = null;
                        foreach ($fModel->children as $child) {
                            $withPhoto = $child->products->firstWhere(fn ($p) => $p->photoUrl());
                            if ($withPhoto && ! $firstChildPhoto) {
                                $firstChildPhoto = $withPhoto->photoUrl();
                                $firstChildDesc  = $desc($child->description ?? '');
                            }
                        }
                    @endphp
                    <li class="nav-item dropdown position-static promotion-menu">
                        <a href="{{ route('category.show', $fModel->slug) }}" class="nav-link" target="_self" aria-haspopup="true">
                            <i class="{{ $icon }}" role="presentation"></i><span class="sr-only">{{ $fModel->navLabel() }}</span><span> {!! implode('<br>', array_filter($fModel->navLines())) !!} </span>
                        </a>
                        <div class="dropdown-menu mt-0">
                            <p><div class="row mx-0 w-100">
                                <div class="col-md-9 promo_category_menu">
                                    <div class="card-columns">
                                        @foreach ($fModel->children as $child)
                                            <ul class="list-unstyled py-1">
                                                <li>
                                                    <a href="{{ route('category.show', $child->slug) }}"
                                                       class="all-product dropdown-item megamenu-category-link"
                                                       data-img="{{ $child->products->firstWhere(fn ($p) => $p->photoUrl())?->photoUrl() }}"
                                                       data-name="{{ $child->name }}"
                                                       data-desc="{{ $desc($child->description ?? '') }}"
                                                       data-url="{{ route('category.show', $child->slug) }}"
                                                       data-category-id="{{ $child->id }}">
                                                        <strong>{{ $child->name }}</strong>
                                                    </a>
                                                </li>
                                                @foreach ($child->products->take(5) as $prod)
                                                    @php
                                                        $minPrice = (float) ($prod->priceGrids->min('price') ?: $prod->base_price);
                                                        $minQty = (int) ($prod->priceGrids->min('min_qty') ?: 100);
                                                        if ($minPrice > 0 && $minPrice < 1) $minPrice = 1;
                                                        $badge = 'Dès '.number_format($minPrice, 0, ',', ' ').' dh';
                                                        if ($prod->priceGrids->count() >= 2 && $prod->priceGrids->max('price') > 0) {
                                                            $discount = (int) round((1 - $minPrice / $prod->priceGrids->max('price')) * 100);
                                                            if ($discount >= 25) $badge .= ' · -'.$discount.'%';
                                                        }
                                                    @endphp
                                                    <li>
                                                        <a href="{{ route('product.show', $prod->slug) }}"
                                                           class="all-product dropdown-item edatalayer megamenu-product-link"
                                                           data-img="{{ $prod->photoUrl() }}"
                                                           data-name="{{ $prod->name }}"
                                                           data-desc="{{ $desc($prod->description ?? '') }}"
                                                           data-url="{{ route('product.show', $prod->slug) }}">
                                                            {{ $prod->name }}
                                                            <span class="ml-2 badge badge-info">{{ $badge }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-3 ml-auto d-none d-md-block py-2 category_header_content megamenu-image-container"
                                     data-default-img="{{ $firstChildPhoto }}"
                                     data-default-name="{{ $fModel->navLabel() }}"
                                     data-default-desc="{{ $desc($fModel->description ?? '', 120) }}"
                                     data-default-url="{{ route('category.show', $fModel->slug) }}">
                                    <a href="{{ route('category.show', $fModel->slug) }}" class="megamenu-image-link d-none mb-2">
                                        <img src="" alt="" class="megamenu-image img-fluid">
                                    </a>
                                    <span class="megamenu-image-name font-weight-bold text-primary mb-2 d-block d-none"></span>
                                    <div class="megamenu-image-description mt-2 d-none"></div>
                                    <a href="{{ route('category.show', $fModel->slug) }}" class="btn btn-primary btn-sm megamenu-image-btn mt-3 d-none">Plus de détails</a>
                                </div>
                            </div></p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>
