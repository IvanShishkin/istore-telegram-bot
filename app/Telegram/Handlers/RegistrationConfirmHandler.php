<?php
declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Domain\User\Exception\RegistrationConfirmException;
use App\Domain\User\Services\UserService;
use App\Telegram\Actions\ShowMenuAction;
use SergiX44\Nutgram\Nutgram;

class RegistrationConfirmHandler
{
    public function __invoke(Nutgram $bot, $value, UserService $userService): void
    {
        try {
            $userData = $userService->registrationConfirmation($value, $bot->userId());
            $bot->sendMessage('Привет, ' . $userData->name . '! Я тебя узнал');
        } catch (RegistrationConfirmException $e) {
            $bot->sendMessage($e->getMessage());
        }
    }
}
