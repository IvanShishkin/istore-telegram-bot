<?php
declare(strict_types=1);

namespace App\Telegram\Actions;

use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

final class ShowInfoAction
{
    public function execute(Nutgram $bot): void
    {
        Log::info(__FUNCTION__, [$bot->userId()]);
        $bot->sendMessage(
            text: '123',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(
                    InlineKeyboardButton::make('A', callback_data:'buy ' . json_encode(['m' => 'buy'])),
                    InlineKeyboardButton::make('B', callback_data:'cancel ' . json_encode(['m' => 'cancel']))
                )
        );
    }
}
