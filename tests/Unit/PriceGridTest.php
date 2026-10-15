<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\PriceGrid;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceGridTest extends TestCase
{
    public function test_fillable_contains_expected_fields()
    {
        $model = new PriceGrid();

        $this->assertContains('product_id', $model->getFillable());
        $this->assertContains('min_qty', $model->getFillable());
        $this->assertContains('max_qty', $model->getFillable());
        $this->assertContains('price', $model->getFillable());
    }

    public function test_casts_are_configured()
    {
        $model = new PriceGrid();

        $casts = $model->getCasts();

        $this->assertArrayHasKey('min_qty', $casts);
        $this->assertArrayHasKey('max_qty', $casts);
        $this->assertEquals('integer', $casts['min_qty']);
        $this->assertEquals('integer', $casts['max_qty']);
    }

    public function test_product_relation_returns_belongs_to()
    {
        $model = new PriceGrid();

        $relation = $model->product();

        $this->assertInstanceOf(BelongsTo::class, $relation);
    }
}
