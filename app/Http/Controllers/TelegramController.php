<?php

namespace App\Http\Controllers;

use App\Telegram\StoreBotBotProcessor;

class TelegramController extends Controller
{
    public function __invoke(StoreBotBotProcessor $processor)
    {
        $processor->process();
    }
}
