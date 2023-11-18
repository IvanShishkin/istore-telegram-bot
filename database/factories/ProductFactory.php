<?php

namespace Database\Factories;

use App\Domain\Products\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;


class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'active' => true,
            'description' => $this->faker->text(),
            'price' => $this->faker->numberBetween(100, 10000),
            'stock' => $this->faker->numberBetween(100, 300),
        ];
    }
}
