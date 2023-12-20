<?php

declare(strict_types=1);

namespace App\Domain\Сurrency\Payments\Dto;

use App\Domain\Сurrency\Payments\Enums\PaymentStatusEnum;
use Spatie\LaravelData\Data;

class PaymentDto extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $transaction_id,
        public readonly int $amount,
        public readonly PaymentStatusEnum $status,
        public readonly ?string $link,
    )
    {
    }
}
