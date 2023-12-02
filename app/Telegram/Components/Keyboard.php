<?php
declare(strict_types=1);

namespace App\Telegram\Components;

use App\Telegram\Enums\MenuEnum;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class Keyboard
{
    public static function makeMainKeyboard(): ReplyKeyboardMarkup
    {
        return ReplyKeyboardMarkup::make()
            ->addRow(KeyboardButton::make(MenuEnum::CATALOG->value()))
            ->addRow(KeyboardButton::make(MenuEnum::ACCOUNT->value()))
            ->addRow(KeyboardButton::make(MenuEnum::ORDER->value()));
    }
}
