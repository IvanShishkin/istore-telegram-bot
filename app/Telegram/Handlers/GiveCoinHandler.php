<?php
declare(strict_types=1);

namespace App\Telegram\Handlers;

use SergiX44\Nutgram\Nutgram;

class GiveCoinHandler
{
    public function __invoke(Nutgram $bot, $value): void
    {
        $bot->sendMessage('give=' . $value);
    }
}
