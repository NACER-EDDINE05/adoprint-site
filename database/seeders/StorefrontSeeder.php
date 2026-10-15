<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Amorce la boutique publique : coordonnées de l'entreprise, familles de
 * produits supplémentaires, produits avec descriptions et grilles tarifaires
 * dégressives (MAD). Toutes les lignes sont idempotentes (updateOrInsert).
 */
class StorefrontSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedCategories();
        $this->seedProducts();
    }

    private function seedSettings(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        // Coordonnées réelles (carte d'entreprise ADO PRINT) :
        // tél. 0703-201412 · adoprint.2@gmail.com · GCW3+7JP Casablanca · Instagram @print_ado
        $settings = [
            'company.name'        => 'ADO PRINT',
            'company.email'       => 'adoprint.2@gmail.com',
            'company.phone'       => '0703-201412',
            'company.phone_intl'  => '+212703201412',
            'company.whatsapp'    => '+212703201412',
            'company.address'     => 'GCW3+7JP, Casablanca, Maroc',
            'company.maps_url'    => 'https://www.google.com/maps/search/?api=1&query=GCW3%2B7JP+Casablanca',
            'company.currency'    => 'MAD',
            'company.hours'       => 'Lun–Ven : 9h–18h · Sam : 9h–13h',
            'company.tagline'     => 'Votre imprimerie en ligne, livrée partout au Maroc',
            'social.instagram'    => 'https://www.instagram.com/print_ado',
            'social.messenger'    => '',
            'social.facebook'     => '',
        ];

        foreach ($settings as $key => $value) {
            DB::table('settings')->updateOrInsert(['key' => $key], ['value' => $value]);
        }
    }

    private function seedCategories(): void
    {
        if (! Schema::hasTable('categories')) {
            return;
        }

        foreach (StorefrontCatalog::categories() as $cat) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $cat['slug']],
                $cat + ['active' => true]
            );
        }
    }

    private function seedProducts(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        foreach (StorefrontCatalog::products() as $entry) {
            $categoryId = DB::table('categories')->where('slug', $entry['category'])->value('id');

            DB::table('products')->updateOrInsert(
                ['slug' => $entry['slug']],
                [
                    'category_id' => $categoryId,
                    'name'        => $entry['name'],
                    'description' => $entry['description'],
                    'base_price'  => $entry['grids'][0][2],
                    'active'      => true,
                ]
            );
        }

        if (! Schema::hasTable('price_grids')) {
            return;
        }

        foreach (StorefrontCatalog::products() as $entry) {
            $product = DB::table('products')->where('slug', $entry['slug'])->first();
            if (! $product) {
                continue;
            }

            foreach ($entry['grids'] as [$min, $max, $price]) {
                DB::table('price_grids')->updateOrInsert(
                    ['product_id' => $product->id, 'min_qty' => $min],
                    [
                        'name'       => sprintf('%d-%d', $min, $max),
                        'max_qty'    => $max,
                        'price'      => $price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
