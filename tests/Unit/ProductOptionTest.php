<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ProductOption;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOptionTest extends TestCase
{
    public function test_fillable_contains_expected_fields()
    {
        $model = new ProductOption();

        $this->assertContains('product_id', $model->getFillable());
        $this->assertContains('name', $model->getFillable());
        $this->assertContains('type', $model->getFillable());
        $this->assertContains('values', $model->getFillable());
    }

    public function test_values_casted_to_array()
    {
        $model = new ProductOption();

        $casts = $model->getCasts();

        $this->assertArrayHasKey('values', $casts);
        $this->assertEquals('array', $casts['values']);
    }

    public function test_product_relation_returns_belongs_to()
    {
        $model = new ProductOption();

        $relation = $model->product();

        $this->assertInstanceOf(BelongsTo::class, $relation);
    }
}
