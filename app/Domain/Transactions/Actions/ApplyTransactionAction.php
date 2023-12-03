<?php

declare(strict_types=1);

namespace App\Domain\Transactions\Actions;

use App\Domain\Transactions\Exceptions\ErrorApplyTransactionException;
use App\Domain\Transactions\Exceptions\IncorrectStatusException;
use App\Domain\Transactions\Exceptions\TransactionNotFoundException;
use App\Domain\Transactions\Services\TransactionService;
use App\Domain\Wallets\Exceptions\FailedSaveException;
use App\Domain\Wallets\Exceptions\IncreaseBalanceException;
use App\Domain\Wallets\Exceptions\InitializationException;
use App\Domain\Wallets\Interfaces\WalletInterface;
use App\Domain\Wallets\Services\ChangeBalanceService;

final class ApplyTransactionAction
{
    public function __construct(
        protected TransactionService $service,
        protected ChangeBalanceService $balanceService
    ) {
    }

    /**
     * @throws InitializationException
     * @throws FailedSaveException
     * @throws IncreaseBalanceException
     * @throws \Throwable
     * @throws TransactionNotFoundException
     * @throws ErrorApplyTransactionException
     * @throws IncorrectStatusException
     */
    public function execute(string $transactionId, ?WalletInterface $to = null, ?string $comment = null): \App\Domain\Transactions\Dto\TransactionDto
    {
        try {
            \DB::beginTransaction();

            $transactionDto = $this->service->apply($transactionId, $to);

            $this->balanceService->increase(
                wallet: $transactionDto->getTo(),
                value: $transactionDto->getValue(),
                comment: $comment
            );

            \DB::commit();

            return $transactionDto;
        } catch (\Throwable $e) {
            \DB::rollBack();

            $this->service->setError($transactionId, $e->getMessage());

            throw $e;
        }
    }
}
