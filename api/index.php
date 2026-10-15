<?php

/**
 * Vercel serverless entry point.
 *
 * Vercel's PHP runtime (vercel-php) executes this file for every dynamic
 * request. It simply boots the real Laravel application that lives in
 * /public/index.php, so all routing, controllers and views behave exactly
 * as they do locally.
 */

// ---------------------------------------------------------------------------
// Système de fichiers en lecture seule : Vercel ne persiste aucun fichier.
// La base SQLite embarquée est donc COPIÉE dans /tmp (seul dossier inscriptible)
// à chaque démarrage à froid, pour éviter les erreurs « attempt to write a
// readonly database ». Les lectures fonctionnent ; les écritures sont perdues
// au redémarrage (comportement attendu pour une démo visuelle).
// ---------------------------------------------------------------------------
$srcDb = __DIR__ . '/../database/database.sqlite';
$tmpDir = sys_get_temp_dir();
$tmpDb = $tmpDir . '/adoprint.sqlite';

if (is_file($srcDb) && !is_file($tmpDb)) {
    @copy($srcDb, $tmpDb);
}
if (is_file($tmpDb)) {
    putenv('DB_DATABASE=' . $tmpDb);
    $_ENV['DB_DATABASE'] = $tmpDb;
    $_SERVER['DB_DATABASE'] = $tmpDb;
}

require __DIR__ . '/../public/index.php';

