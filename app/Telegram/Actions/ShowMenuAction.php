<?php
declare(strict_types=1);

namespace App\Telegram\Actions;

use App\Telegram\Components\Keyboard;
use SergiX44\Nutgram\Nutgram;

final class ShowMenuAction
{
    public function execute(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: 'Основное меню бота:',
            reply_markup: Keyboard::makeMainKeyboard()
        );

        $bot->sendMessage('user_id: ' . \Auth::id());
    }
}
