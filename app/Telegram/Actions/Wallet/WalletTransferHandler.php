<?php
declare(strict_types=1);

namespace App\Telegram\Actions\Wallet;

use App\Domain\Wallets\Services\UserWalletService;
use App\Support\Utils;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

final class WalletTransferHandler
{
    public const DEFINE_TRANSFER_VALUES = [
        100,
        200,
        500,
        1000,
        2000,
        5000
    ];

    public function __invoke(Nutgram $bot): void
    {
        $logger = Log::channel('telegram');
        $logger->info('Создание перевода', ['user_id' => \Auth::id()]);

        /** @var UserWalletService $userWalletService */
        $userWalletService = \App::make(UserWalletService::class);

        try {
            $wallet = $userWalletService->getWalletData(\Auth::id());
            $keyboard = InlineKeyboardMarkup::make();

            foreach (self::DEFINE_TRANSFER_VALUES as $value) {
                if ($wallet->balance >= $value) {
                    $keyboard->addRow(InlineKeyboardButton::make(
                        text: (string)$value . '💎',
                        callback_data: 'make_transaction ' . $value
                    ));
                }
            }

            $keyboard->addRow(InlineKeyboardButton::make(text: __('bot_common.button_back'), callback_data: 'show_wallet'));

            $bot->editMessageText(
                text: __('bot_wallet.transfer_info'),
                parse_mode: 'HTML',
                reply_markup: $keyboard
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
