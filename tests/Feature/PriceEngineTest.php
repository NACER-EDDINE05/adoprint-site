<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\PriceGrid;

class PriceEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_price_selected_based_on_quantity_ranges()
    {
        $product = Product::factory()->create();

        PriceGrid::factory()->create([
            'product_id' => $product->id,
            'min_qty' => 1,
            'max_qty' => 10,
            'price' => 10.00,
        ]);

        PriceGrid::factory()->create([
            'product_id' => $product->id,
            'min_qty' => 11,
            'max_qty' => 100,
            'price' => 8.50,
        ]);

        $cases = [
            1 => 10.00,
            5 => 10.00,
            10 => 10.00,
            11 => 8.50,
            50 => 8.50,
        ];

        foreach ($cases as $qty => $expected) {
            $priceGrid = PriceGrid::where('product_id', $product->id)
                ->where('min_qty', '<=', $qty)
                ->where('max_qty', '>=', $qty)
                ->first();

            $this->assertNotNull($priceGrid, "No PriceGrid found for qty $qty");
            $this->assertEquals($expected, (float) $priceGrid->price);
        }
    }
}
