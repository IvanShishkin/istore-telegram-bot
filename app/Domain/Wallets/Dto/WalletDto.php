<?php

declare(strict_types=1);

namespace App\Domain\Wallets\Dto;

use Spatie\LaravelData\Data;

class WalletDto extends Data
{
    public function __construct(
        public string $number,
        public int $balance,
        public ?int $holder_id = null
    ) {
    }
}
