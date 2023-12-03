<?php

namespace App\Telegram;

use App\Telegram\Actions\Common\ShowMenuAction;
use App\Telegram\Actions\Common\RegistrationConfirmAction;
use App\Telegram\Actions\Common\StartAction;
use App\Telegram\Actions\Order\ShowOrderListAction;
use App\Telegram\Actions\Store\BuyProductAction;
use App\Telegram\Actions\Store\LowBalanceAction;
use App\Telegram\Actions\Store\ShowCatalogAction;
use App\Telegram\Actions\Store\ShowProductAction;
use App\Telegram\Actions\Wallet\GiveTransactionAction;
use App\Telegram\Actions\Wallet\MakeTransactionAction;
use App\Telegram\Actions\Wallet\ShowWalletAction;
use App\Telegram\Actions\Wallet\WalletTransferAction;
use App\Telegram\Enums\MenuEnum;
use App\Telegram\Middlewares\AuthenticationMiddleware;

final class StoreBotProcessor extends AbstractBotProcessor
{
    protected function defineHandlers(): void
    {
        $this->bot->middleware(AuthenticationMiddleware::class);

        $this->bot->onCommand('menu', ShowMenuAction::class);
        $this->bot->onCommand('start', StartAction::class)->skipGlobalMiddlewares();
        $this->bot->onCommand('start reg-{value}', RegistrationConfirmAction::class)->skipGlobalMiddlewares();
        $this->bot->onCommand('start give-{value}', GiveTransactionAction::class);

        $this->bot->onText(MenuEnum::CATALOG->value(), ShowCatalogAction::class);
        $this->bot->onText(MenuEnum::ACCOUNT->value(), ShowWalletAction::class);
        $this->bot->onText(MenuEnum::ORDER->value(), ShowOrderListAction::class);

        $this->bot->onCallbackQueryData('product {id}', ShowProductAction::class);
        $this->bot->onCallbackQueryData('show_catalog', ShowCatalogAction::class);
        $this->bot->onCallbackQueryData('show_wallet', ShowWalletAction::class);
        $this->bot->onCallbackQueryData('buy {id}', BuyProductAction::class);
        $this->bot->onCallbackQueryData('low_balance', LowBalanceAction::class);

        $this->bot->onCallbackQueryData('wallet_transfer', WalletTransferAction::class);
        $this->bot->onCallbackQueryData('make_transaction {value}', MakeTransactionAction::class);
        //$this->bot->onCallbackQueryData('cancel {param}', CancelHandler::class);
    }
}
