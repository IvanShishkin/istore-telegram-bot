<?php
declare(strict_types=1);

namespace App\Domain\Store\Actions;

use App\Domain\Store\Events\OrderProcessedEvent;
use App\Domain\Store\Exceptions\ErrorOrderActionException;
use App\Domain\Store\Services\OrderStatusService;
use App\Domain\Transactions\Actions\ApplyTransactionAction;
use Illuminate\Support\Facades\DB;

final class ProcessedOrderAction
{
    public function __construct(
        protected OrderStatusService $orderStatusService,
        protected ApplyTransactionAction $applyTransactionAction
    ) {
    }

    /**
     * @throws ErrorOrderActionException
     * @throws \Throwable
     */
    public function execute(int $orderId): void
    {
        try {
            DB::beginTransaction();

            $orderData = $this->orderStatusService->processed($orderId);

            $this->applyTransactionAction->execute($orderData->getTransactionId());

            DB::commit();

            OrderProcessedEvent::dispatch($orderData);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new ErrorOrderActionException("Ошибка выполнения " . __CLASS__ . ". Детально: {$e->getMessage()}");
        }
    }
}
