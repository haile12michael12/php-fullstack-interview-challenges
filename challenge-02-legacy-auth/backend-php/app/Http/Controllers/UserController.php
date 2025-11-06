<?php

namespace Challenge02\Http\Controllers;

use Challenge02\Application\UserService;

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function profile(): array
    {
        // In a real implementation, you would extract the user ID from the token
        $userId = $_SERVER['HTTP_USER_ID'] ?? '';
        return $this->userService->getProfile($userId);
    }
}