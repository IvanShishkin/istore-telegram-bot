<?php

namespace App\Domain\Products\Listeners;

use App\Domain\Products\Exceptions\ErrorStockChangeException;
use App\Domain\Products\Services\ProductService;
use App\Domain\Store\Events\OrderCancelledEvent;
use App\Domain\Store\Events\OrderCreatedEvent;
use App\Domain\Store\Services\OrderService;

class OrderReduceProductStockListener
{
    public function __construct(protected ProductService $productService)
    {
    }

    /**
     * @throws ErrorStockChangeException
     */
    public function handle(OrderCreatedEvent $event): void
    {
        $orderDto = $event->getOrderDto();

        $this->productService->reduceStock($orderDto->getProductId());
    }
}
