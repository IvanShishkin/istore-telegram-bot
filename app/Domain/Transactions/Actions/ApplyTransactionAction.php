<?php
declare(strict_types=1);

namespace App\Domain\Transactions\Actions;

use App\Domain\Transactions\Services\TransactionService;
use App\Domain\Wallets\Interfaces\WalletInterface;
use App\Domain\Wallets\Services\ChangeBalanceService;

final class ApplyTransactionAction
{
    public function __construct(
        protected TransactionService $service,
        protected ChangeBalanceService $balanceService
    ) {
    }

    public function execute(string $transactionId, ?WalletInterface $to = null)
    {
        try {
            \DB::beginTransaction();

            $transactionDto = $this->service->apply($transactionId, $to);

            $this->balanceService->increase(
                $transactionDto->getTo(),
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
