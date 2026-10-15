<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptionSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('product_options') || ! Schema::hasTable('products')) {
            $this->command->info('Table `product_options` ou `products` inexistante, OptionSeeder ignoré.');
            return;
        }

        $products = DB::table('products')->pluck('id');

        if ($products->isEmpty()) {
            $this->command->info('Aucun produit disponible, OptionSeeder ignoré.');
            return;
        }

        foreach ($products as $productId) {
            DB::table('product_options')->insertOrIgnore([
                'product_id' => $productId,
                'name' => 'Format',
                'type' => 'select',
                'values' => json_encode(['A6', 'A5', 'A4']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('product_options')->insertOrIgnore([
                'product_id' => $productId,
                'name' => 'Papier',
                'type' => 'select',
                'values' => json_encode(['Couché', 'Mat', 'Brillant']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
