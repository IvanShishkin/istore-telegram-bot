<?php
declare(strict_types=1);

namespace App\Domain\Wallets\Dto;

use App\Domain\Wallets\Enums\WalletLogOperationEnum;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class WalletLogDto extends Data
{
    public function __construct(
        public string $number,
        public WalletLogOperationEnum $operation,
        public int $value,
        public ?Carbon $created_at = null,
    )
    {
    }
}
