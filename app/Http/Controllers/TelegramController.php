<?php

namespace App\Http\Controllers;

use App\Telegram\StoreBotProcessor;

class TelegramController extends Controller
{
    public function __invoke(StoreBotProcessor $processor)
    {
        $processor->process();
    }
}
