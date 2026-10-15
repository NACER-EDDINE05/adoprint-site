<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoCompanySeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('settings')) {
            $this->command->info('Table `settings` inexistante, DemoCompanySeeder ignoré.');
            return;
        }

        // Valeurs de démonstration clairement identifiables
        DB::table('settings')->updateOrInsert(
            ['key' => 'company.name'],
            ['value' => 'Imprimerie Demo']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'company.email'],
            ['value' => 'contact@imprimerie.local']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'company.phone'],
            ['value' => '+212600000000']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'company.currency'],
            ['value' => 'MAD']
        );
    }
}
