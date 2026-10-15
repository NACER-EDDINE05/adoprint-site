<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','name','slug','description','photo','base_price','active'];

    /** URL de la photo du produit (stockée sur le disque public), sinon null. */
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
