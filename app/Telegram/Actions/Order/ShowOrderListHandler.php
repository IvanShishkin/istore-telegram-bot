<?php
declare(strict_types=1);

namespace App\Telegram\Actions\Order;

use App\Domain\Products\Exceptions\NotFoundException;
use App\Domain\Products\Services\ProductService;
use App\Domain\Store\Enums\OrderStatusEnum;
use App\Domain\Store\Services\OrderService;
use App\Support\Utils;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use SergiX44\Nutgram\Nutgram;

/**
 * Показ списка заказов пользователя отсортированных по приоритету статуса
 */
final class ShowOrderListHandler
{
    public function __invoke(Nutgram $bot): void
    {
        $logger = \Log::channel('telegram');
        $logger->info('Вызов просмотра заказов', ['user_id' => Auth::id()]);

        try {
            $orderService = \App::make(OrderService::class);
            $productService = \App::make(ProductService::class);

            $orderCollection = $orderService->getListForUser(Auth::id());

            if ($orderCollection->isEmpty()) {
                $bot->sendMessage('Список заказов пуст');
                return;
            }

            // нужно будет сгруппировать по статусу и выводить в порядке важности статусов
            $orders = [
                OrderStatusEnum::IN_PROCESSING->value => [],
                OrderStatusEnum::NEW->value => [],
                OrderStatusEnum::PROCESSED->value => [],
                OrderStatusEnum::CANCEL->value => []
            ];

            foreach ($orderCollection as $item) {
                try {
                    $productDto = $productService->get($item->product_id);
                    $productName = $productDto->name;
                } catch (NotFoundException) {
                    $productName = '<Неизвестный товар>';
                }

                $orders[$item->status->value][] = [
                    'id' => $item->id,
                    'product_name' => $productName,
                    'price' => $item->price,
                    'created_at' => date('Y-m-d H:i', strtotime($item->created_at))
                ];
            }

            $message = '';

            foreach ($orders as $orderStatusValue => $orderStatusGroup) {
                if (!empty($orderStatusGroup)) {
                    $enumCase = OrderStatusEnum::from($orderStatusValue);
                    $message .= PHP_EOL . $enumCase->icon() . ' <b>' . $enumCase->label() . '</b>' . PHP_EOL . PHP_EOL;

                    foreach ($orderStatusGroup as $orderItem) {
                        $message .= '<b>Заказ №'. $orderItem['id'] . ' от ' . $orderItem['created_at'] . '</b>' . PHP_EOL;

                        $message .= 'товар: ' . $orderItem['product_name'] . PHP_EOL;
                        $message .= 'цена: ' . $orderItem['price'] . '💎' . PHP_EOL . PHP_EOL;
                    }
                }
            }

            $bot->sendMessage(
                text: $message,
                parse_mode: 'HTML'
            );
        } catch (\Throwable $exception) {
            $errorCode = Utils::errorInitializationCode();

            $logger->critical('Фатальная ошибка', [
                'exception' => $exception,
                'error_code' => $errorCode
            ]);

            $bot->sendMessage(
                text: __('bot_common.error_default', ['error_code' => $errorCode]),
                parse_mode: 'HTML'
            );
        }
    }
}
