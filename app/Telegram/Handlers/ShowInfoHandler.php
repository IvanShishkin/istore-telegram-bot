<?php
declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Telegram\Actions\ShowInfoAction;
use SergiX44\Nutgram\Nutgram;

final class ShowInfoHandler
{
    public function __invoke(Nutgram $bot, ShowInfoAction $action): void
    {
        $action->execute($bot);
    }
}
