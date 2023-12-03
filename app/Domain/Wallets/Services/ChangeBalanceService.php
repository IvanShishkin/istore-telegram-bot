<?php

declare(strict_types=1);

namespace App\Domain\Wallets\Services;

use App\Domain\Wallets\Dto\WalletLogDto;
use App\Domain\Wallets\Enums\WalletLogOperationEnum;
use App\Domain\Wallets\Exceptions\FailedSaveException;
use App\Domain\Wallets\Exceptions\IncreaseBalanceException;
use App\Domain\Wallets\Exceptions\InitializationException;
use App\Domain\Wallets\Exceptions\ReduceBalanceException;
use App\Domain\Wallets\Interfaces\WalletInterface;
use App\Domain\Wallets\Repositories\WalletLogRepository;
use App\Domain\Wallets\Repositories\WalletLogRepositoryInterface;

class ChangeBalanceService
{
    public function __construct(protected WalletLogRepositoryInterface $logRepository)
    {
    }

    /**
     * @param WalletInterface $wallet
     * @param int $value
     * @param string|null $comment
     * @return void
     * @throws FailedSaveException
     * @throws InitializationException
     * @throws IncreaseBalanceException
     */
    public function increase(WalletInterface $wallet, int $value, ?string $comment = null): void
    {
        $wallet->increase($value);

        $this->logRepository->write(
            new WalletLogDto(
                number: $wallet->getNumber(),
                operation: WalletLogOperationEnum::INCREASE,
                value: $value,
                comment: $comment
            )
        );
    }

    /**
     * @param WalletInterface $wallet
     * @param int $value
     * @param string|null $comment
     * @throws FailedSaveException
     * @throws InitializationException
     * @throws ReduceBalanceException
     */
    public function reduce(WalletInterface $wallet, int $value, ?string $comment = null): void
    {
        $wallet->reduce($value);

        $this->logRepository->write(
            new WalletLogDto(
                number: $wallet->getNumber(),
                operation: WalletLogOperationEnum::REDUCE,
                value: $value,
                comment: $comment
            )
        );
    }
}
