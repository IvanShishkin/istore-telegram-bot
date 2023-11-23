<?php

namespace Database\Factories;

use App\Domain\Wallets\Models\WalletUuid;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;

class WalletUuidFactory extends Factory
{
    protected $model = WalletUuid::class;

    public function definition(): array
    {
        return [
            'uuid' => Uuid::uuid4(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
