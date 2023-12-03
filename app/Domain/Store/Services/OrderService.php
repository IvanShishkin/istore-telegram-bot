<?php
declare(strict_types=1);

namespace App\Domain\Store\Services;

use App\Domain\Products\Dto\ProductDto;
use App\Domain\Store\Dto\OrderDto;
use App\Domain\Store\Enums\OrderStatusEnum;
use App\Domain\Store\Exceptions\ErrorCreateOrderException;
use App\Domain\Store\Exceptions\OrderNotExistsException;
use App\Domain\Store\Models\Order;
use App\Domain\User\Dto\UserDto;
use Illuminate\Support\Collection;

class OrderService
{

    /**
     * @param UserDto $userDto
     * @param ProductDto $productDto
     * @param string $transactionId
     * @return OrderDto
     * @throws ErrorCreateOrderException
     */
    public function create(
        UserDto $userDto,
        ProductDto $productDto,
        string $transactionId
    ): OrderDto
    {
        $model = Order::create([
            'user_id' => $userDto->id,
            'status' => OrderStatusEnum::NEW,
            'product_id' => $productDto->id,
            'price' => $productDto->price,
            'transaction_id' => $transactionId,
        ]);

        if (!$model) {
            throw new ErrorCreateOrderException('Ошибка создания заказа');
        }

        return self::makeDto($model);
    }

    /**
     * @param int $id
     * @return OrderDto
     * @throws OrderNotExistsException
     */
    public function byId(int $id): OrderDto
    {
        $model = $this->getModel($id);

        if (!$model) {
            throw new OrderNotExistsException("Заказ id={$id} не найден");
        }

        return self::makeDto($model);
    }

    /**
     * @param int $userId
     * @return ?Collection<OrderDto>
     */
    public function getListForUser(int $userId): ?Collection
    {
        $list = Order::where(['user_id' => $userId])->get();

        return $list->map(fn(Order $order) => self::makeDto($order));
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
