<?php
declare(strict_types=1);

namespace App\Domain\Store\Dto;

use App\Domain\Store\Enums\OrderStatusEnum;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class OrderDto extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly int $user_id,
        public readonly OrderStatusEnum $status,
        public readonly int $product_id,
        public readonly int $price,
        public readonly string $transaction_id,
        public readonly string $created_at
    )
    {
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @return OrderStatusEnum
     */
    public function getStatus(): OrderStatusEnum
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->product_id;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transaction_id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
