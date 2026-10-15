@extends('layouts.storefront')

@section('title', 'Devis sur mesure — ADO PRINT')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-12">
        <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-cyan-dark">Accueil</a>
            <span>/</span>
            <span class="font-semibold text-slate-900">Devis sur mesure</span>
        </nav>

        <h1 class="text-3xl font-extrabold text-slate-900">Demande de devis sur mesure</h1>
        <p class="mt-2 text-slate-500">Décrivez votre projet : format, quantité, finition… Notre équipe vous répond sous 24 h ouvrées.</p>

        @if (session('success'))
            <div class="mt-8 rounded-2xl border border-green-200 bg-green-50 p-6 text-green-800">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('devis.send') }}" class="mt-8 space-y-5 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            @csrf
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="nom" class="mb-1 block text-sm font-semibold text-slate-700">Nom complet *</label>
                    <input id="nom" name="nom" type="text" required value="{{ old('nom') }}"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">
                    @error('nom')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="societe" class="mb-1 block text-sm font-semibold text-slate-700">Société</label>
                    <input id="societe" name="societe" type="text" value="{{ old('societe') }}"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">
                </div>
                <div>
                    <label for="email" class="mb-1 block text-sm font-semibold text-slate-700">Email *</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="telephone" class="mb-1 block text-sm font-semibold text-slate-700">Téléphone</label>
                    <input id="telephone" name="telephone" type="tel" value="{{ old('telephone') }}"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">
                </div>
                <div>
                    <label for="product_id" class="mb-1 block text-sm font-semibold text-slate-700">Produit concerné</label>
                    <select id="product_id" name="product_id"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 outline-none transition focus:border-brand-cyan">
                        <option value="">— Autre / à préciser —</option>
                        @foreach ($products as $p)
                            <option value="{{ $p->id }}" {{ old('product_id', $productId) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="quantite" class="mb-1 block text-sm font-semibold text-slate-700">Quantité estimée</label>
                    <input id="quantite" name="quantite" type="number" min="1" value="{{ old('quantite', request('quantite')) }}"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">
                </div>
            </div>
            <div>
                <label for="message" class="mb-1 block text-sm font-semibold text-slate-700">Votre projet *</label>
                <textarea id="message" name="message" rows="5" required
                          placeholder="Ex. : 2 000 flyers A5 recto/verso, papier couché 135 g, livraison à Rabat…"
                          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">{{ old('message') }}</textarea>
                @error('message')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="w-full rounded-xl bg-brand-cyan px-6 py-3.5 text-lg font-bold text-white transition hover:bg-brand-cyan-dark">
                Envoyer ma demande de devis
            </button>
        </form>
    </div>
@endsection
