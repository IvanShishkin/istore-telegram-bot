<?php

declare(strict_types=1);

namespace App\Telegram\Notifications;

use SergiX44\Nutgram\Nutgram;

abstract class AbstractNotification
{
    public function __construct(protected Nutgram $bot)
    {
    }

    public function sendMessage(string $message, ?int $chatId = null): void
    {
        $this->bot->sendMessage(
            text: $message,
            chat_id: $chatId,
            parse_mode: 'HTML',
        );
    }
}
