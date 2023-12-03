<?php

namespace Database\Factories;

use App\Domain\Transactions\Enums\TransactionStatusEnum;
use App\Domain\Transactions\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'id' => Uuid::uuid4()->toString(),
            'status' => TransactionStatusEnum::NEW,
            'value' => 100,
            'term_at' => null,
            'with_error' => false,
            'error_detail' => null,
        ];
    }
}
