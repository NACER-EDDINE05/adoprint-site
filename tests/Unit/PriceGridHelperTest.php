<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\PriceGrid;

class PriceGridHelperTest extends TestCase
{
    use RefreshDatabase;

    public function test_forQuantity_returns_correct_pricegrid()
    {
        $product = Product::factory()->create();

        $low = PriceGrid::factory()->create([
            'product_id' => $product->id,
            'min_qty' => 1,
            'max_qty' => 10,
            'price' => 12.00,
        ]);

        $high = PriceGrid::factory()->create([
            'product_id' => $product->id,
            'min_qty' => 11,
            'max_qty' => 100,
            'price' => 9.00,
        ]);

        $this->assertEquals(12.00, (float) PriceGrid::forQuantity($product->id, 1)->price);
        $this->assertEquals(12.00, (float) PriceGrid::forQuantity($product->id, 10)->price);
        $this->assertEquals(9.00, (float) PriceGrid::forQuantity($product->id, 11)->price);
        $this->assertEquals(9.00, (float) PriceGrid::forQuantity($product->id, 50)->price);
    }
}
