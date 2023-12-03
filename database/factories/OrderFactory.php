<?php

namespace Database\Factories;

use App\Domain\Store\Enums\OrderStatusEnum;
use App\Domain\Store\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'status' => OrderStatusEnum::NEW,
            'product_id' => $this->faker->randomNumber(),
            'price' => $this->faker->randomNumber(),
            'transaction_id' => Uuid::uuid4()->toString(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
