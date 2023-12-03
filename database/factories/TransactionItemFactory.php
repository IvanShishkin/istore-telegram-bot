<?php

namespace Database\Factories;

use App\Domain\Transactions\Enums\TransactionDirectionEnum;
use App\Domain\Transactions\Models\TransactionItem;
use App\Domain\Wallets\Enums\WalletTypesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TransactionItemFactory extends Factory
{
    protected $model = TransactionItem::class;

    public function definition(): array
    {
        return [
            'transaction_id' => $this->faker->word(),
            'wallet_number' => $this->faker->word(),
            'direction' => TransactionDirectionEnum::FROM,
            'type' => WalletTypesEnum::USER,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
