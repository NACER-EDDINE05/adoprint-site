@extends('layouts.storefront')

@section('title', 'Votre panier — ADO PRINT')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-12">
        <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-cyan-dark">Accueil</a>
            <span>/</span>
            <span class="font-semibold text-slate-900">Panier</span>
        </nav>

        <h1 class="text-3xl font-extrabold text-slate-900">Votre panier</h1>

        @if (session('success'))
            <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
        @endif

        @if (count($items) === 0)
            <div class="mt-10 rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="text-lg font-semibold text-slate-900">Votre panier est vide.</p>
                <p class="mt-2 text-slate-500">Parcourez le catalogue et configurez votre premier produit.</p>
                <a href="{{ route('products.index') }}" class="mt-6 inline-flex rounded-xl bg-brand-cyan px-6 py-3 font-bold text-white transition hover:bg-brand-cyan-dark">Découvrir nos produits</a>
            </div>
        @else
            <div class="mt-8 grid gap-8 lg:grid-cols-[1.6fr_1fr]">
                {{-- Lignes du panier --}}
                <div class="space-y-4">
                    @foreach ($items as $item)
                        <div class="rounded-2xl border border-slate-200 bg-white p-5">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <span class="grid h-14 w-14 shrink-0 place-items-center rounded-xl bg-brand-cyan-light text-brand-cyan-dark">
                                        <span class="h-7 w-7">@include('partials.category-icon', ['slug' => $item['product']->category?->slug ?? 'default'])</span>
                                    </span>
                                    <div>
                                        <a href="{{ route('product.show', $item['product']->slug) }}" class="font-bold text-slate-900 hover:text-brand-cyan-dark">{{ $item['product']->name }}</a>
                                        @if ($item['options'])
                                            <p class="mt-0.5 text-xs text-slate-500">{{ collect($item['options'])->map(fn ($v, $k) => $k.' : '.$v)->implode(' · ') }}</p>
                                        @endif
                                        <p class="mt-1 text-sm text-slate-500">{{ number_format($item['unit'], 2, ',', ' ') }} dhs / ex.</p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <p class="text-lg font-extrabold text-brand-cyan-dark">{{ number_format($item['total'], 2, ',', ' ') }} dhs</p>
                                </div>
                            </div>

                            <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="row_id" value="{{ $item['row_id'] }}">
                                    <label class="text-sm text-slate-500" for="qty-{{ $item['row_id'] }}">Quantité</label>
                                    <input id="qty-{{ $item['row_id'] }}" type="number" name="qty" value="{{ $item['qty'] }}" min="1" max="1000000"
                                           class="w-24 rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-bold text-slate-900 outline-none transition focus:border-brand-cyan">
                                    <button type="submit" class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-600 transition hover:border-brand-cyan hover:text-brand-cyan-dark">Mettre à jour</button>
                                </form>

                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="row_id" value="{{ $item['row_id'] }}">
                                    <button type="submit" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-400 transition hover:text-red-600">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        Retirer
                                    </button>
                                </form>
                            </div>

                            @if (session("cart_files.{$item['row_id']}"))
                                <p class="mt-3 flex items-center gap-2 text-xs font-semibold text-green-700">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    {{ count(session("cart_files.{$item['row_id']}")) }} fichier(s) joint(s) à cette ligne
                                </p>
                            @endif
                        </div>
                    @endforeach

                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-cyan-dark hover:text-brand-cyan">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        Continuer mes achats
                    </a>
                </div>

                {{-- Récapitulatif --}}
                <aside class="h-fit rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <h2 class="font-bold text-slate-900">Récapitulatif</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Articles</dt>
                            <dd class="font-bold text-slate-900">{{ count($items) }}</dd>
                        </div>
                        <div class="flex justify-between border-t border-slate-200 pt-3">
                            <dt class="font-bold text-slate-900">Total estimé</dt>
                            <dd class="text-xl font-extrabold text-brand-cyan-dark">{{ number_format($total, 2, ',', ' ') }} dhs</dd>
                        </div>
                    </dl>
                    <a href="{{ route('checkout.show') }}" class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-brand-cyan px-6 py-3.5 font-bold text-white transition hover:bg-brand-cyan-dark">
                        Passer la commande
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                    <p class="mt-3 text-center text-xs text-slate-500">Paiement à la livraison · Devis confirmé avant impression</p>
                </aside>
            </div>
        @endif
    </div>
@endsection
