<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ADO PRINT — Imprimerie en ligne au Maroc')</title>
    <meta name="description" content="@yield('meta_description', 'Impression en ligne : cartes de visite, flyers, brochures, affiches, objets publicitaires. Qualité professionnelle, prix dégressifs et livraison rapide partout au Maroc.')">

    {{-- Identité ADO PRINT : icônes issues du logo officiel --}}
    <link rel="icon" type="image/x-icon" href="/favicon.ico?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32.png?v=2">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192.png?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png?v=2">
    <meta name="theme-color" content="#0a0a0a">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-slate-800 antialiased">
    @include('partials.site-header')

    <main>
        @yield('content')
    </main>

    @include('partials.site-bottom')
