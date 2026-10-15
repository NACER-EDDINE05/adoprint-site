<?php
/**
 * Generate merged `resources/lang/<locale>/filament.php` by scanning vendor for Filament translation files.
 *
 * Usage:
 *   php scripts/generate_filament_translations.php [--force]
 *
 * Behavior:
 * - Scans vendor/ for files matching */lang/<locale>/filament.php
 * - Deep merges vendor-provided arrays (base) and then existing resources/lang/<locale>/filament.php (overrides)
 * - Writes merged result to resources/lang/<locale>/filament.php (backups existing file to filament.php.bak.YYYYMMDDHHMMSS)
 *
 * Run this locally after `composer install` to assemble a complete Filament translation file.
 */

$root = realpath(__DIR__ . '/..');
$vendorDir = $root . '/vendor';
$resourcesDir = $root . '/resources/lang';
$force = in_array('--force', $argv, true);
$write = in_array('--write', $argv, true); // when set, write to filament.php, otherwise write to filament.generated.php

if (!is_dir($vendorDir)) {
    echo "vendor directory not found. Run `composer install` first.\n";
    exit(1);
}

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($vendorDir));
$found = [];
foreach ($iterator as $file) {
    if (!$file->isFile()) {
        continue;
    }
    $path = str_replace('\\', '/', $file->getPathname());
    // match pattern .../lang/{locale}/filament.php
    if (preg_match('#/lang/([^/]+)/filament\.php$#', $path, $m)) {
        $locale = $m[1];
        $found[$locale][] = $path;
    }
}

if (empty($found)) {
    echo "No Filament translation files found in vendor. Ensure Filament packages are installed.\n";
    exit(0);
}

function deep_merge(array $a, array $b): array {
    foreach ($b as $k => $v) {
        if (is_array($v) && isset($a[$k]) && is_array($a[$k])) {
            $a[$k] = deep_merge($a[$k], $v);
        } else {
            $a[$k] = $v;
        }
    }
    return $a;
}

foreach ($found as $locale => $paths) {
    $merged = [];
    foreach ($paths as $p) {
        $arr = @include $p;
        if (is_array($arr)) {
            $merged = deep_merge($merged, $arr);
        }
    }

    $destDir = $resourcesDir . '/' . $locale;
    if (!is_dir($destDir)) {
        mkdir($destDir, 0775, true);
    }

    $destFile = $destDir . ($write ? '/filament.php' : '/filament.generated.php');
    // If dest exists, load it and let it override vendor values
    // If target (generated) exists and we're not forcing overwrite, merge it to preserve previous generated customizations
    $existingTarget = $destDir . ($write ? '/filament.php' : '/filament.generated.php');
    if (file_exists($existingTarget)) {
        $existing = @include $existingTarget;
        if (is_array($existing)) {
            $merged = deep_merge($merged, $existing);
        }
    }

    // When writing to the real filament.php, backup the original filament.php if present
    if ($write) {
        $realFile = $destDir . '/filament.php';
        if (file_exists($realFile)) {
            $bak = $destDir . '/filament.php.bak.' . date('YmdHis');
            if (!copy($realFile, $bak)) {
                echo "Warning: failed to backup existing $realFile\n";
            }
        }
    }

    // Write merged file (to generated or real depending on --write)
    $export = var_export($merged, true);
    $content = "<?php\n\nreturn " . $export . ";\n";

    if (file_put_contents($destFile, $content) === false) {
        echo "Failed to write $destFile\n";
    } else {
        echo "Wrote $destFile (merged from " . count($paths) . " vendor files).\n";
        if (!$write) {
            echo "Note: this wrote to filament.generated.php. Run with --write to update filament.php once you have reviewed.\n";
        }
    }
}

echo "Done. Review the generated files under resources/lang/*/filament.php and adjust translations as needed.\n";
