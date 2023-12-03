<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares;

use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Services\UserAuthService;
use SergiX44\Nutgram\Nutgram;

final class AuthenticationMiddleware
{
    public function __invoke(Nutgram $bot, $next, UserAuthService $userService): void
    {
        try {
            $userData = $userService->authByExternalId($bot->userId());

            $bot->set('systemUserId', $userData->id);

            $next($bot);
        } catch (UserNotFoundException $e) {
            $bot->sendMessage('Ошибка авторизации. Обратитесь к администратору');
        }
    }
}
