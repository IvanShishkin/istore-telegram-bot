<?php

namespace Database\Factories;

use App\Domain\Transactions\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'status' => 'new',
            'value' => 100,
            'term_at' => Carbon::now(),
            'with_error' => false,
            'error_detail' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'lifetime_at' => null,
        ];
    }
}
