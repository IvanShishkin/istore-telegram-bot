<?php

namespace App\Domain\Wallets\Events;

use App\Domain\Wallets\Dto\WalletDto;
use Illuminate\Foundation\Events\Dispatchable;

class AccrualWalletEvent
{
    use Dispatchable;

    public function __construct(
        protected WalletDto $walletDto,
        protected int $accrualValue,
        protected ?string $comment
    )
    {
    }

    /**
     * @return WalletDto
     */
    public function getWalletDto(): WalletDto
    {
        return $this->walletDto;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @return int
     */
    public function getAccrualValue(): int
    {
        return $this->accrualValue;
    }
}
