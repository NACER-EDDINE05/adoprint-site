<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('filament:translations:generate {--write}', function () {
    $write = $this->option('write');
    $script = base_path('scripts/generate_filament_translations.php');
    if (!file_exists($script)) {
        $this->error("scripts/generate_filament_translations.php not found.\n");
        return 1;
    }

    $php = PHP_BINARY;
    $cmd = escapeshellarg($php) . ' ' . escapeshellarg($script) . ($write ? ' --write' : '');
    $this->info("Running: $cmd\n");
    passthru($cmd, $ret);
    if ($ret !== 0) {
        $this->error("Command failed with exit code $ret\n");
        return $ret;
    }
    $this->info("Done. Check resources/lang/*/filament.generated.php (or filament.php if --write used).\n");
    return 0;
})->purpose('Generate Filament translations from vendor and merge with existing translations');
