<?php

namespace App\Dto;

class UserDto
{
    /**
     * @param int|null $id
     * @param string|null $userName
     * @param string|null $firstName
     * @param string|null $lastName
     */
    public function __construct(
        public ?int $id,
        public ?string $userName,
        public ?string $firstName,
        public ?string $lastName
    ) {
    }

    /**
     * Create DTO from array
     *
     * @param array $data
     * @return UserDto
     */
    public static function createFromArray(array $data): UserDto
    {
        return new self(
            $data['id'] ?? null,
            $data['user_name'] ?? null,
            $data['first_name'] ?? null,
            $data['last_name'] ?? null,
        );
    }
}