<?php

declare(strict_types=1);

namespace App\Domain\Store\Actions;

use App\Domain\Store\Events\OrderCancelledEvent;
use App\Domain\Store\Exceptions\ErrorOrderActionException;
use App\Domain\Store\Services\OrderStatusService;
use App\Domain\Transactions\Actions\CancelTransactionAction;
use Illuminate\Support\Facades\DB;

final class CancelOrderAction
{
    public function __construct(
        protected OrderStatusService $orderStatusService,
        protected CancelTransactionAction $cancelTransactionAction
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

            $orderData = $this->orderStatusService->cancel($orderId);

            $comment = "Возврат за отмену заказа №{$orderData->getId()}";

            $this->cancelTransactionAction->execute(
                $orderData->getTransactionId(),
                $comment
            );

            DB::commit();

            OrderCancelledEvent::dispatch($orderData);
        } catch (\Throwable $e) {
            DB::rollBack();

            throw new ErrorOrderActionException("Ошибка выполнения " . __CLASS__ . ". Детально: {$e->getMessage()}");
        }
    }
}
