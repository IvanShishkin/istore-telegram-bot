<?php

namespace Database\Factories;

use App\Domain\Wallets\Models\UserWalletModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;

class UserWalletFactory extends Factory
{
    protected $model = UserWalletModel::class;

    public function definition(): array
    {
        return [
            'holder_id' => $this->faker->randomNumber(),
            'balance' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'number' => Uuid::uuid4(),
        ];
    }
}
