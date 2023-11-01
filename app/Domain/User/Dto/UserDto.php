<?php
declare(strict_types=1);

namespace App\Domain\User\Dto;

use Spatie\LaravelData\Data;

class UserDto extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly bool $active,
    )
    {
    }
}
