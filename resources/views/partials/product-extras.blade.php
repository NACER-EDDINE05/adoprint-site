{{-- Grille tarifaire, description, produits similaires --}}
@if ($grids->isNotEmpty())
    <section class="ap24-block">
        <div class="ap24-subhead">
            <div>
                <h2>Grille tarifaire</h2>
                <p>Le prix unitaire baisse avec la quantité commandée.</p>
            </div>
        </div>

        <div class="ap24-tablewrap">
            <table class="ap24-table">
                <thead>
                    <tr>
                        <th>Quantité</th>
                        <th>Prix unitaire HT</th>
                        <th>Total HT</th>
                        <th>Remise</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grids as $grid)
                        @php $maxPrice = (float) $grids->max('price'); @endphp
                        <tr>
                            <td><strong>{{ number_format((int) $grid->min_qty, 0, ',', ' ') }} – {{ $grid->max_qty ? number_format((int) $grid->max_qty, 0, ',', ' ') : '∞' }}</strong></td>
                            <td><strong>{{ number_format((float) $grid->price, 2, ',', ' ') }} DH</strong></td>
                            <td>{{ number_format((float) $grid->price * (int) $grid->min_qty, 2, ',', ' ') }} DH</td>
                            <td>
                                @if ($maxPrice > 0 && $grid->price < $maxPrice)
                                    −{{ round((1 - $grid->price / $maxPrice) * 100) }} %
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($product->description)
    <section class="ap24-block">
        <div class="ap24-subhead">
            <div>
                <h2>Description</h2>
            </div>
        </div>

        <div class="ap24-prose">{!! nl2br(e($product->description)) !!}</div>
    </section>
@endif

@if ($related->isNotEmpty())
    <section class="ap24-block">
        <div class="ap24-subhead">
            <div>
                <h2>Vous aimerez aussi</h2>
                <p>Des produits de la même famille.</p>
            </div>
        </div>

        <div class="ap24-grid ap24-grid--tight">
            @foreach ($related as $relatedProduct)
                @include('partials.product-card', ['product' => $relatedProduct])
            @endforeach
        </div>
    </section>
@endif
