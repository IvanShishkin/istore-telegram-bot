<?php
declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Telegram\Actions\StartAction;
use SergiX44\Nutgram\Nutgram;

final class StartHandler
{
    public function __invoke(Nutgram $bot, StartAction $action): void
    {
        $action->execute($bot);
    }
}
