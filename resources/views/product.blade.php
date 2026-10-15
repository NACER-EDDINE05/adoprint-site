@extends('layouts.storefront')

@section('title', $product->name.' — ADO PRINT')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10">
        {{-- Fil d'ariane --}}
        <nav class="mb-6 flex flex-wrap items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-cyan-dark">Accueil</a>
            <span>/</span>
            @if ($product->category)
                <a href="{{ route('category.show', $product->category->slug) }}" class="hover:text-brand-cyan-dark">{{ $product->category->name }}</a>
                <span>/</span>
            @endif
            <span class="font-semibold text-slate-900">{{ $product->name }}</span>
        </nav>

        <div class="grid gap-10 lg:grid-cols-2">
            {{-- Visuel --}}
            <div class="relative grid aspect-[4/3] place-items-center overflow-hidden rounded-3xl border border-slate-200 bg-slate-100">
                @if ($product->photoUrl())
                    <img src="{{ $product->photoUrl() }}" alt="{{ $product->name }}" class="absolute inset-0 h-full w-full object-cover">
                @endif
                <span class="pointer-events-none absolute -bottom-6 -right-4 select-none text-[10rem] font-black text-slate-200 {{ $product->photoUrl() ? 'hidden' : '' }}">A</span>
                <span class="grid h-28 w-28 place-items-center rounded-3xl bg-white text-brand-cyan-dark shadow-sm ring-1 ring-slate-200 {{ $product->photoUrl() ? 'hidden' : '' }}">
                    <span class="h-14 w-14">@include('partials.category-icon', ['slug' => $product->category?->slug ?? 'default'])</span>
                </span>
            </div>

            {{-- Configurateur --}}
            <div>
                @if ($product->category)
                    <a href="{{ route('category.show', $product->category->slug) }}" class="inline-flex rounded-full bg-brand-cyan-light px-3 py-1 text-xs font-semibold text-brand-cyan-dark hover:bg-brand-cyan-light">{{ $product->category->name }}</a>
                @endif
                <h1 class="mt-3 text-3xl font-extrabold text-slate-900">{{ $product->name }}</h1>
                <p class="mt-3 text-slate-500">{{ \Illuminate\Support\Str::limit(strip_tags($product->description ?? ''), 160) }}</p>

                {{-- Configurateur : prix en direct + ajout au panier + fichiers --}}
                <form action="{{ route('cart.add') }}" method="POST" enctype="multipart/form-data" class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" data-calculator data-product-id="{{ $product->id }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="flex items-center justify-between gap-4">
                        <label for="qty" class="font-bold text-slate-900">Quantité</label>
                        <div class="flex items-center overflow-hidden rounded-xl border border-slate-200">
                            <button type="button" class="px-4 py-2 text-lg font-bold text-slate-500 transition hover:bg-slate-50" data-qty-step="-1">−</button>
                            <input id="qty" type="number" name="qty" min="1" max="1000000" value="{{ $grids->first()?->min_qty ?? 100 }}" class="w-24 border-x border-slate-200 py-2 text-center font-bold text-slate-900 outline-none">
                            <button type="button" class="px-4 py-2 text-lg font-bold text-slate-500 transition hover:bg-slate-50" data-qty-step="1">+</button>
                        </div>
                    </div>

                    <div class="mt-5 flex items-end justify-between rounded-xl bg-slate-50 p-4">
                        <div>
                            <p class="text-sm text-slate-500">Prix unitaire</p>
                            <p class="text-lg font-bold text-slate-900"><span id="unit-price">…</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-slate-500">Total estimé</p>
                            <p class="text-2xl font-extrabold text-brand-cyan-dark"><span id="total-price">…</span></p>
                        </div>
                    </div>

                    {{-- Fichiers d'impression --}}
                    <label for="product-files" class="mt-5 flex cursor-pointer items-center gap-3 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-4 transition hover:border-brand-cyan hover:bg-brand-cyan-light/40">
                        <svg class="h-5 w-5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m17 8-5-5-5 5"/><path d="M12 3v12"/></svg>
                        <span class="text-sm">
                            <span class="block font-semibold text-slate-700">Joindre mon fichier d'impression</span>
                            <span class="block text-xs text-slate-400">PDF, PNG, JPG, AI, EPS, ZIP · 10 Mo max — optionnel</span>
                        </span>
                    </label>
                    <input id="product-files" name="files[]" type="file" multiple accept=".pdf,.png,.jpg,.jpeg,.ai,.eps,.zip" class="sr-only">

                    <button type="submit" class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-brand-cyan px-6 py-3.5 text-lg font-bold text-white transition hover:bg-brand-cyan-dark">
                        Ajouter au panier
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    </button>

                    <a href="{{ route('devis', ['produit' => $product->slug]) }}" data-devis-link
                       class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 transition hover:border-brand-cyan hover:text-brand-cyan-dark">
                        Ou demander un devis pour ce produit
                    </a>
                    <p class="mt-3 text-center text-xs text-slate-400">Sans paiement en ligne · Fichiers vérifiés gratuitement · Livraison partout au Maroc</p>
                </form>

                @include('partials.product-options')
            </div>
        </div>

        @include('partials.product-extras')
    </div>

    @include('partials.product-calculator-script')
@endsection
