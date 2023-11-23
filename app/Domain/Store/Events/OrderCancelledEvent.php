<?php

namespace App\Domain\Store\Events;

use App\Domain\Store\Dto\OrderDto;
use Illuminate\Foundation\Events\Dispatchable;

class OrderCancelledEvent
{
    use Dispatchable;

    public function __construct(protected OrderDto $orderDto)
    {
    }

    /**
     * @return OrderDto
     */
    public function getOrderDto(): OrderDto
    {
        return $this->orderDto;
    }
}
