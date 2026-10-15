@extends('layouts.storefront')

@section('title', 'ADO PRINT — Imprimerie en ligne au Maroc | Cartes de visite, flyers, brochures')

@section('content')
    {{-- ================================================================ --}}
    {{-- HÉRO — promesse claire, fond blanc, produits en avant --}}
    {{-- ================================================================ --}}
    <section class="relative overflow-hidden bg-white">
        {{-- Couche photo : téléversée depuis l'admin (Apparence du site) --}}
        @php $heroImage = $siteSettings->get('home.hero_image'); $onPhoto = (bool) $heroImage; @endphp
        @if ($heroImage)
            <div class="absolute inset-0" aria-hidden="true">
                <img src="/storage/{{ $heroImage }}" alt="" class="h-full w-full object-cover">
                {{-- Voile sombre : lisibilité du texte blanc sur la photo --}}
                <div class="absolute inset-0 bg-slate-900/55"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900/80 via-slate-900/45 to-slate-900/15"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/65 via-transparent to-slate-900/25"></div>
                <div class="brand-stripes absolute inset-x-0 top-0 h-1.5"></div>
                <div class="brand-stripes absolute inset-x-0 bottom-0 h-1.5"></div>
            </div>
        @else
            {{-- Visuel de substitution : trame presse discrète aux encres de la marque --}}
            <div class="absolute inset-0" aria-hidden="true">
                <div class="brand-stripes absolute inset-x-0 top-0 h-1.5"></div>
                <div class="brand-stripes absolute inset-x-0 bottom-0 h-1.5"></div>
                <div class="absolute -right-40 top-0 h-[130%] w-[42rem] rotate-12 bg-brand-cyan-light/25"></div>
                <div class="absolute -right-24 top-0 h-[130%] w-10 rotate-12 bg-brand-magenta/10"></div>
                <div class="absolute -right-16 top-0 h-[130%] w-6 rotate-12 bg-brand-yellow/20"></div>
            </div>
        @endif
        <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 py-14 lg:grid-cols-[1.05fr_0.95fr] lg:py-20">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold {{ $onPhoto ? 'bg-white/10 text-white ring-1 ring-white/30 backdrop-blur-sm' : 'bg-brand-cyan-light text-brand-cyan-dark' }}">
                    Impression en 24 h · Livraison partout au Maroc
                </span>

                <h1 class="mt-6 text-4xl font-extrabold leading-[1.1] tracking-tight sm:text-5xl {{ $onPhoto ? 'text-white' : 'text-slate-900' }}">
                    Impression professionnelle,
                    <span class="text-brand-cyan">simple et rapide.</span>
                </h1>

                <p class="mt-5 max-w-xl text-lg leading-relaxed {{ $onPhoto ? 'text-slate-100/90' : 'text-slate-600' }}">
                    Cartes de visite, flyers, brochures, roll-up, packaging : configurez votre produit,
                    téléchargez vos fichiers et recevez votre commande partout au Maroc.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('products.index') }}" class="group inline-flex items-center gap-2 rounded-xl bg-brand-cyan px-7 py-3.5 font-bold text-white transition hover:bg-brand-cyan-dark">
                        Commander maintenant
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('devis') }}" class="inline-flex items-center gap-2 rounded-xl border px-7 py-3.5 font-semibold transition {{ $onPhoto ? 'border-white/40 text-white hover:bg-white/10' : 'border-slate-300 text-slate-700 hover:border-brand-cyan hover:text-brand-cyan-dark' }}">
                        Obtenir un devis
                    </a>
                </div>

                <div class="mt-10 grid max-w-lg grid-cols-3 gap-6 border-t pt-8 {{ $onPhoto ? 'border-white/25' : 'border-slate-100' }}">
                    <div>
                        <p class="text-2xl font-extrabold {{ $onPhoto ? 'text-white' : 'text-slate-900' }}">24 h</p>
                        <p class="mt-0.5 text-xs font-semibold {{ $onPhoto ? 'text-slate-300' : 'text-slate-500' }}">Délai d'impression</p>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold {{ $onPhoto ? 'text-white' : 'text-slate-900' }}">9</p>
                        <p class="mt-0.5 text-xs font-semibold {{ $onPhoto ? 'text-slate-300' : 'text-slate-500' }}">Familles de produits</p>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold {{ $onPhoto ? 'text-white' : 'text-slate-900' }}">100%</p>
                        <p class="mt-0.5 text-xs font-semibold {{ $onPhoto ? 'text-slate-300' : 'text-slate-500' }}">Livré au Maroc</p>
                    </div>
                </div>
            </div>

            {{-- Mosaïque produits (visuel de substitution propre) --}}
            <div class="grid grid-cols-2 gap-4">
                @foreach ($univers->take(4) as $u)
                    <a href="{{ route('category.show', $u['model']->slug) }}" class="group flex flex-col items-start gap-3 rounded-2xl border p-5 transition hover:-translate-y-1 hover:border-brand-cyan hover:shadow-lg {{ $onPhoto ? 'border-white/30 bg-white/90 backdrop-blur-sm' : 'border-slate-200 bg-slate-50' }}">
                        <span class="grid h-14 w-14 place-items-center rounded-xl bg-brand-cyan-light text-brand-cyan-dark transition group-hover:bg-brand-cyan group-hover:text-white">
                            <span class="h-7 w-7">@include('partials.category-icon', ['slug' => $u['model']->slug])</span>
                        </span>
                        <span>
                            <span class="block text-sm font-bold text-slate-900">{{ $u['model']->name }}</span>
                            @if ($u['fromPrice'] !== null)
                                <span class="mt-0.5 block text-xs font-semibold text-slate-500">Dès {{ $money($u['fromPrice']) }} dhs</span>
                            @endif
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================================================================ --}}
    {{-- BANDE PROMOTIONS — remises réelles issues des grilles tarifaires --}}
    {{-- ================================================================ --}}
    @if ($offers->isNotEmpty())
    @php $topDiscount = $offers->max('discount'); @endphp
    <section class="relative overflow-hidden bg-slate-900">
        {{-- bandes décoratives CMJN --}}
        <div class="brand-stripes absolute inset-x-0 top-0 h-1.5"></div>
        <div class="brand-stripes absolute inset-x-0 bottom-0 h-1.5"></div>

        {{-- ---- BANDEAU DÉFILANT « moving band » : remises réelles ---- --}}
        <div class="marquee mt-1.5 bg-gradient-to-r from-brand-magenta-dark via-brand-magenta to-brand-yellow-dark py-2">
            <div class="marquee-track">
                @for ($__m = 0; $__m < 2; $__m++)
                    @foreach ($offers as $offer)
                        <span class="flex items-center gap-2 whitespace-nowrap px-6 text-sm font-extrabold uppercase tracking-wide text-white">
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                            Jusqu'à -{{ $offer['discount'] }}% · {{ $offer['product']->name }}
                        </span>
                        <span class="text-white/50" aria-hidden="true">◆</span>
                    @endforeach
                @endfor
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-12">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-brand-yellow px-3 py-1 text-xs font-extrabold uppercase tracking-wide text-slate-900">
                        <span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-brand-magenta opacity-75"></span><span class="relative inline-flex h-2 w-2 rounded-full bg-brand-magenta"></span></span>
                        Offres limitées
                    </span>
                    <h2 class="mt-3 text-2xl font-extrabold text-white sm:text-3xl">Nos promotions {{ date('Y') }}</h2>
                    <p class="mt-1 max-w-xl text-slate-300">Prix dégressifs : plus la quantité augmente, plus le prix unitaire baisse. Économisez jusqu'à <span class="font-extrabold text-brand-yellow">-{{ $topDiscount }}%</span> sur vos grands tirages.</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn-glow inline-flex items-center gap-2 rounded-xl bg-brand-magenta px-6 py-3 font-bold text-white transition hover:bg-brand-magenta-dark">
                    Profiter des offres
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($offers as $offer)
                    <a href="{{ route('product.show', $offer['product']->slug) }}" class="group relative flex flex-col overflow-hidden rounded-2xl border border-white/10 bg-white shadow-lg transition duration-200 hover:-translate-y-1.5 hover:border-brand-magenta hover:shadow-2xl">
                        <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                            @if ($offer['product']->photoUrl())
                                <img src="{{ $offer['product']->photoUrl() }}" alt="{{ $offer['product']->name }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-110">
                            @else
                                <span class="grid h-full place-items-center text-slate-300"><span class="h-14 w-14">@include('partials.category-icon', ['slug' => $offer['product']->category?->slug ?? 'default'])</span></span>
                            @endif
                            <span class="badge-discount absolute -right-2 top-3 grid h-14 w-14 place-items-center rounded-full bg-brand-magenta text-white ring-4 ring-white">
                                <span class="text-center leading-none">
                                    <span class="block text-lg font-extrabold">-{{ $offer['discount'] }}%</span>
                                    <span class="block text-[9px] font-bold uppercase tracking-wide">max</span>
                                </span>
                            </span>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <span class="text-[11px] font-bold uppercase tracking-wide text-brand-cyan-dark">{{ $offer['product']->category?->name ?? 'Produit' }}</span>
                            <h3 class="mt-1 font-bold text-slate-900 transition group-hover:text-brand-magenta">{{ $offer['product']->name }}</h3>
                            <p class="mt-1 flex-1 text-sm leading-relaxed text-slate-500">{{ \Illuminate\Support\Str::limit(strip_tags($offer['product']->description ?? ''), 70) }}</p>
                            <div class="mt-4 flex items-end justify-between gap-2">
                                <div>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-xl font-extrabold text-brand-cyan-dark">{{ $money($offer['fromPrice']) }} dhs</span>
                                        @if ($offer['beforePrice'] > $offer['fromPrice'])
                                            <span class="text-xs font-medium text-slate-400 line-through">{{ $money($offer['beforePrice']) }} dhs</span>
                                        @endif
                                    </div>
                                    <p class="text-xs font-semibold text-slate-500">Dès {{ $offer['minQty'] }} ex. · <span class="text-brand-magenta">économisez {{ $money($offer['beforePrice'] - $offer['fromPrice']) }} dhs</span></p>
                                </div>
                                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-slate-100 text-slate-500 transition group-hover:bg-brand-magenta group-hover:text-white">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ================================================================ --}}
    {{-- CATÉGORIES DE PRODUITS --}}
    {{-- ================================================================ --}}
    <section class="mx-auto max-w-7xl px-4 py-16">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 sm:text-3xl">Nos catégories</h2>
                <p class="mt-1 text-slate-500">Tout ce qu'il faut pour votre communication visuelle.</p>
            </div>
            <a href="{{ route('products.index') }}" class="group hidden shrink-0 items-center gap-1.5 font-semibold text-brand-cyan-dark hover:text-brand-cyan sm:inline-flex">
                Tout voir
                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($univers as $u)
                <a href="{{ route('category.show', $u['model']->slug) }}" class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:border-brand-cyan hover:shadow-lg">
                    {{-- Bandeau photo : produit le moins cher de la catégorie --}}
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                        @if ($u['cheapest'] && $u['cheapest']->photoUrl())
                            <img src="{{ $u['cheapest']->photoUrl() }}" alt="{{ $u['model']->name }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-110">
                        @else
                            <div class="grid h-full place-items-center bg-brand-cyan-light text-brand-cyan-dark">
                                <span class="h-12 w-12">@include('partials.category-icon', ['slug' => $u['model']->slug])</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-slate-900/10 to-transparent"></div>
                        @if ($u['fromPrice'])
                            <span class="absolute bottom-3 left-3 rounded-full bg-white/95 px-3 py-1 text-xs font-extrabold text-brand-cyan-dark shadow-sm backdrop-blur-sm">Dès {{ $money($u['fromPrice']) }} dhs</span>
                        @endif
                        @if ($u['items']->isNotEmpty())
                            <span class="absolute right-3 top-3 rounded-full bg-slate-900/65 px-2.5 py-1 text-[11px] font-bold text-white backdrop-blur-sm">{{ $u['items']->count() }} produit{{ $u['items']->count() > 1 ? 's' : '' }}</span>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-5">
                        <div class="flex items-center gap-2.5">
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-brand-cyan-light text-brand-cyan-dark transition group-hover:scale-105 group-hover:bg-brand-cyan group-hover:text-white">
                                <span class="h-5 w-5">@include('partials.category-icon', ['slug' => $u['model']->slug])</span>
                            </span>
                            <h3 class="font-bold text-slate-900 transition group-hover:text-brand-cyan-dark">{{ $u['model']->name }}</h3>
                        </div>
                        <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-slate-500">{{ $u['model']->description }}</p>
                        <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-bold text-brand-cyan-dark">
                            Voir les produits
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ================================================================ --}}
    {{-- PRODUITS PHARES --}}
    {{-- ================================================================ --}}
    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900 sm:text-3xl">Produits phares</h2>
                    <p class="mt-1 text-slate-500">Les plus commandés par nos clients.</p>
                </div>
                <a href="{{ route('products.index') }}" class="group hidden shrink-0 items-center gap-1.5 font-semibold text-brand-cyan-dark hover:text-brand-cyan sm:inline-flex">
                    Tout le catalogue
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>

            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($featured as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================================================================ --}}
    {{-- AVANTAGES --}}
    {{-- ================================================================ --}}
    <section class="mx-auto max-w-7xl px-4 py-16">
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['icon' => '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>', 'title' => 'Livraison rapide', 'text' => 'Expédition 24–48 h partout au Maroc.'],
                ['icon' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>', 'title' => 'Qualité garantie', 'text' => 'Contrôle qualité sur chaque commande avant expédition.'],
                ['icon' => '<path d="M13 2a9 9 0 0 1 9 9"/><path d="M13 6a5 5 0 0 1 5 5"/><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/>', 'title' => 'Support dédié', 'text' => 'Par téléphone, email et WhatsApp aux heures d\'ouverture.'],
                ['icon' => '<rect width="18" height="11" x="3" y="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>', 'title' => 'Prix transparents', 'text' => 'Grilles dégressives publiées, calcul instantané en ligne.'],
            ] as $benefit)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-brand-cyan hover:shadow-lg">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-cyan-light text-brand-cyan-dark">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $benefit['icon'] !!}</svg>
                    </span>
                    <h3 class="mt-4 font-bold text-slate-900">{{ $benefit['title'] }}</h3>
                    <p class="mt-1.5 text-sm leading-relaxed text-slate-500">{{ $benefit['text'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ================================================================ --}}
    {{-- PROCESSUS DE COMMANDE --}}
    {{-- ================================================================ --}}
    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-2xl font-extrabold text-slate-900 sm:text-3xl">Commander en 4 étapes</h2>
                <p class="mt-2 text-slate-500">Simple, rapide et sans engagement.</p>
            </div>

            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['n' => '1', 'title' => 'Choisissez votre produit', 'text' => 'Parcourez le catalogue et sélectionnez votre produit.'],
                    ['n' => '2', 'title' => 'Configurez et uploadez', 'text' => 'Quantité, finition, puis déposez vos fichiers PDF, PNG ou JPG.'],
                    ['n' => '3', 'title' => 'Nous imprimons', 'text' => 'Contrôle qualité et impression sous 24 h après validation.'],
                    ['n' => '4', 'title' => 'Recevez votre commande', 'text' => 'Livraison suivie partout au Maroc, paiement à la réception.'],
                ] as $step)
                    <div class="relative text-center">
                        <span class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-brand-cyan font-extrabold text-white">{{ $step['n'] }}</span>
                        <h3 class="mt-4 font-bold text-slate-900">{{ $step['title'] }}</h3>
                        <p class="mx-auto mt-1.5 max-w-[16rem] text-sm leading-relaxed text-slate-500">{{ $step['text'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-7 py-3.5 font-bold text-white transition hover:bg-slate-700">
                    Commencer ma commande
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ================================================================ --}}
    {{-- INFORMATION SOCIÉTÉ --}}
    {{-- ================================================================ --}}
    <section class="mx-auto max-w-7xl px-4 py-16">
        <div class="grid items-center gap-10 rounded-3xl border border-slate-200 bg-white p-8 sm:p-12 lg:grid-cols-2">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 sm:text-3xl">ADO PRINT, imprimerie à Casablanca</h2>
                <p class="mt-4 leading-relaxed text-slate-600">
                    De la carte de visite au grand format, chaque commande passe entre les mains de notre
                    équipe production — avec la même exigence, que vous commandiez 100 ou 50 000 exemplaires.
                    Nos tarifs sont publics, dégressifs, et votre devis sur mesure est gratuit.
                </p>
                <div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-slate-600">
                    <span class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-sm bg-brand-cyan"></span><span class="h-2.5 w-2.5 rounded-sm bg-brand-magenta"></span><span class="h-2.5 w-2.5 rounded-sm bg-brand-yellow"></span><span class="h-2.5 w-2.5 rounded-sm bg-slate-900"></span>
                        Quadri CMJN en interne
                    </span>
                    <span class="font-semibold text-slate-900">SARL au capital de Casablanca</span>
                </div>
                <a href="{{ route('contact') }}" class="mt-6 inline-flex items-center gap-2 font-bold text-brand-cyan-dark hover:text-brand-cyan">
                    Nous contacter
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl bg-slate-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Adresse</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $siteSettings->get('company.address', 'Casablanca, Maroc') }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Horaires</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $siteSettings->get('company.hours', 'Lun–Ven : 9h–18h') }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Téléphone</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $siteSettings->get('company.phone', '') }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Email</p>
                    <p class="mt-2 break-all text-sm font-semibold text-slate-900">{{ $siteSettings->get('company.email', '') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ================================================================ --}}
    {{-- RESSOURCES & CONSEILS --}}
    {{-- ================================================================ --}}
    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900 sm:text-3xl">Ressources & conseils</h2>
                    <p class="mt-1 text-slate-500">Préparez vos fichiers et choisissez le bon support.</p>
                </div>
            </div>

            <div class="mt-8 grid gap-5 sm:grid-cols-3">
                @foreach ([
                    ['slug' => 'preparer-fichier', 'icon' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/>', 'title' => 'Préparer votre fichier d\'impression', 'text' => 'PDF 300 dpi, CMJN, fonds perdus 3 mm : le checklist complet.'],
                    ['slug' => 'choisir-papier', 'icon' => '<path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/>', 'title' => 'Bien choisir son papier', 'text' => 'Couché, offset, recyclé : grammages et usages recommandés.'],
                    ['slug' => 'cmjn-rvb', 'icon' => '<circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/>', 'title' => 'CMJN ou RVB ?', 'text' => 'Pourquoi vos couleurs changent à l\'impression et comment l\'éviter.'],
                ] as $guide)
                    <a href="{{ route('guides.show', $guide['slug']) }}" class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-brand-cyan hover:shadow-lg">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-cyan-light text-brand-cyan-dark">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $guide['icon'] !!}</svg>
                        </span>
                        <h3 class="mt-4 font-bold text-slate-900 transition group-hover:text-brand-cyan-dark">{{ $guide['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-slate-500">{{ $guide['text'] }}</p>
                        <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-bold text-brand-cyan-dark">
                            Lire le guide
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================================================================ --}}
    {{-- CTA FINAL --}}
    {{-- ================================================================ --}}
    <section class="mx-auto max-w-7xl px-4 py-16">
        <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center sm:p-14">
            <h2 class="text-2xl font-extrabold text-slate-900 sm:text-3xl">Vous ne trouvez pas votre produit ?</h2>
            <p class="mx-auto mt-3 max-w-xl text-slate-600">Nos équipes étudient gratuitement votre projet sur mesure et vous répondent sous 24 h ouvrées.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('devis') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-lg font-bold text-white transition hover:bg-brand-cyan-dark">
                    Envoyer une demande de devis
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center rounded-xl border border-slate-300 px-8 py-4 text-lg font-semibold text-slate-700 transition hover:border-brand-cyan hover:text-brand-cyan-dark">
                    Nous appeler
                </a>
            </div>
        </div>
    </section>
@endsection
