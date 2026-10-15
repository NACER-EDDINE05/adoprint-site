{{-- ================================================================ --}}
{{-- Barre d'information — contacts réels, confiance --}}
{{-- ================================================================ --}}
<div class="bg-slate-900 text-xs text-slate-300">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-x-6 gap-y-1 px-4 py-2">
        <div class="flex flex-wrap items-center gap-x-5 gap-y-1">
            <span class="flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5 text-brand-cyan" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2a9 9 0 0 1 9 9"/><path d="M13 6a5 5 0 0 1 5 5"/><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/></svg>
                <a class="font-semibold text-white hover:text-brand-cyan" href="tel:{{ $siteSettings->get('company.phone_intl', $siteSettings->get('company.phone', '')) }}">{{ $siteSettings->get('company.phone', '') }}</a>
            </span>
            <span class="hidden items-center gap-1.5 md:flex">
                <svg class="h-3.5 w-3.5 text-brand-cyan" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                <a class="hover:text-white" href="mailto:{{ $siteSettings->get('company.email', '') }}">{{ $siteSettings->get('company.email', '') }}</a>
            </span>
            <span class="hidden items-center gap-1.5 lg:flex">
                <svg class="h-3.5 w-3.5 text-brand-cyan" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="13" r="8"/><path d="M5 3 2 6"/><path d="m22 6-3-3"/><path d="m9 13 2 2 4-4"/></svg>
                {{ $siteSettings->get('company.hours', '') }}
            </span>
        </div>
        <nav class="flex items-center gap-4">
            <a class="hover:text-white" href="{{ route('aide') }}">Aide</a>
            <a class="hover:text-white" href="{{ route('contact') }}">Contact</a>
            @auth
                <a class="hover:text-white" href="{{ url('/admin') }}">Mon compte</a>
            @else
                <a class="hover:text-white" href="{{ route('filament.admin.auth.login') }}">Connexion</a>
            @endauth
        </nav>
    </div>
</div>

{{-- ================================================================ --}}
{{-- En-tête principal --}}
{{-- ================================================================ --}}
<header class="sticky top-0 z-40 border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-4 px-4 py-3">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center" aria-label="ADO PRINT — Accueil">
            <img src="/images/logo.png" alt="ADO PRINT" class="h-10 w-auto" width="195" height="40">
        </a>

        <form action="{{ route('products.index') }}" method="GET" class="order-3 w-full sm:order-2 sm:w-auto sm:flex-1 sm:max-w-xl" role="search">
            <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-slate-50 transition focus-within:border-brand-cyan focus-within:bg-white">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher un produit (cartes, flyers, roll-up…)" class="w-full bg-transparent px-4 py-2.5 text-sm outline-none" aria-label="Rechercher un produit">
                <button type="submit" class="px-4 text-slate-400 transition hover:text-brand-cyan-dark" aria-label="Lancer la recherche">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </button>
            </div>
        </form>

        <div class="order-2 ml-auto flex items-center gap-2 sm:order-3">
            {{-- Panier --}}
            <a href="{{ route('cart') }}" class="relative inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-brand-cyan hover:text-brand-cyan-dark" aria-label="Voir le panier">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                <span class="hidden md:inline">Panier</span>
                @if ($cartCount > 0)
                    <span class="absolute -right-1.5 -top-1.5 grid h-5 min-w-5 place-items-center rounded-full bg-brand-cyan px-1 text-[11px] font-bold text-white">{{ $cartCount }}</span>
                @endif
            </a>

            <a href="{{ route('devis') }}" class="hidden items-center gap-2 rounded-xl bg-brand-cyan px-5 py-2.5 text-sm font-bold text-white transition hover:bg-brand-cyan-dark lg:inline-flex">
                Demander un devis
            </a>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- Barre de navigation principale --}}
    {{-- ================================================================ --}}
    <style>
        /* Menu déroulant « Nos Produits » — ouverture au survol (CSS pur) */
        .nav-panel{position:absolute;left:0;top:100%;z-index:50;min-width:15rem;padding-top:.25rem;visibility:hidden;opacity:0;transition:opacity .15s ease,visibility .15s ease;}
        .nav-group:hover .nav-panel,.nav-group:focus-within .nav-panel{visibility:visible;opacity:1;}
    </style>
    <nav class="border-t border-slate-100 bg-white" aria-label="Navigation principale">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-center gap-1 px-3 py-2.5 text-[13px] font-medium text-slate-600">
            {{-- Nos Produits (menu déroulant par catégorie) --}}
            <div class="nav-group relative">
                <a href="{{ route('products.index') }}" class="flex items-center gap-1.5 whitespace-nowrap rounded-lg px-3 py-2.5 transition hover:text-brand-cyan-dark {{ request()->routeIs('products.index') || request()->routeIs('category.show') || request()->routeIs('product.show') ? 'text-brand-cyan-dark' : '' }}">
                    Nos Produits
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </a>
                <div class="nav-panel">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white py-2 shadow-lg ring-1 ring-slate-900/5">
                        <a href="{{ route('products.index') }}" class="block px-4 py-2 font-semibold text-slate-900 transition hover:bg-slate-50 hover:text-brand-cyan-dark">Tous les produits</a>
                        <div class="my-1 border-t border-slate-100"></div>
                        @foreach ($navCategories as $c)
                            <a href="{{ route('category.show', $c->slug) }}" class="block px-4 py-2 text-slate-600 transition hover:bg-slate-50 hover:text-brand-cyan-dark">{{ $c->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            <a href="{{ route('devis') }}" class="whitespace-nowrap rounded-lg px-3 py-2.5 transition hover:text-brand-cyan-dark {{ request()->routeIs('devis') ? 'text-brand-cyan-dark' : '' }}">Demander un Devis</a>
            <a href="{{ route('guides.index') }}" class="whitespace-nowrap rounded-lg px-3 py-2.5 transition hover:text-brand-cyan-dark {{ request()->routeIs('guides.*') ? 'text-brand-cyan-dark' : '' }}">Guide d'Impression</a>
            <a href="{{ route('apropos') }}" class="whitespace-nowrap rounded-lg px-3 py-2.5 transition hover:text-brand-cyan-dark {{ request()->routeIs('apropos') ? 'text-brand-cyan-dark' : '' }}">À Propos</a>
            <a href="{{ route('contact') }}" class="whitespace-nowrap rounded-lg px-3 py-2.5 transition hover:text-brand-cyan-dark {{ request()->routeIs('contact') ? 'text-brand-cyan-dark' : '' }}">Contact</a>
        </div>
    </nav>
</header>