<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoOrdersSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('orders') || ! Schema::hasTable('users') || ! Schema::hasTable('products')) {
            $this->command->info('Tables nécessaires (orders/users/products) manquantes, DemoOrdersSeeder ignoré.');
            return;
        }

        $user = DB::table('users')->where('email', 'client1@demo.local')->first();
        $product = DB::table('products')->first();

        if (! $user || ! $product) {
            $this->command->info('Utilisateur ou produit de démonstration manquant, ordre non créé.');
            return;
        }

        DB::table('orders')->insert([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => $product->base_price ?? 0,
            'meta' => json_encode([
                'items' => [
                    [
                        'product_id' => $product->id,
                        'quantity' => 1,
                        'unit_price' => $product->base_price ?? 0,
                    ],
                ],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
