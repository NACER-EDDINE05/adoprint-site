{{-- Fil d'Ariane : $items = [['label' => ..., 'url' => ...], ...] --}}
<nav class="ap24-crumbs" aria-label="Fil d'Ariane">
    <ol class="ap24-crumbs__list">
        <li><a href="{{ route('home') }}">Accueil</a></li>
        @foreach ($items as $item)
            <li class="ap24-crumbs__sep" aria-hidden="true">/</li>
            <li @if (empty($item['url'])) aria-current="page" @endif>
                @if (! empty($item['url']))
                    <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                @else
                    {{ $item['label'] }}
                @endif
            </li>
        @endforeach
    </ol>
</nav>
