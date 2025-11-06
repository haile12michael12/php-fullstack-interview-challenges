<?php

namespace Challenge02\Application;

use Challenge02\Core\Auth\RegisterUserUseCase;
use Challenge02\Core\Auth\LoginUserUseCase;
use Challenge02\Core\Auth\LogoutUserUseCase;
use Challenge02\Core\Auth\ResetPasswordUseCase;
use Challenge02\Core\Auth\GenerateTokenUseCase;

class AuthService
{
    private RegisterUserUseCase $registerUserUseCase;
    private LoginUserUseCase $loginUserUseCase;
    private LogoutUserUseCase $logoutUserUseCase;
    private ResetPasswordUseCase $resetPasswordUseCase;
    private GenerateTokenUseCase $generateTokenUseCase;

    public function __construct(
        RegisterUserUseCase $registerUserUseCase,
        LoginUserUseCase $loginUserUseCase,
        LogoutUserUseCase $logoutUserUseCase,
        ResetPasswordUseCase $resetPasswordUseCase,
        GenerateTokenUseCase $generateTokenUseCase
    ) {
        $this->registerUserUseCase = $registerUserUseCase;
        $this->loginUserUseCase = $loginUserUseCase;
        $this->logoutUserUseCase = $logoutUserUseCase;
        $this->resetPasswordUseCase = $resetPasswordUseCase;
        $this->generateTokenUseCase = $generateTokenUseCase;
    }

    public function register(array $data): array
    {
        return $this->registerUserUseCase->execute($data);
    }

    public function login(array $credentials): array
    {
        return $this->loginUserUseCase->execute($credentials);
    }

    public function logout(string $token): bool
    {
        return $this->logoutUserUseCase->execute($token);
    }

    public function requestPasswordReset(string $email): void
    {
        $this->resetPasswordUseCase->requestReset($email);
    }

    public function resetPassword(string $token, string $newPassword): bool
    {
        return $this->resetPasswordUseCase->execute($token, $newPassword);
    }

    public function generateToken(object $user): array
    {
        return $this->generateTokenUseCase->execute($user);
    }
}