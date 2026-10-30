@extends('layouts.storefront')

@section('title', 'Devis sur mesure — ADO PRINT')

@section('content')
    <div class="ap24-wrap">
        @include('partials.crumbs', ['items' => [['label' => 'Devis sur mesure']]])

        @include('partials.page-head', [
            'kicker' => 'Sur mesure',
            'title' => 'Demande de devis',
            'desc' => 'Décrivez votre projet : format, quantité, finition… Notre équipe vous répond sous 24 h ouvrées.',
            'iconSvg' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 15h6"/><path d="M9 11h2"/>',
            'meta' => ['Réponse sous 24 h ouvrées', 'Devis gratuit et sans engagement'],
        ])

        @if (session('success'))
            <div class="ap24-alert ap24-alert--ok mb-6">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('devis.send') }}" class="ap24-panel" style="max-width: 920px;">
            @csrf

            <div class="ap24-formgrid">
                <div class="ap24-field">
                    <label for="nom" class="ap24-label">Nom complet *</label>
                    <input id="nom" name="nom" type="text" required value="{{ old('nom') }}" class="ap24-input">
                    @error('nom')<p class="ap24-error">{{ $message }}</p>@enderror
                </div>

                <div class="ap24-field">
                    <label for="societe" class="ap24-label">Société</label>
                    <input id="societe" name="societe" type="text" value="{{ old('societe') }}" class="ap24-input">
                </div>

                <div class="ap24-field">
                    <label for="email" class="ap24-label">Email *</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}" class="ap24-input">
                    @error('email')<p class="ap24-error">{{ $message }}</p>@enderror
                </div>

                <div class="ap24-field">
                    <label for="telephone" class="ap24-label">Téléphone</label>
                    <input id="telephone" name="telephone" type="tel" value="{{ old('telephone') }}" class="ap24-input">
                </div>

                <div class="ap24-field">
                    <label for="product_id" class="ap24-label">Produit concerné</label>
                    <select id="product_id" name="product_id" class="ap24-input">
                        <option value="">— Autre / à préciser —</option>
                        @foreach ($products as $p)
                            <option value="{{ $p->id }}" {{ old('product_id', $productId) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="ap24-field">
                    <label for="quantite" class="ap24-label">Quantité estimée</label>
                    <input id="quantite" name="quantite" type="number" min="1" value="{{ old('quantite', request('quantite')) }}" class="ap24-input">
                </div>
            </div>

            <div class="ap24-field mt-4">
                <label for="message" class="ap24-label">Votre projet *</label>
                <textarea id="message" name="message" rows="5" required class="ap24-input"
                          placeholder="Ex. : 2 000 flyers A5 recto/verso, papier couché 135 g, livraison à Rabat…">{{ old('message') }}</textarea>
                @error('message')<p class="ap24-error">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="ap24-btn ap24-btn--yellow ap24-btn--block ap24-btn--flat mt-5">
                Envoyer ma demande de devis
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>

            <p class="mt-3 text-center text-xs text-slate-500">Devis gratuit · Aucun paiement en ligne</p>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- Parcours devis : demande sur mesure envoyée --}}
    <script type="module">
        const form = document.querySelector('form[action="{{ route('devis.send') }}"]');

        form?.addEventListener('submit', () => {
            if (! form.checkValidity()) return;

            const selected = form.querySelector('#product_id option:checked');

            window.adoAnalytics?.capture('quote_requested', {
                product: selected && selected.value !== '' ? selected.textContent.trim() : null,
                quantity: Number(form.querySelector('#quantite')?.value) || null,
                has_phone: Boolean(form.querySelector('#telephone')?.value),
                source_product_page: {{ request()->filled('produit') ? 'true' : 'false' }},
            });
        });
    </script>
@endpush
