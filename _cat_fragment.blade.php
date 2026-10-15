    {{-- ================================================================ --}}
    {{-- CATÉGORIES DE PRODUITS --}}
    {{-- ================================================================ --}}
    <section class="mx-auto max-w-7xl px-4 py-16">
        <div class="flex items-end justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-brand-cyan-light px-3 py-1 text-xs font-bold uppercase tracking-wide text-brand-cyan-dark">Catalogue</span>
                <h2 class="mt-3 text-2xl font-extrabold text-slate-900 sm:text-3xl">Nos catégories</h2>
                <p class="mt-1 text-slate-500">Tout ce qu'il faut pour votre communication visuelle.</p>
            </div>
            <a href="{{ route('products.index') }}" class="group hidden shrink-0 items-center gap-1.5 font-semibold text-brand-cyan-dark hover:text-brand-cyan sm:inline-flex">
                Tout voir
                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($univers as $u)
                @php $tilePhoto = $u['cheapest']?->photoUrl(); @endphp
                <a href="{{ route('category.show', $u['model']->slug) }}" class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:border-brand-cyan hover:shadow-xl">
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                        @if ($tilePhoto)
                            <img src="{{ $tilePhoto }}" alt="{{ $u['model']->name }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent"></div>
                        @else
                            <span class="grid h-full place-items-center bg-brand-cyan-light"><span class="h-12 w-12">@include('partials.category-icon', ['slug' => $u['model']->slug])</span></span>
                        @endif
                        <span class="absolute bottom-3 left-3 grid h-11 w-11 place-items-center rounded-xl bg-white/90 text-brand-cyan-dark shadow backdrop-blur-sm transition group-hover:bg-brand-cyan group-hover:text-white">
                            <span class="h-6 w-6">@include('partials.category-icon', ['slug' => $u['model']->slug])</span>
                        </span>
                        @if ($u['items']->isNotEmpty())
                            <span class="absolute right-3 top-3 rounded-full bg-slate-900/70 px-2.5 py-1 text-[11px] font-bold text-white backdrop-blur-sm">{{ $u['items']->count() }} produit{{ $u['items']->count() > 1 ? 's' : '' }}</span>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-5">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="font-bold text-slate-900 transition group-hover:text-brand-cyan-dark">{{ $u['model']->name }}</h3>
                            @if (!is_null($u['fromPrice']))
                                <span class="shrink-0 text-sm font-extrabold text-brand-cyan-dark">dès {{ $money($u['fromPrice']) }} dh</span>
                            @endif
                        </div>
                        <p class="mt-1.5 line-clamp-2 text-sm leading-relaxed text-slate-500">{{ $u['model']->description }}</p>
                        <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-bold text-brand-cyan-dark">
                            Voir les produits
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
