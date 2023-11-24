<?php

namespace Database\Factories;

use App\Domain\Store\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'status' => $this->faker->word(),
            'canceled' => false,
            'product_id' => $this->faker->randomNumber(),
            'price' => $this->faker->randomNumber(),
            'transaction_id' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
