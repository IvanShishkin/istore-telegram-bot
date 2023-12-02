<?php
declare(strict_types=1);

namespace App\Telegram\Actions\Store;

use App\Domain\Products\Services\ProductService;
use App\Domain\Wallets\Services\UserWalletService;
use App\Support\Utils;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

final class ShowProductHandler
{
    public function __invoke(Nutgram $bot, $id): void
    {
        $id = (int)$id;

        $logger = Log::channel('telegram');
        $logger->info('Просмотр товара', ['user_id' => \Auth::id(), 'product_id' => $id]);

        try {
            /** @var ProductService $productService */
            $productService = \App::make(ProductService::class);
            $product = $productService->get($id);

            $imageName = (!empty($product->image_path)) ? $product->image_path : 'no-photo.png';
            $path =  Storage::disk('public')->path($imageName);

            $file = fopen($path, 'r+');

            // todo перенести в lang
            $captionMessage = PHP_EOL . '<b>' . $product->name . '</b>' . PHP_EOL . PHP_EOL;
            $captionMessage .= $product->description . PHP_EOL . PHP_EOL;
            $captionMessage .= 'Цена: ' . $product->price . '💎';

            /** @var UserWalletService $userWalletData */
            $userWalletService = \App::make(UserWalletService::class);
            $userWalletData = $userWalletService->getWalletData(\Auth::id());

            if ($userWalletData->balance > $product->price) {
                $buyButton = InlineKeyboardButton::make(
                    text: __('bot_store.button_buy'),
                    callback_data: 'buy ' . $product->id
                );
            } else {
                $buyButton = InlineKeyboardButton::make(
                    text: __('bot_store.button_low_balance'),
                    callback_data: 'low_balance'
                );
            }

            $keyboard = InlineKeyboardMarkup::make()
                ->addRow($buyButton)
                ->addRow(InlineKeyboardButton::make(
                    text: __('bot_common.button_back'),
                    callback_data: 'show_catalog'
                ));

            $bot->sendPhoto(
                photo: InputFile::make($file),
                caption: $captionMessage,
                parse_mode: 'HTML',
                reply_markup: $keyboard
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
