<?php

declare(strict_types=1);

namespace App\Domain\Wallets\Actions;

use App\Domain\Transactions\Actions\ApplyTransactionAction;
use App\Domain\Transactions\Actions\CreateTransactionAction;
use App\Domain\Transactions\Dto\TransactionDto;
use App\Domain\Wallets\Events\AccrualWalletEvent;
use App\Domain\Wallets\Exceptions\FailedSaveException;
use App\Domain\Wallets\Exceptions\IncreaseBalanceException;
use App\Domain\Wallets\Exceptions\InitializationException;
use App\Domain\Wallets\Exceptions\ReduceBalanceException;
use App\Domain\Wallets\Exceptions\WalletNotExistsException;
use App\Domain\Wallets\Interfaces\WalletInterface;
use App\Domain\Wallets\Services\ChangeBalanceService;
use App\Domain\Wallets\Services\StoreWalletService;
use App\Domain\Wallets\Services\UserWalletService;
use App\Domain\Wallets\UserWallet;

final class AccrualWalletAction
{
    public function __construct(
        protected ChangeBalanceService $balanceService,
        protected UserWalletService $userWalletService,
        protected StoreWalletService $storeWalletService,
        protected CreateTransactionAction $createTransactionAction,
        protected ApplyTransactionAction $applyTransactionAction
    ) {
    }

    /**
     * @throws InitializationException
     * @throws IncreaseBalanceException
     * @throws FailedSaveException
     * @throws WalletNotExistsException
     */
    public function execute(string $walletNumber, int $value, ?string $comment = null): void
    {
        try {
            \DB::beginTransaction();

            $wallet = new UserWallet($walletNumber);

            $storeWallet = $this->storeWalletService->initWallet();


            $transactionId = $this->createTransactionAction->execute(new TransactionDto(
                from: $storeWallet,
                value: $value,
                to: $wallet,
                comment: $comment
            ));

            $this->applyTransactionAction->execute($transactionId);

            $walletDto = $this->userWalletService->getDataByNumber($walletNumber);

            \DB::commit();

            AccrualWalletEvent::dispatch($walletDto, $value, $comment);
        } catch (\Throwable $e) {
            \DB::rollBack();

            throw $e;
        }
    }
}
