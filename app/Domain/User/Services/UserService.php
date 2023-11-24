<?php
declare(strict_types=1);

namespace App\Domain\User\Services;

use App\Domain\User\Dto\UserDto;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Models\User;
use Spatie\LaravelData\Exceptions\InvalidDataClass;

class UserService
{
    /**
     * @param int $id
     * @return UserDto
     * @throws InvalidDataClass
     * @throws UserNotFoundException
     */
    public function byId(int $id): UserDto
    {
        $model = User::whereId($id)->first();

        if (!$model) {
            throw new UserNotFoundException('Пользователь не найден');
        }

        return $model->dto();
    }
}
