{{-- En-tête de page : $kicker, $title, $desc, $meta[], $icon (slug) ou $iconSvg (tracés bruts) --}}
<header class="ap24-pagehead">
    @isset($iconSvg)
        <span class="ap24-pagehead__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $iconSvg !!}</svg>
        </span>
    @elseif (! empty($icon))
        <span class="ap24-pagehead__icon" aria-hidden="true">
            @include('partials.category-icon', ['slug' => $icon])
        </span>
    @endisset

    <div>
        <p class="ap24-kicker">{{ $kicker }}</p>
        <h1>{{ $title }}</h1>

        @isset($desc)
            <p class="ap24-pagehead__desc">{{ $desc }}</p>
        @endisset

        @if (! empty($meta))
            <p class="ap24-pagehead__meta">
                @foreach ($meta as $item)
                    <span>{{ $item }}</span>
                @endforeach
            </p>
        @endif
    </div>
</header>
