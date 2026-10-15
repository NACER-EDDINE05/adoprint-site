<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('products') || ! Schema::hasTable('categories')) {
            $this->command->info('Tables `products` ou `categories` inexistantes, ProductSeeder ignoré.');
            return;
        }

        $category = DB::table('categories')->where('slug', 'cartes-de-visite')->first();

        $products = [
            [
                'name' => 'Carte de visite classique',
                'slug' => 'carte-de-visite-classique',
                'category_id' => $category ? $category->id : null,
                'base_price' => 50,
            ],
            [
                'name' => 'Flyer A5',
                'slug' => 'flyer-a5',
                'category_id' => null,
                'base_price' => 120,
            ],
        ];

        foreach ($products as $p) {
            DB::table('products')->updateOrInsert(['slug' => $p['slug']], $p);
        }
    }
}
