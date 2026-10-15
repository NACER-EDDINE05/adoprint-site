<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductOption;
use App\Models\Product;

class ProductOptionFactory extends Factory
{
    protected $model = ProductOption::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['select', 'text', 'number']),
            'values' => ['Option A', 'Option B'],
        ];
    }
}
