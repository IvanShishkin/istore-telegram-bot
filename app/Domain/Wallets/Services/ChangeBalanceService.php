<?php
declare(strict_types=1);

namespace App\Domain\Wallets\Services;

use App\Domain\Wallets\Dto\WalletLogDto;
use App\Domain\Wallets\Enums\WalletLogOperationEnum;
use App\Domain\Wallets\Exceptions\FailedSaveException;
use App\Domain\Wallets\Exceptions\InitializationException;
use App\Domain\Wallets\Exceptions\ReduceBalanceException;
use App\Domain\Wallets\Interfaces\WalletInterface;
use App\Domain\Wallets\Repositories\WalletLogRepository;

class ChangeBalanceService
{
    public function __construct(protected WalletLogRepository $logRepository)
    {
    }

    /**
     * @param WalletInterface $wallet
     * @param int $value
     * @return void
     * @throws FailedSaveException
     * @throws InitializationException
     */
    public function increase(WalletInterface $wallet, int $value): void
    {
        $wallet->increase($value);

        $this->logRepository->write(
            new WalletLogDto(
                number: $wallet->getNumber(),
                operation: WalletLogOperationEnum::INCREASE,
                value: $value
            )
        );
    }

    /**
     * @throws InitializationException
     * @throws ReduceBalanceException
     * @throws FailedSaveException
     */
    public function reduce(WalletInterface $wallet, int $value): void
    {
        $wallet->reduce($value);

        $this->logRepository->write(
            new WalletLogDto(
                number: $wallet->getNumber(),
                operation: WalletLogOperationEnum::REDUCE,
                value: $value
            )
        );
    }
}
