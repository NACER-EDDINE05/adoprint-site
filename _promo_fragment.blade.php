    {{-- ================================================================ --}}
    {{-- BANDE PROMOTIONS — remises réelles issues des grilles tarifaires --}}
    {{-- ================================================================ --}}
    @if ($offers->isNotEmpty())
    @php $topDiscount = $offers->max('discount'); @endphp
    <section class="relative overflow-hidden bg-slate-900">
        {{-- halos décoratifs CMJN --}}
        <div class="brand-stripes absolute inset-x-0 top-0 h-1.5"></div>
        <div class="brand-stripes absolute inset-x-0 bottom-0 h-1.5"></div>
        <div class="pointer-events-none absolute -left-24 top-16 h-80 w-80 rounded-full bg-brand-magenta/25 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 bottom-10 h-80 w-80 rounded-full bg-brand-cyan/20 blur-3xl"></div>

        {{-- ---- BANDEAU DÉFILANT « moving band » : remises réelles ---- --}}
        <div class="marquee relative mt-1.5 bg-gradient-to-r from-brand-magenta-dark via-brand-magenta to-brand-yellow-dark py-2.5">
            <div class="marquee-track">
                @for ($__m = 0; $__m < 3; $__m++)
                    @foreach ($offers as $offer)
                        <span class="flex items-center gap-2 whitespace-nowrap px-6 text-base font-extrabold uppercase tracking-wide text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                            Jusqu'à -{{ $offer['discount'] }}% · {{ $offer['product']->name }}
                        </span>
                        <span class="text-white/60" aria-hidden="true">◆</span>
                    @endforeach
                @endfor
            </div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-12">
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
