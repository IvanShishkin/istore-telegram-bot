<?php
declare(strict_types=1);

namespace App\Telegram\Actions\Common;

use App\Telegram\Components\Keyboard;
use SergiX44\Nutgram\Nutgram;

/**
 * Показ основного меню бота
 */
final class MenuHandler
{
    public function __invoke(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: __('bot_menu.description'),
            reply_markup: Keyboard::makeMainKeyboard()
        );
    }
}
