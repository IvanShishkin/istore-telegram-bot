<?php

declare(strict_types=1);

namespace App\Telegram\Actions\Wallet;

use App\Domain\Wallets\Enums\WalletLogOperationEnum;
use App\Domain\Wallets\Exceptions\WalletNotExistsException;
use App\Domain\Wallets\Repositories\WalletLogRepositoryInterface;
use App\Domain\Wallets\Services\UserWalletService;
use Illuminate\Support\Facades\Auth;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class ShowWalletHandler
{
    public function __invoke(Nutgram $bot): void
    {
        $logger = \Log::channel('telegram');
        $logger->info('Показать кошелек пользователя', ['user_id' => Auth::id()]);

        /** @var UserWalletService $userWalletService */
        $userWalletService = \App::make(UserWalletService::class);

        try {
            $wallet = $userWalletService->getWalletData(\Auth::id());

            /** @var WalletLogRepositoryInterface $walletLogs */
            $walletLogRepository = \App::make(WalletLogRepositoryInterface::class);
            $walletLogs = $walletLogRepository->get($wallet->number, 10);

            if ($walletLogs->isNotEmpty()) {
                $walletLogsMessage = 'Последние 10 операций:' . PHP_EOL . PHP_EOL;
                foreach ($walletLogs as $logRecord) {
                    $operationIcon = ($logRecord->operation === WalletLogOperationEnum::INCREASE) ? '🟩 +' : '🟥 -' ;
                    $comment = ($logRecord->comment) ? " ($logRecord->comment)" : '';
                    $walletLogsMessage .= $logRecord->created_at . ' ' . $operationIcon . ' ' .  $logRecord->value . $comment . PHP_EOL;
                }
            } else {
                $walletLogsMessage = 'Нет операций';
            }

            $message = '<b>Мой кошелек</b>' . PHP_EOL . PHP_EOL;
            $message .= 'Номер: ' . $wallet->number . PHP_EOL . PHP_EOL;
            $message .= 'Баланс: ' . $wallet->balance . '💎' . PHP_EOL . PHP_EOL;
            $message .= $walletLogsMessage;

            $keyboard = InlineKeyboardMarkup::make();

            if ($wallet->balance > 100) {
                $keyboard->addRow(InlineKeyboardButton::make(
                    text: __('bot_wallet.button_transfer'),
                    callback_data: 'wallet_transfer'
                ));
            }

            $keyboard->addRow(InlineKeyboardButton::make(
                text: __('bot_wallet.button_fill'),
                callback_data: 'wallet_fill'
            ));

            $bot->sendMessage(
                text: $message,
                parse_mode: 'HTML',
                reply_markup: $keyboard
            );

            $bot->deleteMessage($bot->chatId(), $bot->messageId());
        } catch (WalletNotExistsException $e) {
            $bot->sendMessage('Ошибка ' . $e->getMessage());
        }
    }
}
