<?php
declare(strict_types=1);

namespace App\Telegram\Handlers;

use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;

class BuyHandler
{
    public function __invoke(Nutgram $bot, $param)
    {
        $data = json_decode($param, true);

        Log::info('buyHandler', $data);
    }
}
