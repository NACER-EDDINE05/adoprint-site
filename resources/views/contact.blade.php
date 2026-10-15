@extends('layouts.storefront')

@section('title', 'Contact — ADO PRINT')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-12">
        <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-cyan-dark">Accueil</a>
            <span>/</span>
            <span class="font-semibold text-slate-900">Contact</span>
        </nav>

        <h1 class="text-3xl font-extrabold text-slate-900">Contactez-nous</h1>
        <p class="mt-2 text-slate-500">Une question, un projet ? Notre équipe est à votre écoute.</p>

        <div class="mt-10 grid gap-8 lg:grid-cols-5">
            {{-- Coordonnées --}}
            <div class="space-y-4 lg:col-span-2">
                @foreach ([
                    ['icon' => '<path d="M13 2a9 9 0 0 1 9 9"/><path d="M13 6a5 5 0 0 1 5 5"/><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/>', 'title' => 'Téléphone', 'text' => $siteSettings->get('company.phone', '')],
                    ['icon' => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>', 'title' => 'Email', 'text' => $siteSettings->get('company.email', '')],
                    ['icon' => '<path d="M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z"/><path d="M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2"/><path d="M18 22v-3"/><circle cx="10" cy="10" r="3"/>', 'title' => 'Adresse', 'text' => $siteSettings->get('company.address', '')],
                    ['icon' => '<circle cx="12" cy="13" r="8"/><path d="M5 3 2 6"/><path d="m22 6-3-3"/><path d="m9 13 2 2 4-4"/>', 'title' => 'Horaires', 'text' => $siteSettings->get('company.hours', '')],
                ] as $card)
                    <div class="flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-brand-cyan-light text-brand-cyan-dark">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $card['icon'] !!}</svg>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-900">{{ $card['title'] }}</h3>
                            <p class="mt-0.5 text-sm text-slate-500">{{ $card['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Formulaire --}}
            <form method="POST" action="{{ route('contact.send') }}" class="space-y-5 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm lg:col-span-3">
                @csrf
                @if (session('success'))
                    <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
                @endif
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="nom" class="mb-1 block text-sm font-semibold text-slate-700">Nom complet *</label>
                        <input id="nom" name="nom" type="text" required value="{{ old('nom') }}"
                               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">
                        @error('nom')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="mb-1 block text-sm font-semibold text-slate-700">Email *</label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">
                        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label for="sujet" class="mb-1 block text-sm font-semibold text-slate-700">Sujet *</label>
                    <input id="sujet" name="sujet" type="text" required value="{{ old('sujet') }}"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">
                    @error('sujet')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="message" class="mb-1 block text-sm font-semibold text-slate-700">Message *</label>
                    <textarea id="message" name="message" rows="5" required
                              class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-brand-cyan">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="w-full rounded-xl bg-brand-cyan px-6 py-3.5 font-bold text-white transition hover:bg-brand-cyan-dark">
                    Envoyer le message
                </button>
            </form>
        </div>
    </div>
@endsection
