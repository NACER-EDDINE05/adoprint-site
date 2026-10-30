@extends('layouts.storefront')

@section('title', 'Aide — Comment commander ? | ADO PRINT')

@section('content')
    <div class="ap24-wrap">
        @include('partials.crumbs', ['items' => [['label' => 'Aide']]])

        @include('partials.page-head', [
            'kicker' => 'Mode d\'emploi',
            'title' => 'Comment commander ?',
            'desc' => 'Commander chez ADO PRINT se fait en quatre étapes simples.',
            'iconSvg' => '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/>',
        ])

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['n' => '1', 'title' => 'Choisissez votre produit', 'text' => 'Parcourez nos catégories et sélectionnez le produit adapté à votre besoin.'],
                ['n' => '2', 'title' => 'Configurez le prix', 'text' => 'Indiquez votre quantité : le prix unitaire et le total se calculent instantanément.'],
                ['n' => '3', 'title' => 'Envoyez votre demande', 'text' => 'Ajoutez le produit à votre devis, précisez vos options et validez.'],
                ['n' => '4', 'title' => 'Recevez votre commande', 'text' => 'Après validation et impression, votre commande est livrée partout au Maroc.'],
            ] as $step)
                <div class="ap24-panel">
                    <span class="ap24-stepnum">{{ $step['n'] }}</span>
                    <h3 class="mt-4 font-bold text-slate-900">{{ $step['title'] }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $step['text'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- FAQ --}}
        <section class="ap24-block mt-12">
            <div class="ap24-subhead">
                <div>
                    <h2>Questions fréquentes</h2>
                    <p>Tout ce qu'il faut savoir avant de commander.</p>
                </div>
            </div>

            <div class="space-y-3">
                @foreach ([
                    ['q' => 'Quels sont les délais de fabrication ?', 'a' => 'La plupart des produits sont imprimés et expédiés sous 24 à 48 heures ouvrées après validation de votre bon à tirer. Les grands formats peuvent nécessiter un délai supplémentaire.'],
                    ['q' => 'Livrez-vous partout au Maroc ?', 'a' => "Oui, nous livrons dans toutes les villes du Maroc. Les frais et délais de livraison vous sont communiqués lors de l'établissement de votre devis."],
                    ['q' => 'Puis-je obtenir un prix pour une grande quantité ?', 'a' => 'Absolument. Nos tarifs sont dégressifs : plus la quantité augmente, plus le prix unitaire baisse. Pour les très grandes séries, contactez-nous pour une offre personnalisée.'],
                    ['q' => 'Quels formats de fichiers acceptez-vous ?', 'a' => 'Nous acceptons les fichiers PDF haute définition (300 dpi, CMJN), avec fonds perdus de 3 mm. Notre équipe peut aussi vous accompagner pour la mise en page.'],
                    ['q' => 'Comment obtenir un devis sur mesure ?', 'a' => 'Utilisez notre formulaire de devis en ligne : décrivez votre projet (produit, format, quantité, finition) et recevez une réponse détaillée sous 24 heures ouvrées.'],
                ] as $faq)
                    <details class="ap24-faq">
                        <summary>{{ $faq['q'] }}</summary>
                        <p>{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </section>

        {{-- CTA --}}
        <div class="ap24-ctaband mb-12">
            <div>
                <h2>Prêt à imprimer ?</h2>
                <p>Obtenez votre devis gratuit en quelques minutes.</p>
            </div>
            <a href="{{ route('devis') }}" class="ap24-btn ap24-btn--yellow ap24-btn--flat">Demander un devis</a>
        </div>
    </div>
@endsection
