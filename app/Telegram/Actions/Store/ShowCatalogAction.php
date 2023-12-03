<?php

declare(strict_types=1);

namespace App\Telegram\Actions\Store;

use App\Domain\Products\Services\ProductService;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

final class ShowCatalogAction
{
    public function __invoke(Nutgram $bot): void
    {
        /** @var ProductService $productService */
        $productService = \App::make(ProductService::class);
        $productList = $productService->getAll()->sortBy('price');

        if ($productList->isNotEmpty()) {
            $keyboard = InlineKeyboardMarkup::make();

            foreach ($productList as $product) {
                $productName = $product->name;
                $productPrice = $product->price;

                $productTitle = $productName . ' 💎 ' . $productPrice;
                $keyboard->addRow(InlineKeyboardButton::make(
                    text: $productTitle,
                    callback_data: 'product ' . $product->id
                ));
            }

            $bot->sendMessage(
                text: __('bot_store.catalog_listing'),
                parse_mode: 'HTML',
                reply_markup: $keyboard
            );

            $bot->deleteMessage($bot->chatId(), $bot->messageId());
        } else {
            $bot->sendMessage(__('bot_store.catalog_empty'));
        }
    }
}
