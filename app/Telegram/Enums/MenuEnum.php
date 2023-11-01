<?php

namespace App\Telegram\Enums;

enum MenuEnum
{
    case CATALOG;
    case ACCOUNT;

    public function value(): string
    {
        return match ($this) {
            self::CATALOG => __('bot_menu.store'),
            self::ACCOUNT => __('bot_menu.account'),
            default => ''
        };
    }

}
