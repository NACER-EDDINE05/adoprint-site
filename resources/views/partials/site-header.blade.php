@php
    $phone = $siteSettings->get('company.phone', '+212 6 12 34 56 78');
    $phoneIntl = $siteSettings->get('company.phone_intl', '+212612345678');
    $email = $siteSettings->get('company.email', 'contact@adoprint.ma');
    $whatsapp = preg_replace('/[^0-9]/', '', $siteSettings->get('company.whatsapp', '+212612345678'));
@endphp

<header class="ap24-header">
    <div class="ap24-header-top"></div>

    <div class="ap24-header__container">
        <!-- Barre supérieure compacte -->
        <div class="ap24-topbar">
            <div class="ap24-topbar__left">
                <a href="tel:{{ $phoneIntl }}" class="ap24-topbar__item" aria-label="Téléphone">
                    <svg class="ap24-topbar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 2 2 0 0 1 2.11 2A12.84 12.84 0 0 0 22.5 17.72c0 .68-.09 1.36-.29 2h-3a2 2 0 0 1-2-2v-3z"/></svg>
                    {{ $phone }}
                </a>
                <a href="mailto:{{ $email }}" class="ap24-topbar__item" aria-label="Email">
                    <svg class="ap24-topbar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    {{ $email }}
                </a>
            </div>

            <div class="ap24-topbar__center hidden md:flex">
                <a href="{{ route('devis') }}" class="ap24-topbar__item" aria-label="Demander un devis">
                    <svg class="ap24-topbar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><polyline points="8 5 12 5"/></svg>
                    Devis sur mesure
                </a>
                <a href="{{ route('contact') }}" class="ap24-topbar__item" aria-label="Aide">
                    <svg class="ap24-topbar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>
                    Aide
                </a>
            </div>

            <div class="ap24-topbar__right">
                <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener" class="ap24-topbar__item" aria-label="WhatsApp">
                    <svg class="ap24-topbar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12c0-4.89-4.89-8.89-10.89-8.89C3.68 3.11 0 7.11 0 12c0 4.89 4.89 8.89 10.89 8.89 4.89 0 8.89-4.89 8.89-8.89z"/><line x1="16" y1="8" x2="13" y2="11"/><line x1="17" y1="14" x2="13" y2="18"/></svg>
                    WhatsApp
                </a>
                <a href="{{ route('cart') }}" class="ap24-topbar__item" aria-label="Panier">
                    <svg class="ap24-topbar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <span class="ap24-topbar__cart-count" id="ap24-cart-count">{{ $cartCount }}</span>
                </a>
            </div>
        </div>

        <!-- Barre principale -->
        <div class="ap24-header__main">
            <div class="ap24-header__logo">
                <a href="{{ route('home') }}" aria-label="ADO PRINT — Accueil">
                    <img src="{{ isset($siteSettings) && $siteSettings->get('brand.logo') ? '/storage/'.$siteSettings->get('brand.logo') : '/images/logo.png' }}" alt="ADO PRINT" class="ap24-logo-img" style="height: 50px; width: auto; max-width: 200px; object-fit: contain;">
                </a>
            </div>

            <div class="ap24-header__search">
                <form method="GET" action="{{ route('products.index') }}" class="ap24-search-form">
                    <div class="ap24-search-wrapper">
                        <svg class="ap24-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" name="q" placeholder="Rechercher des produits…" class="ap24-search-input" value="{{ request('q') }}">
                        <button type="submit" class="ap24-search-submit">Rechercher</button>
                    </div>
                </form>
            </div>

            <div class="ap24-header__actions">
                <a href="{{ route('devis') }}" class="ap24-btn ap24-btn--sm ap24-header__btn ap24-btn--devis" aria-label="Demander un devis">
                    Devis
                    <svg class="ap24-header__btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
                </a>

                <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener" class="ap24-btn ap24-btn--sm ap24-header__btn ap24-btn--whatsapp" aria-label="WhatsApp">
                    Service client
                    <svg class="ap24-header__btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12c0-4.89-4.89-8.89-10.89-8.89C3.68 3.11 0 7.11 0 12c0 4.89 4.89 8.89 10.89 8.89 4.89 0 8.89-4.89 8.89-8.89z"/><line x1="16" y1="8" x2="13" y2="11"/><line x1="17" y1="14" x2="13" y2="18"/></svg>
                </a>

                <button class="ap24-header__menu-btn" id="ap24-menu-btn" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="ap24-menu-overlay">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Navigation des catégories (méga-menu) -->
    <nav class="ap24-nav" aria-label="Catégories">
        <div class="ap24-nav__container">
            @foreach ($navCategories as $family)
                @php
                    $navName = trim((string) data_get($family, 'name', ''));
                    $navSlug = (string) data_get($family, 'slug', '');
                    $navChildren = data_get($family, 'children', collect());
                    if (is_array($navChildren)) {
                        $navChildren = collect($navChildren);
                    }
                    $navChildCount = $navChildren instanceof \Illuminate\Support\Collection
                        ? $navChildren->count()
                        : (is_countable($navChildren) ? count($navChildren) : 0);
                    if (is_object($family) && method_exists($family, 'navLines')) {
                        $lines = $family->navLines();
                    } elseif (str_contains($navName, ' & ')) {
                        [$navLine1, $navLine2] = explode(' & ', $navName, 2);
                        $lines = [trim($navLine1) . ' &', trim($navLine2)];
                    } else {
                        $navParts = preg_split('/\s+/', $navName, 2);
                        $lines = count($navParts) === 2 ? $navParts : [$navName, ''];
                    }
                @endphp
                <div class="ap24-nav__item">
                    <a href="{{ route('category.show', $navSlug) }}"
                       class="ap24-nav__link {{ request()->routeIs('category.show') && request()->route('slug') === $navSlug ? 'is-active' : '' }}"
                       aria-label="{{ $navName }}">
                        <span class="ap24-nav__icon" aria-hidden="true">
                            @include('partials.category-icon', ['slug' => $navSlug])
                        </span>
                        <span class="ap24-nav__text">{{ $lines[0] }}@if (! empty($lines[1])) {{ $lines[1] }}@endif</span>
                    </a>

                    <div class="ap24-menu" role="group" aria-label="{{ $navName }}">
                        <div class="ap24-menu__panel">
                            <div class="ap24-menu__head">
                                <div class="ap24-menu__head-icon">
                                    @include('partials.category-icon', ['slug' => $navSlug])
                                </div>
                                <div class="ap24-menu__head-text">
                                    <span class="ap24-menu__eyebrow">Catégorie</span>
                                    <span class="ap24-menu__title">{{ $navName }}</span>
                                    <span class="ap24-menu__meta">{{ $navChildCount }} sous-catégories</span>
                                </div>
                                <a href="{{ route('category.show', $navSlug) }}" class="ap24-menu__all-link">
                                    Voir la catégorie →
                                </a>
                            </div>

                            <div class="ap24-menu__grid">
                                @foreach ($navChildren as $child)
                                    @php
                                        $childName = trim((string) data_get($child, 'name', ''));
                                        $childSlug = (string) data_get($child, 'slug', '');
                                        $childProducts = data_get($child, 'products', collect());
                                        if (is_array($childProducts)) {
                                            $childProducts = collect($childProducts);
                                        }
                                        $childProducts = $childProducts instanceof \Illuminate\Support\Collection
                                            ? $childProducts
                                            : collect($childProducts);
                                        $childThumb = $childProducts->first(fn ($product) => is_object($product) && method_exists($product, 'photoUrl') && $product->photoUrl());
                                        $childThumbUrl = $childThumb?->photoUrl();
                                    @endphp
                                    <a href="{{ route('category.show', $childSlug) }}" class="ap24-menu__item">
                                        @if ($childThumbUrl)
                                            <span class="ap24-menu__item__thumb">
                                                <img src="{{ $childThumbUrl }}" alt="{{ $childName }}" loading="lazy" class="ap24-menu__item__thumb-img">
                                            </span>
                                        @else
                                            <span class="ap24-menu__item__thumb">
                                                <span class="ap24-fallback">@include('partials.category-icon', ['slug' => $childSlug])</span>
                                            </span>
                                        @endif
                                        <span class="ap24-menu__item__label">{{ $childName }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </nav>
</header>

<!-- Panneau latéral mobile -->
<div class="ap24-menu-overlay" id="ap24-menu-overlay" aria-hidden="true" style="display:none">
    <div class="ap24-menu-overlay__panel">
        <div class="ap24-menu-overlay__header">
            <button class="ap24-menu-overlay__close" id="ap24-menu-close" aria-label="Fermer le menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="ap24-menu-overlay__body" id="ap24-menu-overlay-body"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('ap24-menu-btn');
    var overlay = document.getElementById('ap24-menu-overlay');
    var closeBtn = document.getElementById('ap24-menu-close');
    var body = document.getElementById('ap24-menu-overlay-body');

    function openMenu() {
        overlay.style.display = 'block';
        overlay.setAttribute('aria-hidden', 'false');
        btn.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
        if (!body.innerHTML) {
            var nav = document.querySelector('.ap24-nav');
            if (nav) {
                var clone = nav.cloneNode(true);
                clone.classList.remove('ap24-nav');
                clone.classList.add('ap24-nav--mobile');
                clone.querySelectorAll('.ap24-menu').forEach(function(m) { m.style.display = 'none'; });
                body.appendChild(clone);
            }
        }
    }

    function closeMenu() {
        overlay.style.display = 'none';
        overlay.setAttribute('aria-hidden', 'true');
        btn.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    btn.addEventListener('click', openMenu);
    closeBtn.addEventListener('click', closeMenu);
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) closeMenu();
    });
});
</script>
