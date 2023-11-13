<?php
declare(strict_types=1);

namespace App\Telegram\Actions;

use SergiX44\Nutgram\Nutgram;

final class StartAction
{
    public function execute(Nutgram $bot): void
    {
        $bot->sendMessage(
            'Привет!'
        );
    }
}
