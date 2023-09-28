<?php

use App\Dto\UserDto;
use App\Repositories\UserRepository;
use App\Services\db\JsonDb;

$dbConnector = new JsonDb();
$userRepository = new UserRepository($dbConnector);

// Add user
$userRepository->addUser(
    UserDto::createFromArray([
        'user_name' => 'Test',
        'first_name' => 'Tester',
        'last_name' => 'Testers',
    ])
);

// Update user
$userRepository->updateUser(3, ['user_name' => 'new name']);

// Delete user
$userRepository->deleteUser(3);

// Get users
$data = $userRepository->getUsers();
require_once('view/main.view.php');

