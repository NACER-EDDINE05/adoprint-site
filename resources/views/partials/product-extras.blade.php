{{-- Grille tarifaire, description, produits similaires --}}
@if ($grids->isNotEmpty())
    <section class="mt-12">
        <h2 class="text-xl font-extrabold text-slate-900">Grille tarifaire</h2>
        <p class="mt-1 text-sm text-slate-500">Prix unitaire dégressif selon la quantité commandée.</p>
        <div class="mt-4 overflow-x-auto rounded-2xl border border-slate-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3">Quantité</th>
                        <th class="px-6 py-3">Prix unitaire</th>
                        <th class="px-6 py-3">Remise</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($grids as $grid)
                        @php $maxPrice = $grids->max('price'); @endphp
                        <tr class="hover:bg-brand-cyan-light/50">
                            <td class="px-6 py-3 font-semibold text-slate-900">{{ $grid->min_qty }} – {{ $grid->max_qty ?: '∞' }}</td>
                            <td class="px-6 py-3 font-bold text-brand-cyan-dark">{{ number_format((float) $grid->price, 2, ',', ' ') }} DH</td>
                            <td class="px-6 py-3 text-slate-500">@if ($maxPrice > 0 && $grid->price < $maxPrice) −{{ round((1 - $grid->price / $maxPrice) * 100) }} % @else — @endif</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($product->description)
    <section class="mt-12 max-w-3xl">
        <h2 class="text-xl font-extrabold text-slate-900">Description</h2>
        <div class="mt-3 space-y-3 leading-relaxed text-slate-600">{!! nl2br(e($product->description)) !!}</div>
    </section>
@endif

@if ($related->isNotEmpty())
    <section class="mt-14">
        <h2 class="text-xl font-extrabold text-slate-900">Vous aimerez aussi</h2>
        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($related as $relatedProduct)
                @include('partials.product-card', ['product' => $relatedProduct])
            @endforeach
        </div>
    </section>
@endif
