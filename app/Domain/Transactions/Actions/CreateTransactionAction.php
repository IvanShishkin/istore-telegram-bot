<?php
declare(strict_types=1);

namespace App\Domain\Transactions\Actions;

use App\Domain\Transactions\Dto\TransactionDto;
use App\Domain\Transactions\TransactionService;
use App\Domain\Wallets\Exceptions\FailedSaveException;
use App\Domain\Wallets\Exceptions\InitializationException;
use App\Domain\Wallets\Exceptions\ReduceBalanceException;
use App\Domain\Wallets\Services\ChangeBalanceService;

final class CreateTransactionAction
{
    public function __construct(
        protected ChangeBalanceService $balanceService,
        protected TransactionService $transactionService
    )
    {
    }

    /**
     * @param TransactionDto $dto
     * @return void
     * @throws FailedSaveException
     * @throws InitializationException
     * @throws ReduceBalanceException
     * @throws \Throwable
     */
    public function execute(TransactionDto $dto): string
    {
        try {
            \DB::beginTransaction();

            $this->balanceService->reduce(
                $dto->getFrom(),
                $dto->getValue()
            );

            $transactionId = $this->transactionService->create($dto);

            \DB::commit();

            return $transactionId;
        } catch (\Throwable $e) {
            \DB::rollBack();
            throw $e;
        }
    }
}
