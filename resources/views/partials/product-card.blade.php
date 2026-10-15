@php
    $min = $product->priceGrids->min('price') ?: $product->base_price;
    $minQty = $product->priceGrids->min('min_qty') ?: 100;
    $max = $product->priceGrids->max('price');
    $discount = ($max > 0 && $min < $max) ? (int) round((1 - $min / $max) * 100) : 0;
@endphp
<a href="{{ route('product.show', $product->slug) }}" class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white">
    {{-- Photo réelle du produit, sinon visuel de substitution (icône catégorie) --}}
    <div class="relative grid aspect-[4/3] place-items-center overflow-hidden bg-slate-100">
        @if ($product->photoUrl())
            <img src="{{ $product->photoUrl() }}" alt="{{ $product->name }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover">
        @else
        <span class="pointer-events-none absolute inset-0 text-slate-200">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="h-full w-full" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
        </span>
        @endif
        @if ($discount >= 25)
            <span class="absolute left-3 top-3 rounded-full bg-brand-yellow px-2.5 py-1 text-[11px] font-extrabold uppercase tracking-wide text-slate-900">Jusqu'à -{{ $discount }}%</span>
        @elseif ($product->category)
            <span class="absolute left-3 top-3 rounded-full bg-white/10 px-3 py-1 text-[11px] font-semibold text-white backdrop-blur">{{ $product->category->name }}</span>
        @endif
        <span class="relative grid h-16 w-16 place-items-center rounded-2xl bg-white text-brand-cyan-dark shadow-sm ring-1 ring-slate-200 {{ $product->photoUrl() ? 'hidden' : '' }}">
            @include('partials.category-icon', ['slug' => $product->category?->slug ?? 'default'])
        </span>
    </div>

    <div class="flex flex-1 flex-col p-5">
        <h3 class="font-bold text-slate-900">{{ $product->name }}</h3>
        <p class="mt-2 text-sm text-slate-500">Dès <span class="text-lg font-extrabold text-brand-cyan-dark">{{ number_format((float) $min, 2, ',', ' ') }} dhs</span> · {{ $minQty }} ex.</p>
        <span class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">
            Configurer le prix
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </span>
    </div>
</a>
