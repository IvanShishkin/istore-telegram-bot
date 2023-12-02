<?php

namespace App\Http\Controllers;

use App\Support\Logger\TelegramLoggerAwareInterface;
use App\Telegram\StoreBotProcessor;
use Illuminate\Http\Request;
use Psr\Log\LoggerAwareTrait;

class TelegramController extends Controller implements TelegramLoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __invoke(Request $request, StoreBotProcessor $processor)
    {
        $this->logger?->info('Request', $request->toArray());

        $processor->process();
    }
}
