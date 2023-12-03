<?php
declare(strict_types=1);

namespace App\Domain\Transactions\Services;

use App\Domain\Transactions\Actions\CancelTransactionAction;
use App\Domain\Transactions\Enums\TransactionStatusEnum;
use App\Domain\Transactions\Exceptions\IncorrectStatusException;
use App\Domain\Transactions\Exceptions\TransactionNotFoundException;
use App\Domain\Transactions\Models\Transaction;
use App\Domain\Wallets\Exceptions\FailedSaveException;
use App\Domain\Wallets\Exceptions\InitializationException;
use App\Support\Logger\TransactionLoggerInterface;
use Carbon\Carbon;
use Psr\Log\LoggerAwareTrait;

final class TerminateService implements TransactionLoggerInterface
{
    use LoggerAwareTrait;

    public function __construct(protected CancelTransactionAction $action)
    {
    }

    public function getList(): array
    {
        $transactions = Transaction::where([
            'status' => TransactionStatusEnum::NEW,
        ])->where('term_at', '<', Carbon::now())->get();

        return $transactions->pluck('id')->toArray();
    }

    public function exec(): void
    {
        $list = $this->getList();

        $this->logger?->info('Начало обработки операции деактивация просроченных транзакций', [
            'list' => $list
        ]);

        if (!empty($list)) {
            foreach ($list as $transactionId) {
                try {
                    $this->action->execute($transactionId);

                    $this->logger?->info('Транзакция {transaction_id} отменена', [
                        'transaction_id' => $transactionId
                    ]);
                } catch (
                IncorrectStatusException
                | TransactionNotFoundException
                | FailedSaveException
                | InitializationException $e) {
                    $this->logger?->critical('Логическая ошибка отмены транзакции', [
                        'exception' => $e,
                        'transaction_id' => $transactionId
                    ]);
                } catch (\Throwable $e) {
                    $this->logger?->emergency('Фатальная ошибка отмены транзакции', [
                        'exception' => $e,
                        'transaction_id' => $transactionId
                    ]);
                }
            }
        }
    }
}
