
                @foreach ($offers as $offer)
                    @php $p = $offer['product']; @endphp
                    <a href="{{ route('product.show', $p->slug) }}" class="group relative flex flex-col overflow-hidden rounded-2xl border border-white/10 bg-white shadow-lg transition duration-200 hover:-translate-y-1.5 hover:border-brand-magenta hover:shadow-2xl">
                        <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                            @if ($p->photoUrl())
                                <img src="{{ $p->photoUrl() }}" alt="{{ $p->name }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/25 to-transparent"></div>
                            @else
                                <span class="grid h-full place-items-center text-slate-300"><span class="h-14 w-14">@include('partials.category-icon', ['slug' => $p->category?->slug ?? 'default'])</span></span>
                            @endif
                            <span class="badge-discount absolute -right-2 top-3 grid h-16 w-16 place-items-center rounded-full bg-brand-magenta text-white ring-4 ring-white">
                                <span class="text-center leading-none">
                                    <span class="block text-xl font-extrabold">-{{ $offer['discount'] }}%</span>
                                    <span class="block text-[9px] font-bold uppercase tracking-wide">max</span>
                                </span>
                            </span>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <span class="text-[11px] font-bold uppercase tracking-wide text-brand-cyan-dark">{{ $p->category?->name ?? 'Produit' }}</span>
                            <h3 class="mt-1 font-bold text-slate-900 transition group-hover:text-brand-magenta">{{ $p->name }}</h3>
                            <p class="mt-1 flex-1 text-sm leading-relaxed text-slate-500">{{ \Illuminate\Support\Str::limit(strip_tags($p->description ?? ''), 70) }}</p>
                            <div class="mt-4 flex items-end justify-between gap-2">
                                <p class="text-sm text-slate-500">Dès <span class="text-lg font-extrabold text-brand-cyan-dark">{{ $money($offer['fromPrice']) }} dhs</span><br>{{ $offer['minQty'] }} ex.</p>
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
