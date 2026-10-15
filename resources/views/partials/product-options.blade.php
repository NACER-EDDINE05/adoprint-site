{{-- Options de personnalisation du produit --}}
@if ($product->options->isNotEmpty())
    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6">
        <h2 class="font-bold text-slate-900">Personnalisation</h2>
        <dl class="mt-4 space-y-3 text-sm">
            @foreach ($product->options as $option)
                <div class="flex flex-wrap items-center gap-2">
                    <dt class="w-28 font-semibold text-slate-700">{{ $option->name }}</dt>
                    <dd class="flex flex-wrap gap-1.5">
                        @foreach ((array) $option->values as $value)
                            <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-medium text-slate-600">{{ $value }}</span>
                        @endforeach
                    </dd>
                </div>
            @endforeach
        </dl>
        <p class="mt-3 text-xs text-slate-400">Précisez vos choix dans la demande de devis.</p>
    </div>
@endif
