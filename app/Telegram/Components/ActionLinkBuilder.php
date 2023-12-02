<?php
declare(strict_types=1);

namespace App\Telegram\Components;

final class ActionLinkBuilder
{
    public static function makeTransaction(string $transactionId): string
    {
        return self::getBotUrl() . '?start=give-' . $transactionId;
    }

    public static function makeRegistrationConfirm(string $token): string
    {
        return self::getBotUrl() . '?start=reg-' . $token;
    }

    protected static function getBotUrl(): mixed
    {
        return 'https://t.me/' . config('nutgram.name');
    }
}
