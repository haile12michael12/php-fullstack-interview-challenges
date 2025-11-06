<?php

namespace Challenge02\Http\Controllers;

use Challenge02\Application\AuthService;

class AuthController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(): array
    {
        $data = $_POST; // In a real implementation, you would use a proper request object
        return $this->authService->register($data);
    }

    public function login(): array
    {
        $credentials = $_POST; // In a real implementation, you would use a proper request object
        return $this->authService->login($credentials);
    }

    public function logout(): bool
    {
        $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token = str_replace('Bearer ', '', $token);
        return $this->authService->logout($token);
    }

    public function requestPasswordReset(): void
    {
        $email = $_POST['email'] ?? '';
        $this->authService->requestPasswordReset($email);
    }

    public function resetPassword(): bool
    {
        $token = $_POST['token'] ?? '';
        $newPassword = $_POST['password'] ?? '';
        return $this->authService->resetPassword($token, $newPassword);
    }
}