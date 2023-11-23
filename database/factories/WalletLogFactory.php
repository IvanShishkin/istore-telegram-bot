<?php

namespace Database\Factories;

use App\Domain\Wallets\Models\WalletLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class WalletLogFactory extends Factory
{
    protected $model = WalletLog::class;

    public function definition(): array
    {
        return [
            'number' => $this->faker->word(),
            'operation' => $this->faker->word(),
            'value' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
