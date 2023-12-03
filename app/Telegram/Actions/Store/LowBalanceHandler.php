<?php

declare(strict_types=1);

namespace App\Telegram\Actions\Store;

use SergiX44\Nutgram\Nutgram;

final class LowBalanceHandler
{
    public function __invoke(Nutgram $bot): void
    {
        $bot->sendSticker(sticker: __('bot_store.low_balance_sticker'));
        $bot->deleteMessage($bot->chatId(), $bot->messageId());
    }
}
