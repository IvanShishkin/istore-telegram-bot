<?php
declare(strict_types=1);

namespace App\Domain\User\Services;

use App\Domain\User\Dto\RegistrationDto;
use App\Domain\User\Dto\UserDto;
use App\Domain\User\Exception\AlreadyExistsException;
use App\Domain\User\Exception\RegistrationConfirmException;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Models\User;
use App\Events\RegistrationConfirmEvent;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Exceptions\InvalidDataClass;

final class UserAuthService
{
    /**
     * @throws UserNotFoundException
     */
    public function authByExternalId(mixed $externalId): ?UserDto
    {
        $user = User::where([
            'external_id' => $externalId,
            'active' => true
        ])->first();

        if (!$user) {
            throw new UserNotFoundException('Данный пользователь не зарегистрирован');
        }

        return $this->login($user);
    }

    /**
     * @param User $user
     * @return UserDto|null
     */
    protected function login(User $user): ?UserDto
    {
        Auth::login($user);

        return $this->getAuthUser();
    }

    /**
     * @return UserDto|null
     * @throws InvalidDataClass
     */
    public function getAuthUser(): ?UserDto
    {
        return Auth::user()?->dto();
    }

    /**
     * @param string $email
     * @return bool
     */
    public function checkExistsByEmail(string $email): bool
    {
        return User::where(['email' => $email])->exists();
    }

    /**
     * @throws InvalidDataClass
     * @throws AlreadyExistsException
     */
    public function register(RegistrationDto $dto): UserDto
    {
        if ($this->checkExistsByEmail($dto->getEmail())) {
            throw new AlreadyExistsException('Пользователь с таким email уже существует');
        }

        $fields = $dto->toArray();
        $fields['password'] = \Hash::make(\Str::password(14));
        $fields['confirm_token'] = \Str::random(8);

        return User::create($fields)->dto();
    }

    /**
     * @throws RegistrationConfirmException
     */
    public function registrationConfirmation(string $confirmToken, ?int $externalId = null): ?UserDto
    {
        $user = User::where(['confirm_token' => $confirmToken, 'active' => false])->first();

        if (!$user) {
            throw new RegistrationConfirmException('Ошибка подтверждения регистрации пользователя');
        }

        $user->fill([
            'active' => true,
            'external_id' => $externalId
        ]);

        $user->save();

        RegistrationConfirmEvent::dispatch($user->dto());

        return $this->login($user);
    }
}
