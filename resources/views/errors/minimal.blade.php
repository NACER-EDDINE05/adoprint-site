@php
    $status = $status ?? (isset($exception) && method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);

    $titles = [
        401 => 'Connexion requise',
        403 => 'Accès refusé',
        404 => 'Page introuvable',
        419 => 'Session expirée',
        429 => 'Trop de requêtes',
        500 => 'Erreur du serveur',
        503 => 'Maintenance en cours',
    ];

    $messages = [
        401 => 'Connectez-vous pour accéder à cette page.',
        403 => 'Vous n’avez pas les droits nécessaires pour cette page.',
        404 => 'Cette page n’existe pas ou a été déplacée. Le catalogue, lui, est bien en stock.',
        419 => 'Votre session a expiré — rechargez la page et réessayez.',
        429 => 'Un petit temps de pause avant de réessayer.',
        500 => 'Une erreur inattendue est survenue. Notre équipe en est informée.',
        503 => 'Nous revenons très vite — l’imprimerie fait une pause technique.',
    ];

    $title = $titles[$status] ?? 'Une erreur est survenue';
    $message = $messages[$status] ?? 'Quelque chose s’est mal passé. Réessayez dans un instant.';
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0a">
    <title>{{ $status }} — ADO PRINT</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32.png?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png?v=2">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #fff;
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;
            color: #0f172a;
        }
        .stripes { position: fixed; inset-inline: 0; height: 6px; background: linear-gradient(90deg, #00aeef 0 25%, #ec1e79 25% 50%, #ffc20e 50% 75%, #0a0a0a 75% 100%); }
        .stripes.top { top: 0; } .stripes.bottom { bottom: 0; }
        .card { text-align: center; padding: 48px 24px; max-width: 34rem; }
        .logo { height: 56px; margin-inline: auto; display: block; }
        .code { margin: 40px 0 0; font-size: 96px; line-height: 1; font-weight: 800; letter-spacing: -0.04em; color: #0f172a; }
        .code span { color: #0089c1; }
        h1 { margin: 12px 0 0; font-size: 24px; font-weight: 800; }
        p { margin: 12px 0 0; color: #64748b; line-height: 1.6; }
        .actions { margin-top: 32px; display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn { display: inline-flex; align-items: center; gap: 8px; border-radius: 12px; padding: 12px 24px; font-weight: 700; text-decoration: none; transition: background .2s, border-color .2s; }
        .btn-primary { background: #00aeef; color: #fff; } .btn-primary:hover { background: #0089c1; }
        .btn-ghost { border: 1px solid #cbd5e1; color: #334155; } .btn-ghost:hover { border-color: #00aeef; color: #0089c1; }
    </style>
</head>
<body>
    <div class="stripes top" aria-hidden="true"></div>
    <main class="card">
        <img src="/images/logo.png" alt="ADO PRINT" class="logo">
        <p class="code">{!! substr((string) $status, 0, 1) . '<span>' . substr((string) $status, 1) . '</span>' !!}</p>
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
        <div class="actions">
            <a href="/" class="btn btn-primary">Retour à l’accueil</a>
            <a href="/contact" class="btn btn-ghost">Nous contacter</a>
        </div>
    </main>
    <div class="stripes bottom" aria-hidden="true"></div>
</body>
</html>
