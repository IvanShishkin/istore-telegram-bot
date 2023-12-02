<?php

namespace App\Telegram;

use App\Telegram\Actions\Common\MenuHandler;
use App\Telegram\Actions\Common\RegistrationConfirmHandler;
use App\Telegram\Actions\Common\StartHandler;
use App\Telegram\Actions\Order\ShowOrderListHandler;
use App\Telegram\Actions\Store\BuyProductHandler;
use App\Telegram\Actions\Store\LowBalanceHandler;
use App\Telegram\Actions\Store\ShowCatalogHandler;
use App\Telegram\Actions\Store\ShowProductHandler;
use App\Telegram\Actions\Wallet\GiveTransactionHandler;
use App\Telegram\Actions\Wallet\MakeTransactionHandler;
use App\Telegram\Actions\Wallet\ShowWalletHandler;
use App\Telegram\Actions\Wallet\WalletTransferHandler;
use App\Telegram\Enums\MenuEnum;
use App\Telegram\Middlewares\AuthenticationMiddleware;

final class StoreBotProcessor extends AbstractBotProcessor
{
    protected function defineHandlers(): void
    {
        $this->bot->middleware(AuthenticationMiddleware::class);

        $this->bot->onCommand('menu', MenuHandler::class);
        $this->bot->onCommand('start', StartHandler::class)->skipGlobalMiddlewares();
        $this->bot->onCommand('start reg-{value}', RegistrationConfirmHandler::class)->skipGlobalMiddlewares();
        $this->bot->onCommand('start give-{value}', GiveTransactionHandler::class);

        $this->bot->onText(MenuEnum::CATALOG->value(), ShowCatalogHandler::class);
        $this->bot->onText(MenuEnum::ACCOUNT->value(), ShowWalletHandler::class);
        $this->bot->onText(MenuEnum::ORDER->value(), ShowOrderListHandler::class);

        $this->bot->onCallbackQueryData('product {id}', ShowProductHandler::class);
        $this->bot->onCallbackQueryData('show_catalog', ShowCatalogHandler::class);
        $this->bot->onCallbackQueryData('show_wallet', ShowWalletHandler::class);
        $this->bot->onCallbackQueryData('buy {id}', BuyProductHandler::class);
        $this->bot->onCallbackQueryData('low_balance', LowBalanceHandler::class);

        $this->bot->onCallbackQueryData('wallet_transfer', WalletTransferHandler::class);
        $this->bot->onCallbackQueryData('make_transaction {value}', MakeTransactionHandler::class);
        //$this->bot->onCallbackQueryData('cancel {param}', CancelHandler::class);
    }
}
