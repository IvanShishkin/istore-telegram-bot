<?php

namespace Tests\Unit\Domain\Traits;

use App\Domain\Store\Models\Order;
use App\Domain\Transactions\Models\Transaction;
use App\Domain\User\Models\User;

trait OrderMockTrait
{
    protected function mockOrder(): Order
    {
        /** @var User $user */
        $user = User::factory()->create()->first();
        /** @var Transaction $transaction */
        $transaction = Transaction::factory()->create()->first();

        return Order::factory()->create([
            'user_id' => $user->id,
            'transaction_id' => $transaction->id
        ])->first();
    }
}
