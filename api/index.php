<?php

/**
 * Vercel serverless entry point.
 *
 * Vercel's PHP runtime (vercel-php) executes this file for every dynamic
 * request. It boots the real Laravel application that lives in
 * /public/index.php so all routing, controllers and views behave exactly
 * as they do locally.
 *
 * Vercel's filesystem is READ-ONLY except for /tmp. Laravel writes to
 * storage/ (logs, framework cache, compiled views, sessions) on every
 * request, which would crash with a 500 on a read-only disk. We therefore
 * redirect every writable path to /tmp before the framework boots.
 */

$tmp = rtrim(sys_get_temp_dir(), '/\\') . '/adoprint';

// Dossiers inscriptibles dédiés (créés une seule fois, au premier démarrage).
foreach (['', '/logs', '/framework/cache', '/framework/sessions', '/framework/views', '/framework/testing'] as $sub) {
    @mkdir($tmp . $sub, 0777, true);
}

// 1) Base SQLite : copiée dans /tmp pour autoriser la lecture (et éviter les
//    erreurs « attempt to write a readonly database »). Démo visuelle : les
//    écritures ne persistent pas d'un cold start à l'autre.
$srcDb = __DIR__ . '/../database/database.sqlite';
$tmpDb = $tmp . '/database.sqlite';
if (is_file($srcDb) && !is_file($tmpDb)) {
    @copy($srcDb, $tmpDb);
}
if (is_file($tmpDb)) {
    putenv('DB_DATABASE=' . $tmpDb);
    $_ENV['DB_DATABASE'] = $tmpDb;
    $_SERVER['DB_DATABASE'] = $tmpDb;
}

// 2) Redirige TOUT le stockage inscriptible vers /tmp (disque éphémère).
//    Ces variables sont lues par Laravel au boot (bootstrap/app.php + config).
putenv('APP_STORAGE_PATH=' . $tmp);
putenv('APP_SERVICES_CACHE=' . $tmp . '/services.php');
putenv('APP_PACKAGES_CACHE=' . $tmp . '/packages.php');
putenv('APP_CONFIG_CACHE=' . $tmp . '/config.php');
putenv('APP_ROUTES_CACHE=' . $tmp . '/routes.php');
putenv('APP_EVENTS_CACHE=' . $tmp . '/events.php');
putenv('LARAVEL_STORAGE_PATH=' . $tmp);

// Laravel lit ses variables via $_ENV / $_SERVER (adaptateur ServerVar),
// pas seulement via putenv() — on renseigne donc les trois canaux.
foreach ([
    'APP_STORAGE_PATH' => $tmp,
    'LARAVEL_STORAGE_PATH' => $tmp,
    'APP_SERVICES_CACHE' => $tmp . '/services.php',
    'APP_PACKAGES_CACHE' => $tmp . '/packages.php',
    'APP_CONFIG_CACHE' => $tmp . '/config.php',
    'APP_ROUTES_CACHE' => $tmp . '/routes.php',
    'APP_EVENTS_CACHE' => $tmp . '/events.php',
] as $k => $v) {
    $_ENV[$k] = $v;
    $_SERVER[$k] = $v;
}

// 3) Le debug est piloté par APP_DEBUG dans vercel.json (false en production).
//    On ne le force pas ici pour ne pas exposer d'informations internes.

require __DIR__ . '/../public/index.php';


