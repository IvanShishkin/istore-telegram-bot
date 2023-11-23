<?php
declare(strict_types=1);

namespace App\Domain\Store\Events;

use App\Domain\Store\Dto\OrderDto;
use Illuminate\Foundation\Events\Dispatchable;

class OrderProcessedEvent
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
