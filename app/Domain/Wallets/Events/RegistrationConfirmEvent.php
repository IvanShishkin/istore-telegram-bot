<?php

namespace App\Domain\Wallets\Events;

use App\Domain\User\Dto\UserDto;
use Illuminate\Foundation\Events\Dispatchable;

class RegistrationConfirmEvent
{
    use Dispatchable;

    public function __construct(protected UserDto $userDto)
    {
    }

    /**
     * @return UserDto
     */
    public function getUserDto(): UserDto
    {
        return $this->userDto;
    }
}
