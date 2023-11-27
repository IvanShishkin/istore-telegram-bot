<?php
declare(strict_types=1);

namespace App\Domain\Wallets\Dto;

use App\Domain\Wallets\Enums\WalletLogOperationEnum;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class WalletLogDto extends Data
{
    public function __construct(
        public readonly string $number,
        public readonly WalletLogOperationEnum $operation,
        public readonly int $value,
        public readonly ?Carbon $created_at = null,
        public readonly ?string $comment = null,
    )
    {
    }
}
