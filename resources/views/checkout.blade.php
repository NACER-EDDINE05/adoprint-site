@extends('layouts.storefront')

@section('title', 'Finaliser ma commande — ADO PRINT')

@section('content')
    @php
        $money = fn ($v): string => number_format((float) $v, 2, ',', ' ');
    @endphp

    <div class="ap24-wrap">
        @include('partials.crumbs', ['items' => [
            ['label' => 'Panier', 'url' => route('cart')],
            ['label' => 'Commande'],
        ]])

        @include('partials.page-head', [
            'kicker' => 'Dernière étape',
            'title' => 'Finaliser ma commande',
            'desc' => 'Vos coordonnées, votre adresse de livraison et vos fichiers d\'impression. Sans paiement en ligne : nous confirmons par téléphone.',
            'iconSvg' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 15h6"/><path d="M9 11h2"/>',
            'meta' => [count($items).' article'.(count($items) > 1 ? 's' : ''), $money($total).' DH HT'],
        ])

        <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data" class="ap24-cart">
            @csrf

            <div class="space-y-5">
                {{-- Coordonnées --}}
                <section class="ap24-panel">
                    <h2>1 · Vos coordonnées</h2>
                    <p class="ap24-panel__hint">Nous vous appelons pour confirmer avant impression.</p>

                    <div class="ap24-formgrid">
                        <div class="ap24-field">
                            <label for="nom" class="ap24-label">Nom complet *</label>
                            <input id="nom" name="nom" type="text" required value="{{ old('nom', $user?->name) }}" class="ap24-input">
                            @error('nom')<p class="ap24-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="ap24-field">
                            <label for="societe" class="ap24-label">Société</label>
                            <input id="societe" name="societe" type="text" value="{{ old('societe') }}" class="ap24-input">
                        </div>
                        <div class="ap24-field">
                            <label for="email" class="ap24-label">Email *</label>
                            <input id="email" name="email" type="email" required value="{{ old('email', $user?->email) }}" class="ap24-input">
                            @error('email')<p class="ap24-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="ap24-field">
                            <label for="telephone" class="ap24-label">Téléphone *</label>
                            <input id="telephone" name="telephone" type="tel" required value="{{ old('telephone') }}" class="ap24-input">
                            @error('telephone')<p class="ap24-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                {{-- Livraison --}}
                <section class="ap24-panel">
                    <h2>2 · Adresse de livraison</h2>

                    <div class="ap24-formgrid mt-4">
                        <div class="ap24-field" style="grid-column: 1 / -1;">
                            <label for="adresse" class="ap24-label">Adresse *</label>
                            <input id="adresse" name="adresse" type="text" required value="{{ old('adresse') }}" class="ap24-input">
                            @error('adresse')<p class="ap24-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="ap24-field">
                            <label for="ville" class="ap24-label">Ville *</label>
                            <input id="ville" name="ville" type="text" required value="{{ old('ville') }}" class="ap24-input">
                            @error('ville')<p class="ap24-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="ap24-field">
                            <label for="code_postal" class="ap24-label">Code postal</label>
                            <input id="code_postal" name="code_postal" type="text" value="{{ old('code_postal') }}" class="ap24-input">
                        </div>
                    </div>
                </section>

                {{-- Fichiers --}}
                <section class="ap24-panel">
                    <h2>3 · Vos fichiers d'impression</h2>
                    <p class="ap24-panel__hint">PDF, PNG, JPG, AI, EPS ou ZIP — 10 Mo maximum par fichier. Vous pouvez aussi les envoyer plus tard par email.</p>

                    <label for="files" class="ap24-file mt-4">
                        <span class="ap24-file__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m17 8-5-5-5 5"/><path d="M12 3v12"/></svg>
                        </span>
                        <span>
                            <strong>Déposer mes fichiers</strong>
                            <small>ou cliquer pour parcourir</small>
                        </span>
                    </label>
                    <input id="files" name="files[]" type="file" multiple accept=".pdf,.png,.jpg,.jpeg,.ai,.eps,.zip" class="sr-only">

                    @error('files.*')<p class="ap24-error mt-2">{{ $message }}</p>@enderror

                    <div class="ap24-field mt-4">
                        <label for="notes" class="ap24-label">Instructions particulières</label>
                        <textarea id="notes" name="notes" rows="3" class="ap24-input" placeholder="Finition souhaitée, découpe, délai…">{{ old('notes') }}</textarea>
                    </div>
                </section>
            </div>

            {{-- Récapitulatif --}}
            <aside class="ap24-panel ap24-panel--soft">
                <h2>Votre commande</h2>

                <dl class="mt-2">
                    @foreach ($items as $item)
                        <div class="ap24-line">
                            <dt>
                                {{ $item['product']->name }}
                                <span class="block text-xs text-slate-500">
                                    {{ number_format((int) $item['qty'], 0, ',', ' ') }} ex.@if ($item['options']) · {{ collect($item['options'])->implode(' · ') }}@endif
                                </span>
                            </dt>
                            <dd>{{ $money($item['total']) }} DH</dd>
                        </div>
                    @endforeach
                    <div class="ap24-line ap24-line--total">
                        <dt>Total HT</dt>
                        <dd>{{ $money($total) }} DH</dd>
                    </div>
                </dl>

                <button type="submit" class="ap24-btn ap24-btn--yellow ap24-btn--block ap24-btn--flat mt-4">
                    Confirmer la commande
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                </button>

                <p class="mt-3 text-center text-xs text-slate-500">Sans paiement en ligne · Notre équipe confirme votre commande par téléphone.</p>
            </aside>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- Parcours commande : formulaire de livraison atteint --}}
    <script type="module">
        window.adoAnalytics?.capture('begin_checkout', {
            items: {{ count($items) }},
            quantity: {{ (int) collect($items)->sum('qty') }},
            total: {{ (float) $total }},
            products: @json(collect($items)->pluck('product.name')->values()),
        });
    </script>
@endpush
