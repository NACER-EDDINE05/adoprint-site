@php
    $min = $product->priceGrids->min('price') ?: $product->base_price;
    $minQty = (int) ($product->priceGrids->min('min_qty') ?: 100);
    $max = $product->priceGrids->max('price');
    $discount = ($max > 0 && $min < $max) ? (int) round((1 - $min / $max) * 100) : 0;
    $photo = $product->photoUrl();
    $name = $product->name;
    $category = $product->category;
@endphp

@if ($photo)
    @php $src = $photo; @endphp
@else
    @php $src = null; @endphp
@endif
<a href="{{ route('product.show', $product->slug) }}" class="ap24-card ap24-card--product">
    <div class="ap24-img">
        @if ($discount >= 25)
            <span class="ap24-tag ap24-tag--yellow">Jusqu'à -{{ $discount }}%</span>
        @elseif ($product->category)
            <span class="ap24-tag">{{ $product->category->name }}</span>
        @endif

        @if ($src)
            <img src="{{ $src }}" alt="{{ $name }}" loading="lazy">
        @else
            <div class="ap24-fallback">
                <span>@include('partials.category-icon', ['slug' => $category?->slug ?? 'default'])</span>
            </div>
        @endif

        <div class="ap24-img__overlay">
            @if ($src)
                <img src="{{ $src }}" alt="{{ $name }}" loading="lazy">
            @else
                <div class="ap24-fallback">
                    <span>@include('partials.category-icon', ['slug' => $category?->slug ?? 'default'])</span>
                </div>
            @endif
        </div>
    </div>

    <div class="ap24-body">
        <h3>{{ $name }}</h3>
        <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) $product->description), 72) }}</p>

        <div class="ap24-price-row">
            <span class="ap24-price">
                Dès {{ number_format((float) $min, 2, ',', ' ') }} dhs
                <small>{{ $minQty }} exemplaires</small>
            </span>
            <span class="ap24-arrow" aria-hidden="true">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </span>
        </div>
    </div>
</a>
