<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ADO PRINT — Imprimerie en ligne au Maroc')</title>
    <meta name="description" content="@yield('meta_description', 'Impression en ligne : cartes de visite, flyers, brochures, affiches, objets publicitaires. Qualité professionnelle, prix dégressifs et livraison rapide partout au Maroc.')">

    {{-- Identité ADO PRINT --}}
    <link rel="icon" type="image/x-icon" href="/favicon.ico?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32.png?v=2">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192.png?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png?v=2">
    <meta name="theme-color" content="#0b0b0b">
    <meta name="route-contact" content="{{ route('contact') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Font Awesome (mêmes références que la maquette) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    {{-- Tailwind (pages internes) puis la charte réplique (Bootstrap 4.6.2 + thème) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="/reference/style1.css">
    <link rel="stylesheet" href="/reference/style2.css">
    <link rel="stylesheet" href="/reference/theme.css">
    <link rel="stylesheet" href="/reference/24hprint.css">
    <link rel="stylesheet" href="/css/shop-replica.css">
    <style>
        /* Conflit Tailwind × Bootstrap : Tailwind définit .collapse { visibility: collapse }
           ce qui masque tout le contenu du navbar Bootstrap. On restaure Bootstrap. */
        .collapse { visibility: visible !important; }
        /* Tailwind v4 met ses utilitaires en @layer, prioritaires sur la feuille Bootstrap :
           .sr-only était donc visible. On le ré-implémente hors layer. */
        .sr-only { position: absolute !important; width: 1px !important; height: 1px !important; padding: 0 !important; margin: -1px !important; overflow: hidden !important; clip: rect(0,0,0,0) !important; white-space: nowrap !important; border: 0 !important; }
        .top_cms_header { display: flex; justify-content: flex-start; gap: 9px; }
        .btn-custom { background-color: white; border: 1.8px solid black; color: black; display: flex; align-items: center; padding: 9px; transition: background-color .3s, color .3s; flex: 1 1 auto; justify-content: center; min-width: 135px; }
        .btn-custom:hover { background-color: yellow; color: black; }
        .btn-icon { font-size: 20px; margin-right: 5px; }
        .whatsapp-icon { color: #25D366; }
        .btn-text { font-size: 14px; white-space: nowrap; margin-left: 5px; }
        .btn-custom-cta { display: block; text-align: center; padding: 9px; margin-top: 9px; background-color: transparent; border: 1.8px solid black; color: black; text-decoration: none; font-weight: bold; font-size: 14px; }
        .btn-custom-cta:hover { background-color: blue; color: white; }
        .dropdown-divider { border-top: .9px solid #e9ecef; margin: .45rem 0; }
        .dropdown-menu-gray { background-color: #f0f0f0; }
        .dropdown-menu-wide { min-width: 270px; }
        .btn-group { display: flex; flex-grow: 1; margin-right: 9px; }
    </style>
</head>
<body class="row-offcanvas-left">

    @include('partials.replica-header')

    <main id="main-content" role="main">
        @yield('content')
    </main>

    @include('partials.replica-footer')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <script src="/js/replica.js"></script>
    @stack('scripts')
</body>
</html>
