<?php

declare(strict_types=1);

namespace App\Telegram\Actions\Store;

use App\Domain\Products\Services\ProductService;
use App\Domain\Store\Actions\CreateOrderAction;
use App\Domain\User\Services\UserService;
use App\Support\Utils;
use Illuminate\Support\Facades\Auth;
use SergiX44\Nutgram\Nutgram;

final class BuyProductHandler
{
    public function __invoke(Nutgram $bot, $productId): void
    {
        $productId = (int)$productId;

        $logger = \Log::channel('telegram');
        $logger->info('Вызов покупка товара', ['user_id' => Auth::id(), 'product_id' => $productId]);

        try {
            /** @var CreateOrderAction $orderAction */
            $orderAction = \App::make(CreateOrderAction::class);
            $productService = \App::make(ProductService::class);
            $userService = \App::make(UserService::class);

            $orderDto = $orderAction->execute(
                userDto: $userService->byId(\Auth::id()),
                productDto: $productService->get($productId)
            );

            $bot->sendMessage(
                text: __('bot_store.order_created', ['number' => $orderDto->id]),
                parse_mode: 'HTML'
            );
            $bot->deleteMessage($bot->chatId(), $bot->messageId());
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
