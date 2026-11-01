<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        // Le slug est optionnel : s'il est vide (ou blanc), il est déduit du nom
        // et rendu unique automatiquement (flyers-a5, flyers-a5-2, …).
        static::saving(function (Product $product) {
            if (trim((string) $product->slug) === '') {
                $product->slug = $product->uniqueSlug($product->name ?: 'produit', $product);
            }
        });
    }

    /** Génère un slug unique à partir d'un texte de base (ignorer un id existant à la mise à jour). */
    public function uniqueSlug(string $base, ?Product $ignore = null): string
    {
        $base = Str::slug($base) ?: 'produit';
        $slug = $base;
        $i = 1;
        while (static::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($q) => $q->whereKeyNot($ignore->getKey()))
            ->exists()) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }

    protected $fillable = ['category_id','name','slug','description','photo','base_price','active'];

    /** Chemin du fichier photo (null si aucun produit dépendant / aucune image fournie). */
    public function photo(): ?string
    {
        return $this->photo;
    }

    /** URL relative de la photo du produit (stockée sur le disque public), sinon null. */
    public function photoUrl(): ?string
    {
        // Racine-relative : fonctionne sur n'importe quel hôte (127.0.0.1, adoprint.test, prod).
        return $this->photo ? '/storage/' . $this->photo : null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function priceGrids()
    {
        return $this->hasMany(PriceGrid::class);
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }
}
