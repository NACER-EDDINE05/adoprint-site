@extends('layouts.storefront')

@section('title', 'Aide — Comment commander ? | ADO PRINT')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-12">
        <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-cyan-dark">Accueil</a>
            <span>/</span>
            <span class="font-semibold text-slate-900">Aide</span>
        </nav>

        <h1 class="text-3xl font-extrabold text-slate-900">Comment commander ?</h1>
        <p class="mt-2 text-slate-500">Commander chez ADO PRINT se fait en quatre étapes simples.</p>

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['n' => '1', 'title' => 'Choisissez votre produit', 'text' => 'Parcourez nos catégories et sélectionnez le produit adapté à votre besoin.'],
                ['n' => '2', 'title' => 'Configurez le prix', 'text' => 'Indiquez votre quantité : le prix unitaire et le total se calculent instantanément.'],
                ['n' => '3', 'title' => 'Envoyez votre demande', 'text' => 'Ajoutez le produit à votre devis, précisez vos options et validez.'],
                ['n' => '4', 'title' => 'Recevez votre commande', 'text' => 'Après validation et impression, votre commande est livrée partout au Maroc.'],
            ] as $step)
                <div class="relative rounded-2xl border border-slate-200 bg-white p-6">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-brand-cyan font-extrabold text-white">{{ $step['n'] }}</span>
                    <h3 class="mt-4 font-bold text-slate-900">{{ $step['title'] }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $step['text'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- FAQ --}}
        <section class="mt-14">
            <h2 class="text-2xl font-extrabold text-slate-900">Questions fréquentes</h2>
            <div class="mt-6 space-y-3">
                @foreach ([
                    ['q' => 'Quels sont les délais de fabrication ?', 'a' => 'La plupart des produits sont imprimés et expédiés sous 24 à 48 heures ouvrées après validation de votre bon à tirer. Les grands formats peuvent nécessiter un délai supplémentaire.'],
                    ['q' => 'Livrez-vous partout au Maroc ?', 'a' => 'Oui, nous livrons dans toutes les villes du Maroc. Les frais et délais de livraison vous sont communiqués lors de l\'étab­lissement de votre devis.'],
                    ['q' => 'Puis-je obtenir un prix pour une grande quantité ?', 'a' => 'Absolument. Nos tarifs sont dégressifs : plus la quantité augmente, plus le prix unitaire baisse. Pour les très grandes séries, contactez-nous pour une offre personnalisée.'],
                    ['q' => 'Quels formats de fichiers acceptez-vous ?', 'a' => 'Nous acceptons les fichiers PDF haute définition (300 dpi, CMJN), avec fonds perdus de 3 mm. Notre équipe peut aussi vous accompagner pour la mise en page.'],
                    ['q' => 'Comment obtenir un devis sur mesure ?', 'a' => 'Utilisez notre formulaire de devis en ligne : décrivez votre projet (produit, format, quantité, finition) et recevez une réponse détaillée sous 24 heures ouvrées.'],
                ] as $faq)
                    <details class="group rounded-2xl border border-slate-200 bg-white">
                        <summary class="flex cursor-pointer items-center justify-between gap-4 p-5 font-semibold text-slate-900">
                            {{ $faq['q'] }}
                            <span class="text-brand-cyan transition group-open:rotate-45">＋</span>
                        </summary>
                        <p class="border-t border-slate-100 p-5 text-sm leading-relaxed text-slate-600">{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </section>

        {{-- CTA --}}
        <div class="mt-14 flex flex-col items-center justify-between gap-6 rounded-3xl bg-slate-900 p-10 text-center sm:flex-row sm:text-left">
            <div>
                <h2 class="text-2xl font-extrabold text-white">Prêt à imprimer ?</h2>
                <p class="mt-1 text-slate-400">Obtenez votre devis gratuit en quelques minutes.</p>
            </div>
            <a href="{{ route('devis') }}" class="shrink-0 rounded-xl bg-brand-cyan px-6 py-3 font-bold text-white transition hover:bg-brand-cyan-dark">Demander un devis</a>
        </div>
    </div>
@endsection
