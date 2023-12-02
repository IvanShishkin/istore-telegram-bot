<?php
declare(strict_types=1);

namespace App\Domain\Transactions\Dto;

use App\Domain\Transactions\Enums\TransactionStatusEnum;
use App\Domain\Wallets\Interfaces\WalletInterface;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class TransactionDto extends Data
{
    public function __construct(
        public WalletInterface $from,
        public int $value,
        public ?WalletInterface $to = null,
        public ?Carbon $term_at = null,
        public ?string $comment = null,
    )
    {
    }

    /**
     * @return WalletInterface
     */
    public function getFrom(): WalletInterface
    {
        return $this->from;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return Carbon|null
     */
    public function getTermAt(): ?Carbon
    {
        return $this->term_at;
    }

    /**
     * @return WalletInterface|null
     */
    public function getTo(): ?WalletInterface
    {
        return $this->to;
    }
}
