@extends('layouts.storefront')

@section('title', 'Commande confirmée — ADO PRINT')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-16">
        <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center">
            <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-brand-cyan-light text-brand-cyan-dark">
                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </span>

            <h1 class="mt-6 text-3xl font-extrabold text-slate-900">Merci {{ $order->meta['customer']['nom'] ?? '' }} !</h1>
            <p class="mt-3 text-slate-600">Votre commande <span class="font-extrabold text-brand-cyan-dark">{{ $number }}</span> a bien été enregistrée.</p>

            @if ($items)
                <div class="mt-8 rounded-2xl bg-slate-50 p-6 text-left">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500">Détail de la commande</h2>
                    <ul class="mt-4 space-y-3 text-sm">
                        @foreach ($items as $item)
                            <li class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $item['product'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $item['qty'] }} ex.</p>
                                </div>
                                <span class="font-bold text-slate-900">{{ number_format((float) $item['total'], 2, ',', ' ') }} dhs</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-4">
                        <span class="font-bold text-slate-900">Total</span>
                        <span class="text-xl font-extrabold text-brand-cyan-dark">{{ number_format((float) $order->total, 2, ',', ' ') }} dhs</span>
                    </div>
                </div>
            @endif

            <div class="mt-8 grid gap-4 text-left sm:grid-cols-3">
                <div class="rounded-xl border border-slate-200 p-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-brand-cyan-dark">Étape 1</p>
                    <p class="mt-1 text-sm text-slate-600">Notre équipe vérifie vos fichiers et vous appelle pour confirmer.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-brand-cyan-dark">Étape 2</p>
                    <p class="mt-1 text-sm text-slate-600">Impression et contrôle qualité sous 24 h après validation.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-brand-cyan-dark">Étape 3</p>
                    <p class="mt-1 text-sm text-slate-600">Livraison à l'adresse indiquée, paiement à la réception.</p>
                </div>
            </div>

            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a href="{{ route('products.index') }}" class="inline-flex rounded-xl bg-brand-cyan px-6 py-3 font-bold text-white transition hover:bg-brand-cyan-dark">Continuer mes achats</a>
                <a href="{{ route('contact') }}" class="inline-flex rounded-xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 transition hover:border-brand-cyan hover:text-brand-cyan-dark">Une question ?</a>
            </div>
        </div>
    </div>
@endsection
