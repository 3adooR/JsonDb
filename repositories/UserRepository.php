<?php

namespace App\Repositories;

use App\Dto\UserDto;
use App\Dto\UserListDto;
use App\Services\db\DbConnectorInterface;
use Exception;

class UserRepository
{
    /** @var string */
    private string $tableName = 'users';

    /**
     * @param DbConnectorInterface $db
     */
    public function __construct(private readonly DbConnectorInterface $db)
    {
    }

    /**
     * Get users (id and user_name)
     *
     * @return UserListDto
     */
    public function getUsers(): UserListDto
    {
        $users = $this->db
            ->table($this->tableName)
            ->select([
                'id',
                'user_name',
            ])
            ->get();

        return UserListDto::createFromArray($users);
    }

    /**
     * Add new user
     *
     * @param UserDto $userDto
     * @return void
     * @throws Exception
     */
    public function addUser(UserDto $userDto): void
    {
        $this->db->table($this->tableName)->insert([
            'id' => $userDto->id,
            'user_name' => $userDto->userName,
            'first_name' => $userDto->firstName,
            'last_name' => $userDto->lastName,
        ]);
    }

    /**
     * Update user data
     *
     * @param int $userId
     * @param array $data
     * @return void
     */
    public function updateUser(int $userId, array $data): void
    {
        $this->db->table($this->tableName)->update($userId, $data);
    }

    /**
     * Delete user by id
     *
     * @param int $userId
     * @return void
     * @throws Exception
     */
    public function deleteUser(int $userId): void
    {
        $this->db->table($this->tableName)->delete($userId);
    }
}