<?php

declare(strict_types=1);

namespace App\Domain\Store\Actions;

use App\Domain\Store\Events\OrderInProcessedEvent;
use App\Domain\Store\Exceptions\ErrorOrderActionException;
use App\Domain\Store\Services\OrderStatusService;
use Illuminate\Support\Facades\DB;

final class InProcessingOrderAction
{
    public function __construct(protected OrderStatusService $orderStatusService)
    {
    }

    /**
     * @throws \Throwable
     * @throws ErrorOrderActionException
     */
    public function execute(int $orderId): void
    {
        try {
            DB::beginTransaction();

            $orderData = $this->orderStatusService->inProcessing($orderId);

            //todo event
            DB::commit();

            OrderInProcessedEvent::dispatch($orderData);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new ErrorOrderActionException("Ошибка выполнения " . __CLASS__ . ". Детально: {$e->getMessage()}");
        }
    }
}
