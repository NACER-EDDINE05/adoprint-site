@extends('layouts.storefront')

@section('title', $guide['title'].' — ADO PRINT')

@section('content')
    <article class="mx-auto max-w-3xl px-4 py-12">
        <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-cyan-dark">Accueil</a>
            <span>/</span>
            <a href="{{ route('guides.index') }}" class="hover:text-brand-cyan-dark">Ressources</a>
            <span>/</span>
            <span class="font-semibold text-slate-900">{{ $guide['title'] }}</span>
        </nav>

        <span class="grid h-12 w-12 place-items-center rounded-xl bg-brand-cyan-light text-brand-cyan-dark">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $guide['icon'] !!}</svg>
        </span>

        <h1 class="mt-5 text-3xl font-extrabold text-slate-900 sm:text-4xl">{{ $guide['title'] }}</h1>
        <p class="mt-4 text-lg leading-relaxed text-slate-600">{{ $guide['intro'] }}</p>

        <div class="mt-10 space-y-8">
            @foreach ($guide['sections'] as $section)
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h2 class="font-bold text-slate-900">{{ $section[0] }}</h2>
                    <p class="mt-2 leading-relaxed text-slate-600">{{ $section[1] }}</p>
                </section>
            @endforeach
        </div>

        <div class="mt-12 rounded-2xl bg-slate-50 p-8 text-center">
            <p class="font-bold text-slate-900">Un doute sur votre fichier ?</p>
            <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">Envoyez-le nous avec votre demande : nous vérifions gratuitement la faisabilité avant impression.</p>
            <a href="{{ route('devis') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-brand-cyan px-6 py-3 font-bold text-white transition hover:bg-brand-cyan-dark">
                Vérifier mon fichier gratuitement
            </a>
        </div>
    </article>
@endsection
