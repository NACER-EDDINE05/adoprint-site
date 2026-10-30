{{-- Options de personnalisation du produit --}}
@if ($product->options->isNotEmpty())
    <div class="ap24-panel mt-5">
        <h2>Personnalisation</h2>
        <p class="ap24-panel__hint">Indiquez vos choix dans la demande de devis, nous validons la faisabilité.</p>

        <dl class="mt-3 space-y-3 text-sm">
            @foreach ($product->options as $option)
                <div class="flex flex-wrap items-center gap-2">
                    <dt class="w-32 font-semibold text-slate-700">{{ $option->name }}</dt>
                    <dd class="flex flex-wrap gap-1.5">
                        @foreach ((array) $option->values as $value)
                            <span class="ap24-chip">{{ $value }}</span>
                        @endforeach
                    </dd>
                </div>
            @endforeach
        </dl>
    </div>
@endif
