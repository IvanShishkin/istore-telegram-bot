<?php

namespace Database\Factories;

use App\Domain\Wallets\Models\StoreBalanceModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreBalanceFactory extends Factory
{
    protected $model = StoreBalanceModel::class;

    public function definition(): array
    {
        return [
            'balance' => 0,
            'number' => $this->faker->uuid()
        ];
    }
}
