<?php

declare(strict_types=1);

namespace App\Domain\Сurrency\Payments\Dto;

use Spatie\LaravelData\Data;

class CreatePaymentDto extends Data
{
    public function __construct(
        public readonly string $transactionId,
        public readonly int $amount
    )
    {
    }
}
