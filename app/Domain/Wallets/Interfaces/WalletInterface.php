<?php

namespace App\Domain\Wallets\Interfaces;

use App\Domain\Wallets\Enums\WalletTypesEnum;
use App\Domain\Wallets\Exceptions\FailedSaveException;
use App\Domain\Wallets\Exceptions\IncreaseBalanceException;
use App\Domain\Wallets\Exceptions\InitializationException;
use App\Domain\Wallets\Exceptions\ReduceBalanceException;

interface WalletInterface
{
    public function __construct(string $uuid);

    /**
     * @param int $value
     * @return void
     *
     * @throws InitializationException
     * @throws FailedSaveException
     * @throws IncreaseBalanceException
     */
    public function increase(int $value): void;

    /**
     * @param int $value
     * @return void
     *
     * @throws FailedSaveException
     * @throws InitializationException
     * @throws ReduceBalanceException
     */
    public function reduce(int $value): void;

    /**
     * @return int
     * @throws InitializationException
     */
    public function balance(): int;

    public function getNumber(): string;

    public function getType(): ?WalletTypesEnum;
}
