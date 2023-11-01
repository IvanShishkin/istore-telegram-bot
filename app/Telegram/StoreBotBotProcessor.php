<?php

namespace App\Telegram;

use App\Telegram\Handlers\BuyHandler;
use App\Telegram\Handlers\CancelHandler;
use App\Telegram\Handlers\GiveCoinHandler;
use App\Telegram\Handlers\MenuHandler;
use App\Telegram\Handlers\RegistrationConfirmHandler;
use App\Telegram\Middlewares\AuthenticationMiddleware;

final class StoreBotBotProcessor extends AbstractBotProcessor
{
    protected function defineHandlers()
    {
        $this->bot->middleware(AuthenticationMiddleware::class);
        $this->bot->onCommand('menu', MenuHandler::class);
        $this->bot->onCommand('start reg-{value}', RegistrationConfirmHandler::class)->skipGlobalMiddlewares();
        $this->bot->onCommand('start give-{value}', GiveCoinHandler::class);
        $this->bot->onCallbackQueryData('buy {param}', BuyHandler::class);
        $this->bot->onCallbackQueryData('cancel {param}', CancelHandler::class);
    }
}
