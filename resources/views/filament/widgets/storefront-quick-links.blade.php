<x-filament::widget>
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="text-base font-semibold text-gray-900">Que voulez-vous faire&nbsp;?</h2>
            <p class="mt-0.5 text-sm text-gray-500">Les actions les plus utiles, en un clic.</p>
        </div>

        <div class="grid grid-cols-1 gap-3 p-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($links as $link)
                @php $isExternal = ! empty($link['external']); @endphp
                <a href="{{ $link['url'] }}"
                    @if ($isExternal) target="_blank" rel="noopener" @endif
                    class="group flex items-start gap-3 rounded-lg border border-gray-200 p-4 no-underline transition hover:border-primary-500 hover:bg-primary-50/60"
                >
                    @php
                        $accent = ! empty($link['accent']);
                    @endphp
                    <span class="mt-0.5 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg {{ $accent ? 'bg-primary-600 text-white group-hover:bg-primary-500' : 'bg-gray-100 text-gray-500 group-hover:bg-primary-100 group-hover:text-primary-600' }}">
                        @svg($link['icon'], 'h-5 w-5')
                    </span>

                    <span class="min-w-0">
                        <span class="block text-sm font-semibold text-gray-900 group-hover:text-primary-700">
                            {{ $link['title'] }}
                        </span>
                        <span class="mt-0.5 block truncate text-xs text-gray-500">
                            {{ $link['subtitle'] }}
                        </span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</x-filament::widget>
