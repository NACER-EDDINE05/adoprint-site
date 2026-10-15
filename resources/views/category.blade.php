@extends('layouts.storefront')

@section('title', $category->name.' — ADO PRINT')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10">
        {{-- Fil d'ariane --}}
        <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-cyan-dark">Accueil</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-brand-cyan-dark">Produits</a>
            <span>/</span>
            <span class="font-semibold text-slate-900">{{ $category->name }}</span>
        </nav>

        {{-- En-tête catégorie --}}
        <div class="flex items-start gap-5">
            <span class="hidden h-16 w-16 shrink-0 place-items-center rounded-2xl bg-brand-cyan-light p-3 text-brand-cyan-dark sm:grid">
                @include('partials.category-icon', ['slug' => $category->slug])
            </span>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">{{ $category->name }}</h1>
                <p class="mt-2 max-w-3xl text-slate-500">
                    {{ $category->description ?: 'Découvrez notre gamme '.$category->name.' : qualité professionnelle, prix dégressifs selon la quantité et livraison rapide partout au Maroc.' }}
                </p>
            </div>
        </div>

        @if ($products->isEmpty())
            <div class="mt-12 rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="text-lg font-semibold text-slate-900">Les produits de cette catégorie arrivent bientôt.</p>
                <p class="mt-2 text-slate-500">Besoin d'un produit dans cette gamme ? Demandez-nous un devis sur mesure.</p>
                <a href="{{ route('devis') }}" class="mt-6 inline-flex rounded-xl bg-brand-cyan px-6 py-3 font-bold text-white transition hover:bg-brand-cyan-dark">Demander un devis</a>
            </div>
        @else
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @endif
    </div>
@endsection
