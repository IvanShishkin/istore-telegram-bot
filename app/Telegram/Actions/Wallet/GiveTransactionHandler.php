<?php
declare(strict_types=1);

namespace App\Telegram\Actions\Wallet;

use App\Domain\Transactions\Actions\ApplyTransactionAction;
use App\Domain\Transactions\Exceptions\ErrorApplyTransactionException;
use App\Domain\Transactions\Exceptions\IncorrectStatusException;
use App\Domain\Wallets\Services\UserWalletService;
use App\Support\Utils;
use Illuminate\Support\Facades\Auth;
use SergiX44\Nutgram\Nutgram;

final class GiveTransactionHandler
{
    public function __invoke(Nutgram $bot, $value): void
    {
        $transactionAction = \App::make(ApplyTransactionAction::class);
        $userWalletService = \App::make(UserWalletService::class);

        $logger = \Log::channel('telegram');

        $logger->info('Активация транзакции', ['user_id' => Auth::id(), 'transaction_id' => $value]);

        try {
            $wallet = $userWalletService->initWallet(\Auth::id());
            $transactionDto = $transactionAction->execute($value, $wallet, 'Начисление транзакцией');

            $bot->sendMessage(
                text: __('bot_wallet.transaction_apply', ['value' => $transactionDto->value]),
                parse_mode: 'HTML'
            );
        } catch (IncorrectStatusException|ErrorApplyTransactionException){
            $bot->sendMessage(
                text: __('bot_wallet.transaction_error'),
                parse_mode: 'HTML',
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
