<?php

namespace App\Services;

use App\Models\PriceGrid;

class PriceService
{
    /**
     * Get the price for a product and quantity, or default when none matches.
     *
     * @param int $productId
     * @param int $qty
     * @param float|null $default
     * @return float|null
     */
    public function getPriceForQuantity(int $productId, int $qty, ?float $default = null): ?float
    {
        return PriceGrid::priceForQuantity($productId, $qty, $default);
    }
}
