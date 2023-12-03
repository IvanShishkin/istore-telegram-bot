<?php

declare(strict_types=1);

namespace App\Domain\User\Dto;

use Spatie\LaravelData\Data;

class UserDto extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $last_name,
        public readonly string $email,
        public readonly bool $active,
        public readonly ?string $confirm_token,
        public readonly ?int $external_id
    ) {
    }

    /**
     * @return string|null
     */
    public function getConfirmToken(): ?string
    {
        return $this->confirm_token;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getExternalId(): ?int
    {
        return $this->external_id;
    }
}
