<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PriceGrid;
use App\Models\Product;

class PriceGridFactory extends Factory
{
    protected $model = PriceGrid::class;

    public function definition()
    {
        $min = $this->faker->numberBetween(1, 10);
        $max = $this->faker->numberBetween(11, 1000);

        return [
            'product_id' => Product::factory(),
            'min_qty' => $min,
            'max_qty' => $max,
            'price' => $this->faker->randomFloat(2, 0.5, 500),
            'conditions' => [],
        ];
    }
}
