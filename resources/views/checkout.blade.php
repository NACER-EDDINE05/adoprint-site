@extends('layouts.storefront')

@section('title', 'Finaliser ma commande — ADO PRINT')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-12">
        <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-cyan-dark">Accueil</a>
            <span>/</span>
            <a href="{{ route('cart') }}" class="hover:text-brand-cyan-dark">Panier</a>
            <span>/</span>
            <span class="font-semibold text-slate-900">Commande</span>
        </nav>

        <h1 class="text-3xl font-extrabold text-slate-900">Finaliser ma commande</h1>
        <p class="mt-2 text-slate-500">Vos coordonnées, votre adresse de livraison et vos fichiers d'impression.</p>

        <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data" class="mt-8 grid gap-8 lg:grid-cols-[1.6fr_1fr]">
            @csrf

            <div class="space-y-6">
                {{-- Coordonnées --}}
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h2 class="font-bold text-slate-900">1 · Vos coordonnées</h2>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="nom" class="mb-1 block text-sm font-semibold text-slate-700">Nom complet *</label>
                            <input id="nom" name="nom" type="text" required value="{{ old('nom', $user?->name) }}"
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
                            <input id="email" name="email" type="email" required value="{{ old('email', $user?->email) }}"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">
                            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="telephone" class="mb-1 block text-sm font-semibold text-slate-700">Téléphone *</label>
                            <input id="telephone" name="telephone" type="tel" required value="{{ old('telephone') }}"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">
                            @error('telephone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                {{-- Livraison --}}
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h2 class="font-bold text-slate-900">2 · Adresse de livraison</h2>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="adresse" class="mb-1 block text-sm font-semibold text-slate-700">Adresse *</label>
                            <input id="adresse" name="adresse" type="text" required value="{{ old('adresse') }}"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">
                            @error('adresse')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="ville" class="mb-1 block text-sm font-semibold text-slate-700">Ville *</label>
                            <input id="ville" name="ville" type="text" required value="{{ old('ville') }}"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">
                            @error('ville')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="code_postal" class="mb-1 block text-sm font-semibold text-slate-700">Code postal</label>
                            <input id="code_postal" name="code_postal" type="text" value="{{ old('code_postal') }}"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">
                        </div>
                    </div>
                </section>

                {{-- Fichiers --}}
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h2 class="font-bold text-slate-900">3 · Vos fichiers d'impression</h2>
                    <p class="mt-1 text-sm text-slate-500">PDF, PNG, JPG, AI, EPS ou ZIP — 10 Mo maximum par fichier. Vous pouvez aussi les envoyer plus tard par email.</p>

                    <label for="files" class="mt-4 flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center transition hover:border-brand-cyan hover:bg-brand-cyan-light/40">
                        <svg class="h-8 w-8 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m17 8-5-5-5 5"/><path d="M12 3v12"/></svg>
                        <span class="text-sm font-semibold text-slate-700">Déposez vos fichiers ici</span>
                        <span class="text-xs text-slate-400">ou cliquez pour parcourir</span>
                    </label>
                    <input id="files" name="files[]" type="file" multiple accept=".pdf,.png,.jpg,.jpeg,.ai,.eps,.zip" class="sr-only">

                    @error('files.*')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror

                    <div class="mt-4">
                        <label for="notes" class="mb-1 block text-sm font-semibold text-slate-700">Instructions particulières</label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan"
                                  placeholder="Finition souhaitée, découpe, délai…">{{ old('notes') }}</textarea>
                    </div>
                </section>
            </div>

            {{-- Récapitulatif --}}
            <aside class="h-fit rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <h2 class="font-bold text-slate-900">Votre commande</h2>

                <ul class="mt-4 space-y-3 text-sm">
                    @foreach ($items as $item)
                        <li class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $item['product']->name }}</p>
                                <p class="text-xs text-slate-500">{{ $item['qty'] }} ex. @if($item['options']) · {{ collect($item['options'])->implode(': ') }} @endif</p>
                            </div>
                            <span class="font-bold text-slate-900">{{ number_format($item['total'], 2, ',', ' ') }} dhs</span>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-4">
                    <span class="font-bold text-slate-900">Total</span>
                    <span class="text-xl font-extrabold text-brand-cyan-dark">{{ number_format($total, 2, ',', ' ') }} dhs</span>
                </div>

                <button type="submit" class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-brand-cyan px-6 py-3.5 font-bold text-white transition hover:bg-brand-cyan-dark">
                    Confirmer la commande
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                </button>
                <p class="mt-3 text-center text-xs text-slate-500">Sans paiement en ligne · Notre équipe confirme votre commande par téléphone.</p>
            </aside>
        </form>
    </div>
@endsection
