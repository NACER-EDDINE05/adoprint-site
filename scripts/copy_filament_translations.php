<?php
/**
 * Copy Filament translation files from vendor to resources/lang/<locale>/filament.php
 * Usage: php scripts/copy_filament_translations.php
 * - Requires `composer install` and `vendor/filament/*` present.
 * - This script will scan vendor/filament for language files and copy them to resources/lang.
 */

$vendorPath = __DIR__ . '/../vendor/filament';
$resourcesPath = __DIR__ . '/../resources/lang';

if (!is_dir(__DIR__ . '/../vendor')) {
    echo "vendor directory not found. Run `composer install` first.\n";
    exit(1);
}

$finder = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/../vendor'));
$copied = 0;
foreach ($finder as $file) {
    if ($file->isFile() && preg_match('#/filament(?:-[^/]*)?/lang/([^/]+)/filament\.php$#', str_replace('\\', '/', $file->getPathname()), $m)) {
        $locale = $m[1];
        $destDir = $resourcesPath . "/$locale";
        if (!is_dir($destDir)) {
            mkdir($destDir, 0775, true);
        }
        $destFile = $destDir . '/filament.php';
        copy($file->getPathname(), $destFile);
        echo "Copied $locale -> $destFile\n";
        $copied++;
    }
}

if ($copied === 0) {
    echo "No Filament translation files found in vendor. Ensure Filament is installed.\n";
} else {
    echo "Done. Copied $copied translation file(s).\n";
}
