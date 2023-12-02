<?php
declare(strict_types=1);

namespace App\Telegram\Actions\Common;

use App\Domain\User\Exception\RegistrationConfirmException;
use App\Domain\User\Services\UserAuthService;
use App\Domain\User\Services\UserService;
use App\Support\Logger\TelegramLoggerAwareInterface;
use App\Support\Utils;
use App\Telegram\Components\Keyboard;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerAwareTrait;
use Ramsey\Uuid\Uuid;
use SergiX44\Nutgram\Nutgram;

/**
 * Подтверждение регистрации пользователя через специальную ссылку с токеном
 */
final class RegistrationConfirmHandler
{
    public function __invoke(
        Nutgram $bot,
        $value,
        UserAuthService $authUserService,
        UserService $userService
    ): void
    {
        $logger = Log::channel('telegram');

        if ($userService->existsByExternalId($bot->userId())) {
            $logger->warning('Повторная попытка регистрации');

            $bot->sendMessage(__('bot_common.already_registered'));

            return;
        }

        try {
            $userData = $authUserService->registrationConfirmation($value, $bot->userId());

            $bot->sendMessage(
                text: __('bot_common.success_registered', ['name' => $userData->name]),
                reply_markup: Keyboard::makeMainKeyboard()
            );
        } catch (RegistrationConfirmException $exception) {
            $logger->error('Ошибка регистрации', [
                'exception' => $exception
            ]);

            $bot->sendMessage($exception->getMessage());

        } catch (\Throwable $exception) {
            $errorCode = Utils::errorInitializationCode();

            $logger->critical('Фатальная ошибка регистрации', [
                'exception' => $exception,
                'error_code' => $errorCode
            ]);

            $bot->sendMessage(
                text: __('bot_common.error_default', ['error_code' => $errorCode]),
                parse_mode: 'HTML'
            );
        }
    }
}
