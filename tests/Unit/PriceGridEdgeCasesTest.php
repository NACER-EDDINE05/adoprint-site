<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\PriceGrid;

class PriceGridEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_priceForQuantity_returns_default_when_no_match()
    {
        $product = Product::factory()->create();

        $this->assertNull(PriceGrid::priceForQuantity($product->id, 5));

        $default = 99.99;
        $this->assertEquals($default, PriceGrid::priceForQuantity($product->id, 5, $default));
    }

    public function test_overlapping_ranges_prefers_higher_min_qty()
    {
        $product = Product::factory()->create();

        // Overlapping ranges: first is broader, second starts higher
        $broad = PriceGrid::factory()->create([
            'product_id' => $product->id,
            'min_qty' => 1,
            'max_qty' => 100,
            'price' => 20.00,
        ]);

        $narrow = PriceGrid::factory()->create([
            'product_id' => $product->id,
            'min_qty' => 10,
            'max_qty' => 50,
            'price' => 15.00,
        ]);

        // Quantity 12 lies in both ranges; should pick `narrow` (higher min_qty)
        $this->assertEquals(15.00, PriceGrid::priceForQuantity($product->id, 12));
    }
}
