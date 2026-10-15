<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceGrid extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','name','conditions','price','min_qty','max_qty'];

    protected $casts = [
        'conditions' => 'array',
        'min_qty' => 'integer',
        'max_qty' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Return the applicable PriceGrid for a given product and quantity.
     *
     * @param  int  $productId
     * @param  int  $qty
     * @return self|null
     */
    public static function forQuantity(int $productId, int $qty): ?self
    {
        return self::where('product_id', $productId)
            ->where('min_qty', '<=', $qty)
            ->where('max_qty', '>=', $qty)
            ->orderBy('min_qty', 'desc')
            ->first();
    }

    /**
     * Return the price for a given product and quantity or a default if none matches.
     *
     * @param  int  $productId
     * @param  int  $qty
     * @param  float|null  $default
     * @return float|null
     */
    public static function priceForQuantity(int $productId, int $qty, ?float $default = null): ?float
    {
        $grid = self::forQuantity($productId, $qty);

        return $grid ? (float) $grid->price : $default;
    }
}
