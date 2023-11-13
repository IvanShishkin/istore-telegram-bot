<?php
declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Telegram\Actions\ShowMenuAction;
use SergiX44\Nutgram\Nutgram;

final class MenuHandler
{
    public function __invoke(Nutgram $bot, ShowMenuAction $action)
    {
        $action->execute($bot);
    }
}
