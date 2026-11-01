<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ADO PRINT — Imprimerie en ligne au Maroc')</title>
    <meta name="description" content="@yield('meta_description', 'Impression en ligne : cartes de visite, flyers, brochures, affiches, objets publicitaires. Qualité professionnelle, prix dégressifs et livraison rapide partout au Maroc.')">

    @php $brandIcon = $siteSettings->get('brand.icon'); @endphp
    @if ($brandIcon){{-- Identité ADO PRINT (icône administrable via /admin) --}}
        <link rel="icon" type="image/png" href="/storage/{{ $brandIcon }}">
        <link rel="apple-touch-icon" sizes="180x180" href="/storage/{{ $brandIcon }}">
    @else
        <link rel="icon" type="image/x-icon" href="/favicon.ico?v=2">
        <link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32.png?v=2">
        <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png?v=2">
    @endif
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
           ce qui masque tout le contenu du navbar Bootstrap. On restaure Bootstrap correctement. */
        .navbar-collapse:not(#navbarText):not(.show) { display: none !important; }
        .navbar .collapse.show { display: block !important; }
        /* Tailwind v4 met ses utilitaires en @layer, prioritaires sur la feuille Bootstrap :
           .sr-only était donc visible. On le ré-implémente hors layer. */
        .sr-only { position: absolute !important; width: 1px !important; height: 1px !important; padding: 0 !important; margin: -1px !important; overflow: hidden !important; clip: rect(0,0,0,0) !important; white-space: nowrap !important; border: 0 !important; }
        .top_cms_header { display: flex; justify-content: flex-start; gap: 9px; }
        .btn-custom { background-color: #00aeef; border: 1.8px solid #00aeef; color: #fff; display: flex; align-items: center; padding: 9px; transition: background-color .3s, color .3s, border-color .3s; flex: 1 1 auto; justify-content: center; min-width: 125px; }
        .btn-custom:hover { background-color: #0089c1; color: #fff; }
        .btn-custom-cta { display: block; text-align: center; padding: 9px; margin-top: 9px; background-color: transparent; border: 1.8px solid black; color: black; text-decoration: none; font-weight: bold; font-size: 14px; }
        .btn-custom-cta:hover { background-color: #00aeef; color: white; border-color: #00aeef; }
        .dropdown-divider { border-top: .9px solid #e9ecef; margin: .45rem 0; }
        .dropdown-menu-gray { background-color: #f0f0f0; }
        .dropdown-menu-wide { min-width: 270px; }
        .btn-custom .whatsapp-icon { color: #fff; }
        .btn-icon { font-size: 20px; margin-right: 5px; }
        .btn-text { font-size: 14px; white-space: nowrap; margin-left: 5px; }
        .btn-group { display: flex; flex-grow: 1; margin-right: 9px; }
        {{-- Loupe du champ de recherche d'en-tête --}}
        .search-icon .twitter-typeahead { position: relative; display: block; width: 100%; }
        .search-icon .twitter-typeahead::before { content: "\f002"; font-family: "Font Awesome 6 Free"; font-weight: 900; position: absolute; left: 1.35rem; top: 50%; transform: translateY(-50%); color: #0b0b0b; font-size: 20px; line-height: 1; z-index: 4; pointer-events: none; }
        .search-icon .twitter-typeahead::after { content: none; }
        /* ========================================================================
           Menu mobile (hamburger) — drawer ADO PRINT
géré par le partiel header (classes .apm-*)
           ======================================================================== */
        /* Sur mobile, l'ancien collapse Bootstrap est remplacé par le drawer .apm- */
        @media (max-width: 991.98px) {
            #navbarText { display: none !important; }
        }

        @media (min-width: 992px) {
            #navbarText {
                display: block !important;
                visibility: visible !important;
                position: static !important;
            }
        }

        /* Bouton hamburger */
        .apm-toggle {
            display: inline-grid;
            place-items: center;
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            padding: 0;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            background: #fff;
            color: #0f172a;
            box-shadow: 0 2px 8px rgba(15, 23, 42, .08);
            cursor: pointer;
            transition: border-color .15s ease, color .15s ease, background-color .15s ease;
        }
        .apm-toggle:hover, .apm-toggle:focus-visible {
            border-color: #00aeef;
            color: #0089c1;
            background: #f5fcff;
        }
        .apm-toggle svg { width: 24px; height: 24px; }
        @media (min-width: 992px) {
            .apm-toggle { display: none !important; }
        }

        /* Voile sombre derrière le drawer */
        .apm-backdrop {
            position: fixed;
            inset: 0;
            z-index: 1240;
            background: rgba(15, 23, 42, .45);
            opacity: 0;
            visibility: hidden;
            transition: opacity .25s ease, visibility .25s ease;
        }
        .apm-backdrop.is-open { opacity: 1; visibility: visible; }

        /* Le drawer lui-même : glisse depuis la gauche, pleine hauteur */
        .apm-drawer {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1250;
            width: min(86vw, 340px);
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 0 18px 18px 0;
            box-shadow: 8px 0 32px rgba(15, 23, 42, .18);
            transform: translateX(-100%);
            transition: transform .3s cubic-bezier(.2, 0, 0, 1);
        }
        .apm-drawer.is-open { transform: translateX(0); }

        .apm-drawer__head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            flex-shrink: 0;
        }
        .apm-drawer__brand img {
            height: 40px;
            width: auto;
            max-width: 130px;
            object-fit: contain;
            display: block;
        }
        .apm-drawer__close {
            display: grid;
            place-items: center;
            width: 40px;
            height: 40px;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            background: #fff;
            color: #0f172a;
            cursor: pointer;
            transition: border-color .15s ease, color .15s ease, background-color .15s ease;
        }
        .apm-drawer__close:hover {
            border-color: #00aeef;
            color: #0089c1;
            background: #f5fcff;
        }
        .apm-drawer__close svg { width: 18px; height: 18px; }

        .apm-drawer__search {
            padding: 14px 16px 4px;
            flex-shrink: 0;
        }
        .apm-drawer__search .apm-search-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .apm-drawer__search svg.apm-search-icon {
            position: absolute;
            left: 12px;
            width: 16px;
            height: 16px;
            color: #94a3b8;
            pointer-events: none;
        }
        .apm-drawer__search input {
            width: 100%;
            padding: 11px 40px 11px 36px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-family: inherit;
            font-size: 14.5px;
            color: #0f172a;
            background: #f8fafc;
            outline: none;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .apm-drawer__search input:focus {
            border-color: #00aeef;
            box-shadow: 0 0 0 3px rgba(0, 174, 239, .12);
            background: #fff;
        }
        .apm-drawer__search button {
            position: absolute;
            right: 6px;
            padding: 7px 11px;
            border: none;
            border-radius: 9px;
            background: #00aeef;
            color: #fff;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
        }

        /* Liste des familles en accordéon */
        .apm-drawer__nav {
            flex: 1;
            overflow-y: auto;
            overscroll-behavior: contain;
            padding: 10px 12px;
            -webkit-overflow-scrolling: touch;
        }
        .apm-drawer__nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .apm-acc { border-bottom: 1px solid #f1f5f9; }
        .apm-acc:last-child { border-bottom: none; }
        .apm-acc__head {
            display: flex;
            align-items: stretch;
            gap: 2px;
        }
        .apm-acc__link {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 8px 13px 12px;
            border-radius: 12px;
            color: #0f172a;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: background-color .15s ease, color .15s ease;
        }
        .apm-acc__link i { color: #00aeef; width: 20px; text-align: center; font-size: 15px; }
        .apm-acc__link:active, .apm-acc__link:hover { background: #f0faff; color: #0089c1; }
        .apm-acc__btn {
            display: grid;
            place-items: center;
            width: 40px;
            flex-shrink: 0;
            border: none;
            background: transparent;
            color: #94a3b8;
            cursor: pointer;
            border-radius: 10px;
            transition: transform .2s ease, color .15s ease;
        }
        .apm-acc__btn svg { width: 16px; height: 16px; transition: transform .2s ease; }
        .apm-acc__item.is-open .apm-acc__btn svg { transform: rotate(180deg); color: #00aeef; }

        .apm-acc__panel {
            overflow: hidden;
            max-height: 0;
            transition: max-height .3s ease;
        }
        .apm-acc__sub {
            display: block;
            padding: 10px 12px 10px 42px;
            color: #475569;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 10px;
            transition: background-color .15s ease, color .15s ease;
        }
        .apm-acc__sub:hover, .apm-acc__sub:active { background: #f0faff; color: #0089c1; }
        .apm-acc__all {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 12px;
            margin: 2px 6px 8px 30px;
            background: #f0faff;
            border-radius: 10px;
            color: #0089c1;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
        }

        /* Raccourcis bas du drawer */
        .apm-drawer__quick {
            flex-shrink: 0;
            padding: 12px;
            border-top: 1px solid #e2e8f0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .apm-quick {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 8px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: background-color .15s ease, color .15s ease;
        }
        .apm-quick svg { width: 16px; height: 16px; flex-shrink: 0; }
        .apm-quick--wa { background: #e8fbf1; color: #128c56; }
        .apm-quick--wa:hover { background: #25D366; color: #fff; }
        .apm-quick--devis { background: #e6f7fe; color: #0089c1; }
        .apm-quick--devis:hover { background: #00aeef; color: #fff; }
        .apm-quick--cart { background: #fff7e0; color: #a16207; }
        .apm-quick--cart:hover { background: #f59e0b; color: #fff; }
        .apm-quick--aide { background: #f1f5f9; color: #0f172a; }
        .apm-quick--aide:hover { background: #0f172a; color: #fff; }
        .apm-quick__count {
            display: inline-grid;
            place-items: center;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: #f59e0b;
            color: #fff;
            font-size: 11px;
            font-weight: 800;
        }
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
