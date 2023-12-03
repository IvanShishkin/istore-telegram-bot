<?php

declare(strict_types=1);

namespace App\Domain\Store\Services;

use App\Domain\Store\Dto\OrderDto;
use App\Domain\Store\Enums\OrderStatusEnum;
use App\Domain\Store\Exceptions\OrderChangeStatusException;
use App\Domain\Store\Models\Order;

final class OrderStatusService
{
    protected const ORDER_STATUS_MAP = [
        OrderStatusEnum::IN_PROCESSING->value => [
            OrderStatusEnum::NEW
        ],
        OrderStatusEnum::PROCESSED->value => [
            OrderStatusEnum::NEW,
            OrderStatusEnum::IN_PROCESSING
        ],
        OrderStatusEnum::CANCEL->value => [
            OrderStatusEnum::NEW,
            OrderStatusEnum::IN_PROCESSING
        ]
    ];

    /**
     * @param int $id
     * @return OrderDto
     * @throws OrderChangeStatusException
     */
    public function cancel(int $id): OrderDto
    {
        $model = $this->getModel($id);

        $changedModel = $this->changeStatus($model, OrderStatusEnum::CANCEL);

        return self::makeDto($changedModel);
    }

    /**
     * @param int $id
     * @return OrderDto
     * @throws OrderChangeStatusException
     */
    public function inProcessing(int $id): OrderDto
    {
        $model = $this->getModel($id);

        $changedModel = $this->changeStatus($model, OrderStatusEnum::IN_PROCESSING);

        return self::makeDto($changedModel);
    }

    /**
     * @param int $id
     * @return OrderDto
     * @throws OrderChangeStatusException
     */
    public function processed(int $id): OrderDto
    {
        $model = $this->getModel($id);

        $changedModel = $this->changeStatus($model, OrderStatusEnum::PROCESSED);

        return self::makeDto($changedModel);
    }

    /**
     * @throws OrderChangeStatusException
     */
    protected function changeStatus(Order $order, OrderStatusEnum $newStatus): Order
    {
        if (!array_key_exists($newStatus->value, self::ORDER_STATUS_MAP)) {
            throw new OrderChangeStatusException('Не известный статус для перехода');
        }

        $currentStatus = $order->status;
        $allowStatus = self::ORDER_STATUS_MAP[$newStatus->value];

        if (!in_array($currentStatus, $allowStatus)) {
            throw new OrderChangeStatusException("Ошибка изменения статуса. Не возможен переход из {$currentStatus->value} в {$newStatus->value}");
        }

        $order->status = $newStatus;
        $order->save();

        return $order;
    }

    protected function getModel(int $id): ?Order
    {
        return Order::whereId($id)->first();
    }

    protected static function makeDto(Order $model): OrderDto
    {
        return OrderDto::from($model->toArray());
    }
}
