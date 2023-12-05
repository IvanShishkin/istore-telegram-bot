<?php

declare(strict_types=1);

namespace App\Domain\User\Actions;

use App\Telegram\Notifications\UserNotification;

final class TelegramNotifyAction
{
    public function __construct(protected UserNotification $notification)
    {
    }
}
