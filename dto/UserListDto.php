<?php

namespace App\Dto;

class UserListDto
{
    /**
     * @param array<UserDto> $users
     */
    public function __construct(
        public array $users = []
    ) {
    }

    /**
     * Create DTO from array
     *
     * @param array $data
     * @return UserListDto
     */
    public static function createFromArray(array $data): UserListDto
    {
        $users = [];
        foreach ($data as $user) {
            $users[] = UserDto::createFromArray($user);
        }

        return new self($users);
    }
}