<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('categories')) {
            $this->command->info('Table `categories` inexistante, CategorySeeder ignoré.');
            return;
        }

        $categories = [
            ['name' => 'Cartes de visite', 'slug' => 'cartes-de-visite'],
            ['name' => 'Flyers', 'slug' => 'flyers'],
            ['name' => 'Brochures', 'slug' => 'brochures'],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->updateOrInsert(['slug' => $cat['slug']], $cat);
        }
    }
}
