<?php

declare(strict_types=1);

namespace App\Domain\Transactions\Services;

use App\Domain\Transactions\Dto\TransactionDto;
use App\Domain\Transactions\Enums\TransactionDirectionEnum;
use App\Domain\Transactions\Enums\TransactionStatusEnum;
use App\Domain\Transactions\Exceptions\ErrorApplyTransactionException;
use App\Domain\Transactions\Exceptions\IncorrectStatusException;
use App\Domain\Transactions\Exceptions\TransactionNotFoundException;
use App\Domain\Transactions\Models\Transaction;
use App\Domain\Transactions\Models\TransactionItem;
use App\Domain\Wallets\Interfaces\WalletInterface;
use App\Domain\Wallets\WalletFactory;
use Ramsey\Uuid\Uuid;

class TransactionService
{
    public function create(TransactionDto $dto): string
    {
        $transactionId = Uuid::uuid4();

        Transaction::create([
            'id' => $transactionId,
            'status' => TransactionStatusEnum::NEW,
            'value' => $dto->getValue(),
            'term_at' => $dto->getTermAt()
        ]);

        TransactionItem::create([
            'transaction_id' => $transactionId,
            'wallet_number' => $dto->getFrom()->getNumber(),
            'direction' => TransactionDirectionEnum::FROM,
            'type' => $dto->getFrom()->getType()
        ]);

        if ($dto->getTo()) {
            TransactionItem::create([
                'transaction_id' => $transactionId,
                'wallet_number' => $dto->getTo()->getNumber(),
                'direction' => TransactionDirectionEnum::TO,
                'type' => $dto->getTo()->getType()
            ]);
        }

        return $transactionId->toString();
    }

    /**
     * @throws TransactionNotFoundException
     * @throws IncorrectStatusException
     */
    public function cancel(string $transactionId): TransactionDto
    {
        $transaction = $this->getModel($transactionId);

        if ($transaction->status !== TransactionStatusEnum::NEW) {
            throw new IncorrectStatusException('Не удалось отменить транзакцию. Неверный статус');
        }

        $transactionItems = TransactionItem::where(['transaction_id' => $transactionId])->get();

        $direction = [
            TransactionDirectionEnum::FROM->value => null,
            TransactionDirectionEnum::TO->value => null,
        ];

        $walletFactory = new WalletFactory();

        foreach ($transactionItems as $item) {
            $direction[$item->direction->value] = $walletFactory->make($item->wallet_number, $item->type);
        }

        $transaction->status = TransactionStatusEnum::CLOSED;
        $transaction->save();

        return new TransactionDto(
            from: $direction[TransactionDirectionEnum::FROM->value],
            value: $transaction->value,
            to: $direction[TransactionDirectionEnum::TO->value],
            status: $transaction->status
        );
    }

    /**
     * @throws IncorrectStatusException
     * @throws TransactionNotFoundException
     * @throws ErrorApplyTransactionException
     */
    public function apply(string $transactionId, ?WalletInterface $wallet = null): TransactionDto
    {
        $transaction = $this->getModel($transactionId);

        if ($transaction->status !== TransactionStatusEnum::NEW) {
            throw new IncorrectStatusException('Не удалось применить транзакцию. Неверный статус');
        }

        $transactionItems = TransactionItem::where(['transaction_id' => $transactionId])->get();

        $direction = [
            TransactionDirectionEnum::FROM->value => null,
            TransactionDirectionEnum::TO->value => null,
        ];

        $walletFactory = new WalletFactory();

        foreach ($transactionItems as $item) {
            $direction[$item->direction->value] = $walletFactory->make($item->wallet_number, $item->type);
        }

        if (empty($direction[TransactionDirectionEnum::TO->value]) && !empty($wallet)) {
            TransactionItem::create([
                'transaction_id' => $transactionId,
                'wallet_number' => $wallet->getNumber(),
                'type' => $wallet->getType(),
                'direction' => TransactionDirectionEnum::TO
            ]);

            $direction[TransactionDirectionEnum::TO->value] = $wallet;
        }

        if (empty($direction[TransactionDirectionEnum::TO->value])) {
            throw new ErrorApplyTransactionException('Не удалось применить транзакцию. Не указан получатель');
        }

        $transaction->status = TransactionStatusEnum::COMPLETED;
        $transaction->save();

        return new TransactionDto(
            from: $direction[TransactionDirectionEnum::FROM->value],
            value: $transaction->value,
            to: $direction[TransactionDirectionEnum::TO->value],
            status: $transaction->status
        );
    }

    /**
     * @throws TransactionNotFoundException
     */
    public function setError(string $transactionId, ?string $errorDetail): void
    {
        $transaction = $this->getModel($transactionId);

        $transaction->with_error = true;
        $transaction->error_detail = $errorDetail;
        $transaction->save();
    }

    /**
     * @throws TransactionNotFoundException
     */
    protected function getModel(string $transactionId): Transaction
    {
        $transaction = Transaction::where(['id' => $transactionId])->lockForUpdate()->first();

        if (!$transaction) {
            throw new TransactionNotFoundException('Транзакция не найдена');
        }

        return $transaction;
    }
}
