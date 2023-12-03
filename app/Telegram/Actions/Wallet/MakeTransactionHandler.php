<?php

declare(strict_types=1);

namespace App\Telegram\Actions\Wallet;

use App\Domain\Transactions\Actions\CreateTransactionAction;
use App\Domain\Transactions\Dto\TransactionDto;
use App\Domain\Wallets\Services\UserWalletService;
use App\Support\Utils;
use App\Telegram\Components\ActionLinkBuilder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;

final class MakeTransactionHandler
{
    public function __invoke(Nutgram $bot, $value): void
    {
        $logger = Log::channel('telegram');
        $logger->info('Начало создания транзакции', ['user_id' => Auth::id()]);

        try {
            $transactionAction = \App::make(CreateTransactionAction::class);
            $userWalletService = \App::make(UserWalletService::class);

            $wallet = $userWalletService->initWallet(\Auth::id());

            $terminateDate = Carbon::now()->addHours(24);

            $transactionDto = new TransactionDto(
                from: $wallet,
                value: (int)$value,
                term_at: $terminateDate,
                comment: __('bot_wallet.transfer_transaction_comment')
            );

            $createdTransaction = $transactionAction->execute($transactionDto);

            $message = __('bot_wallet.transfer_crated', [
                'value' => $value,
                'link' => ActionLinkBuilder::makeTransaction($createdTransaction)
            ]);

            $bot->editMessageText(text: $message, parse_mode: 'HTML');
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
