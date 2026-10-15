@extends('layouts.storefront')

@section('title', 'À propos — ADO PRINT')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-12">
        {{-- Fil d'ariane --}}
        <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-cyan-dark">Accueil</a>
            <span>/</span>
            <span class="font-semibold text-slate-900">À propos</span>
        </nav>

        <h1 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">À propos d'ADO PRINT</h1>
        <p class="mt-4 text-lg leading-relaxed text-slate-600">
            ADO PRINT est une imprimerie en ligne basée à Casablanca. Nous accompagnons les professionnels
            comme les particuliers dans tous leurs supports de communication : cartes de visite, flyers,
            brochures, affiches, grand format et objets publicitaires.
        </p>

        {{-- Chiffres clés --}}
        <div class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-4">
            @foreach ([['24 h', 'Délai d\'impression'], ['9', 'Familles de produits'], ['100%', 'Livré au Maroc'], ['300 dpi', 'Qualité d\'impression']] as $stat)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 text-center">
                    <p class="text-2xl font-extrabold text-slate-900">{{ $stat[0] }}</p>
                    <p class="mt-1 text-xs font-semibold text-slate-500">{{ $stat[1] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Notre mission --}}
        <section class="mt-12">
            <h2 class="text-2xl font-extrabold text-slate-900">Notre mission</h2>
            <p class="mt-3 leading-relaxed text-slate-600">
                Rendre l'impression professionnelle simple, rapide et accessible. Vous configurez votre produit
                en ligne, vous voyez le prix immédiatement grâce à nos grilles dégressives, et nous nous occupons
                du reste : contrôle qualité, impression et livraison partout au Maroc.
            </p>
        </section>

        {{-- Nos valeurs --}}
        <section class="mt-12">
            <h2 class="text-2xl font-extrabold text-slate-900">Nos valeurs</h2>
            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                @foreach ([['Qualité garantie', 'Un contrôle qualité est effectué sur chaque commande avant son expédition.'], ['Prix transparents', 'Nos grilles tarifaires sont publiques et dégressives : plus la quantité augmente, plus le prix unitaire baisse.'], ['Livraison rapide', 'Impression sous 24 h et expédition 24–48 h partout au Maroc.'], ['Support dédié', 'Une équipe à votre écoute par téléphone, email et WhatsApp aux heures d\'ouverture.']] as $value)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6">
                        <h3 class="font-bold text-slate-900">{{ $value[0] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-slate-500">{{ $value[1] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Où nous trouver --}}
        <section class="mt-12 rounded-3xl border border-slate-200 bg-slate-50 p-8">
            <h2 class="text-2xl font-extrabold text-slate-900">Où nous trouver</h2>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl bg-white p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Adresse</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $siteSettings->get('company.address', 'Casablanca, Maroc') }}</p>
                </div>
                <div class="rounded-2xl bg-white p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Horaires</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $siteSettings->get('company.hours', 'Lun–Ven : 9h–18h') }}</p>
                </div>
                <div class="rounded-2xl bg-white p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Téléphone</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $siteSettings->get('company.phone', '') }}</p>
                </div>
                <div class="rounded-2xl bg-white p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Email</p>
                    <p class="mt-2 break-all text-sm font-semibold text-slate-900">{{ $siteSettings->get('company.email', '') }}</p>
                </div>
            </div>
        </section>

        {{-- Appel à l'action --}}
        <div class="mt-12 text-center">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-lg font-bold text-white">
                Découvrir nos produits
            </a>
        </div>
    </div>
@endsection