<?php

namespace Database\Factories;

use App\Domain\Wallets\Models\UserWalletModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAccountFactory extends Factory
{
    protected $model = UserWalletModel::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'balance' => $this->faker->randomNumber(),
        ];
    }
}
