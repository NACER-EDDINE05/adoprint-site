@extends('layouts.storefront')

@section('title', 'Tous nos produits — ADO PRINT')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10">
        {{-- Fil d'ariane --}}
        <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-cyan-dark">Accueil</a>
            <span>/</span>
            <span class="font-semibold text-slate-900">Tous les produits</span>
        </nav>

        <h1 class="text-3xl font-extrabold text-slate-900">Tous nos produits</h1>
        <p class="mt-2 text-slate-500">Configurez votre produit en ligne et obtenez votre prix instantanément.</p>

        {{-- Filtres par catégorie --}}
        <div class="mt-6 flex flex-wrap gap-2">
            <a href="{{ route('products.index') }}" class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request('categorie') ? 'border border-slate-200 bg-white text-slate-600 hover:border-brand-cyan' : 'bg-brand-cyan text-white' }}">Tous</a>
            @foreach ($categories as $c)
                @if (request('categorie') === $c->slug)
                    <span class="rounded-full bg-brand-cyan px-4 py-2 text-sm font-semibold text-white">{{ $c->name }}</span>
                @else
                    <a href="{{ route('category.show', $c->slug) }}" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-brand-cyan">{{ $c->name }}</a>
                @endif
            @endforeach
        </div>

        @if ($products->isEmpty())
            <div class="mt-12 rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="text-lg font-semibold text-slate-900">Aucun produit {{ request('q') ? 'ne correspond à « '.request('q').' »' : 'pour le moment' }}.</p>
                <p class="mt-2 text-slate-500">Contactez-nous, nous pouvons le réaliser sur mesure.</p>
                <a href="{{ route('devis') }}" class="mt-6 inline-flex rounded-xl bg-brand-cyan px-6 py-3 font-bold text-white transition hover:bg-brand-cyan-dark">Demander un devis</a>
            </div>
        @else
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @endif
    </div>
@endsection
