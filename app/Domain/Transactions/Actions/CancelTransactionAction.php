<?php

declare(strict_types=1);

namespace App\Domain\Transactions\Actions;

use App\Domain\Transactions\Exceptions\IncorrectStatusException;
use App\Domain\Transactions\Exceptions\TransactionNotFoundException;
use App\Domain\Transactions\Services\TransactionService;
use App\Domain\Wallets\Exceptions\FailedSaveException;
use App\Domain\Wallets\Exceptions\InitializationException;
use App\Domain\Wallets\Services\ChangeBalanceService;

final class CancelTransactionAction
{
    public function __construct(
        protected TransactionService $service,
        protected ChangeBalanceService $balanceService
    ) {
    }

    /**
     * @param string $transactionId
     * @return void
     * @throws IncorrectStatusException
     * @throws TransactionNotFoundException
     * @throws FailedSaveException
     * @throws InitializationException
     * @throws \Throwable
     */
    public function execute(string $transactionId): void
    {
        try {
            \DB::beginTransaction();

            $transactionDto = $this->service->cancel($transactionId);

            $this->balanceService->increase(
                $transactionDto->getFrom(),
                $transactionDto->getValue()
            );

            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();

            $this->service->setError($transactionId, $e->getMessage());

            throw $e;
        }
    }
}
