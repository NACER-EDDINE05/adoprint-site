<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateFilamentTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament:translations:generate {--write : Write directly to filament.php (backup created)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Filament translations from vendor and merge with existing translations';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $write = $this->option('write');
        $script = base_path('scripts/generate_filament_translations.php');
        if (!file_exists($script)) {
            $this->error("scripts/generate_filament_translations.php not found. Run composer install and ensure scripts/ exists.\n");
            return 1;
        }

        $php = PHP_BINARY;
        $cmd = escapeshellarg($php) . ' ' . escapeshellarg($script) . ($write ? ' --write' : '');
        $this->info("Running: $cmd");
        passthru($cmd, $ret);
        if ($ret !== 0) {
            $this->error("Command failed with exit code $ret");
            return $ret;
        }

        $this->info('Done. Check resources/lang/*/filament.generated.php (or filament.php if --write used).');
        return 0;
    }
}
