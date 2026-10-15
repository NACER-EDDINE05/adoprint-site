<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\PriceGrid;

class PriceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_price_for_quantity()
    {
        $product = Product::factory()->create();

        PriceGrid::factory()->create([
            'product_id' => $product->id,
            'min_qty' => 1,
            'max_qty' => 10,
            'price' => 11.50,
        ]);

        $response = $this->getJson('/api/price?product_id=' . $product->id . '&qty=5');

        $response->assertStatus(200)
            ->assertJson(['price' => 11.5]);
    }

    public function test_api_returns_null_when_no_price()
    {
        $product = Product::factory()->create();

        $response = $this->getJson('/api/price?product_id=' . $product->id . '&qty=50');

        $response->assertStatus(200)
            ->assertJson(['price' => null]);
    }
}
