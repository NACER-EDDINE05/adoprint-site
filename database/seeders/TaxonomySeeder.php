<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Crée les 9 familles ADO PRINT et leurs sous-catégories, puis rattache les
 * produits existants à la bonne sous-catégorie. Les anciennes catégories
 * plates (si elles existent encore) sont archivées pour ne pas polluer la
 * boutique. Le seeder est idempotent : on peut le relancer sans risque.
 */
class TaxonomySeeder extends Seeder
{
    public function run(): void
    {
        $familySlugs = [];
        $childSlugs  = [];
        $display     = AdoPrintTaxonomy::displayNames();

        foreach (AdoPrintTaxonomy::families() as $familyIndex => $family) {
            $familySlug   = Str::slug($family['name']);
            $familySlugs[] = $familySlug;

            $parent = Category::updateOrCreate(
                ['slug' => $familySlug],
                [
                    'name'        => $family['name'],
                    'description' => $family['description'],
                    'parent_id'   => null,
                    'position'    => $familyIndex + 1,
                    'active'      => true,
                ]
            );

            foreach ($family['children'] as $childIndex => $childName) {
                $childSlug = Str::slug($childName);

                if (isset($childSlugs[$childSlug])) {
                    throw new RuntimeException("Slug de sous-catégorie en double : {$childSlug}");
                }
                $childSlugs[$childSlug] = $familySlug;

                Category::updateOrCreate(
                    ['slug' => $childSlug],
                    [
                        'name'      => $display[$childSlug] ?? $childName,
                        'parent_id' => $parent->id,
                        'position'  => $childIndex + 1,
                        'active'    => true,
                    ]
                );
            }
        }

        $this->mapProducts();

        $this->archiveLegacyCategories(array_merge($familySlugs, array_keys($childSlugs)));
    }

    /**
     * Rattache chaque produit existant à sa sous-catégorie.
     */
    private function mapProducts(): void
    {
        foreach (AdoPrintTaxonomy::productMap() as $productSlug => $childSlug) {
            $childId = Category::where('slug', $childSlug)->value('id');

            if (! $childId) {
                $this->command?->warn("Sous-catégorie introuvable : {$childSlug}");

                continue;
            }

            $moved = Product::where('slug', $productSlug)->update(['category_id' => $childId]);

            if (! $moved) {
                $this->command?->warn("Produit introuvable : {$productSlug}");
            }
        }

        // Les produits qui n'ont pas d'équivalent dans la nouvelle taxonomie
        // restent visibles dans la première famille (Papier & Bureautique).
        $orphans = Product::whereNotIn('category_id', Category::where('active', true)->pluck('id'))->get();
        $fallback = Category::where('slug', 'impression-de-documents')->value('id');
        if ($orphans->isNotEmpty() && $fallback) {
            Product::whereIn('id', $orphans->pluck('id'))->update(['category_id' => $fallback]);
            $this->command?->warn($orphans->count().' produit(s) rattaché(s) à « Impression de documents ».');
        }
    }

    /**
     * Les anciennes catégories (Cartes de visite, Flyers…) sont conservées en
     * base pour l'historique de l'admin mais retirées de la boutique.
     */
    private function archiveLegacyCategories(array $keptSlugs): void
    {
        foreach (Category::whereNotIn('slug', $keptSlugs)->get() as $legacy) {
            $legacy->update([
                'active' => false,
                'slug'   => Str::limit('archive-'.Str::slug($legacy->name), 60, ''),
            ]);
        }
    }
}
