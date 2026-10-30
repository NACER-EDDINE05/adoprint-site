@extends('layouts.storefront')

@section('title', 'À propos — ADO PRINT')

@section('content')
    <div class="ap24-wrap">
        @include('partials.crumbs', ['items' => [['label' => 'À propos']]])

        @include('partials.page-head', [
            'kicker' => 'Qui sommes-nous',
            'title' => "À propos d'ADO PRINT",
            'desc' => "ADO PRINT est une imprimerie en ligne basée à Casablanca. Nous accompagnons les professionnels comme les particuliers dans tous leurs supports de communication : cartes de visite, flyers, brochures, affiches, grand format et objets publicitaires.",
            'icon' => 'conception-graphique-design',
        ])

        {{-- Chiffres clés --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            @foreach ([['24 h', "Délai d'impression"], ['9', 'Familles de produits'], ['100%', 'Livré au Maroc'], ['300 dpi', "Qualité d'impression"]] as $stat)
                <div class="ap24-statbox">
                    <strong>{{ $stat[0] }}</strong>
                    <span>{{ $stat[1] }}</span>
                </div>
            @endforeach
        </div>

        {{-- Notre mission --}}
        <section class="ap24-block mt-12">
            <div class="ap24-subhead">
                <div><h2>Notre mission</h2></div>
            </div>
            <p class="ap24-prose">
                Rendre l'impression professionnelle simple, rapide et accessible. Vous configurez votre produit
                en ligne, vous voyez le prix immédiatement grâce à nos grilles dégressives, et nous nous occupons
                du reste : contrôle qualité, impression et livraison partout au Maroc.
            </p>
        </section>

        {{-- Nos valeurs --}}
        <section class="ap24-block mt-12">
            <div class="ap24-subhead">
                <div><h2>Nos valeurs</h2></div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ([
                    ['Qualité garantie', 'Un contrôle qualité est effectué sur chaque commande avant son expédition.'],
                    ['Prix transparents', 'Nos grilles tarifaires sont publiques et dégressives : plus la quantité augmente, plus le prix unitaire baisse.'],
                    ['Livraison rapide', 'Impression sous 24 h et expédition 24–48 h partout au Maroc.'],
                    ['Support dédié', "Une équipe à votre écoute par téléphone, email et WhatsApp aux heures d'ouverture."],
                ] as $value)
                    <div class="ap24-panel">
                        <h3 class="font-bold text-slate-900">{{ $value[0] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-slate-500">{{ $value[1] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Où nous trouver --}}
        <section class="ap24-block mt-12">
            <div class="ap24-subhead">
                <div><h2>Où nous trouver</h2></div>
            </div>

            <div class="ap24-panel ap24-panel--soft">
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach ([
                        ['Adresse', $siteSettings->get('company.address', 'Casablanca, Maroc')],
                        ['Horaires', $siteSettings->get('company.hours', 'Lun–Ven : 9h–18h')],
                        ['Téléphone', $siteSettings->get('company.phone', '')],
                        ['Email', $siteSettings->get('company.email', '')],
                    ] as $info)
                        <div class="rounded-xl bg-white p-5">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $info[0] }}</p>
                            <p class="mt-2 break-words text-sm font-semibold text-slate-900">{{ $info[1] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Appel à l'action --}}
        <div class="ap24-ctaband mb-12">
            <div>
                <h2>Découvrez notre catalogue</h2>
                <p>9 familles, plus de 130 sous-catégories, prix calculés en direct.</p>
            </div>
            <a href="{{ route('products.index') }}" class="ap24-btn ap24-btn--yellow ap24-btn--flat">Voir les produits</a>
        </div>
    </div>
@endsection
