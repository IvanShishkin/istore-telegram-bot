<?php

namespace App\Domain\Products\Listeners;

use App\Domain\Products\Exceptions\ErrorStockChangeException;
use App\Domain\Products\Services\ProductService;
use App\Domain\Store\Events\OrderCancelledEvent;
use App\Domain\Store\Events\OrderCreatedEvent;

class OrderIncreaseProductStockListener
{
    public function __construct(protected ProductService $productService)
    {
    }

    /**
     * @throws ErrorStockChangeException
     */
    public function handle(OrderCancelledEvent $event): void
    {
        $orderDto = $event->getOrderDto();

        $this->productService->increaseStock($orderDto->getProductId());
    }
}
