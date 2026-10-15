@extends('layouts.storefront')

@section('title', 'Ressources & conseils impression — ADO PRINT')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-12">
        <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-cyan-dark">Accueil</a>
            <span>/</span>
            <span class="font-semibold text-slate-900">Ressources</span>
        </nav>

        <h1 class="text-3xl font-extrabold text-slate-900">Ressources & conseils</h1>
        <p class="mt-2 max-w-2xl text-slate-500">Nos guides pratiques pour préparer vos fichiers, choisir vos papiers et comprendre l'impression offset.</p>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($guides as $guide)
                <a href="{{ route('guides.show', $guide['slug']) }}" class="rounded-2xl border border-slate-200 bg-white p-6">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-cyan-light text-brand-cyan-dark">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $guide['icon'] !!}</svg>
                    </span>
                    <h2 class="mt-4 font-bold text-slate-900">{{ $guide['title'] }}</h2>
                    <p class="mt-1.5 text-sm leading-relaxed text-slate-500">{{ $guide['excerpt'] }}</p>
                    <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-bold text-brand-cyan-dark">
                        Lire le guide
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
@endsection
