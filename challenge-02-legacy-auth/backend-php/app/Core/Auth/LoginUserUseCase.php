<?php

namespace Challenge02\Core\Auth;

use Challenge02\Contracts\Repositories\UserRepositoryInterface;
use Challenge02\Contracts\Auth\PasswordHasherInterface;
use Challenge02\Contracts\Auth\TokenInterface;
use Challenge02\Domain\User;

class LoginUserUseCase
{
    private UserRepositoryInterface $userRepository;
    private PasswordHasherInterface $passwordHasher;
    private TokenInterface $tokenManager;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordHasherInterface $passwordHasher,
        TokenInterface $tokenManager
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->tokenManager = $tokenManager;
    }

    public function execute(array $credentials): array
    {
        // Validate credentials
        $this->validateCredentials($credentials);

        // Find user by email
        $user = $this->userRepository->findByEmail($credentials['email']);
        if (!$user || !$user->is_active) {
            throw new \Exception('Invalid credentials');
        }

        // Verify password
        if (!$this->passwordHasher->verify($credentials['password'], $user->password)) {
            $this->handleFailedLogin($user);
            throw new \Exception('Invalid credentials');
        }

        // Check if email is verified
        if (!$user->isEmailVerified()) {
            throw new \Exception('Please verify your email before logging in');
        }

        // Generate tokens
        $tokens = $this->generateTokens($user);

        // Update last login
        $this->updateLastLogin($user);

        return [
            'user' => $user->toArray(),
            'tokens' => $tokens
        ];
    }

    private function validateCredentials(array $credentials): void
    {
        if (empty($credentials['email']) || empty($credentials['password'])) {
            throw new \Exception('Email and password are required');
        }

        if (!filter_var($credentials['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email format');
        }
    }

    private function handleFailedLogin(User $user): void
    {
        // Increment failed login attempts
        // This would typically be implemented in the repository
    }

    private function generateTokens(User $user): array
    {
        $payload = [
            'sub' => $user->id,
            'email' => $user->email,
            'roles' => $user->roles,
            'iat' => time(),
            'exp' => time() + 3600 // 1 hour
        ];

        $accessToken = $this->tokenManager->generate($payload);
        
        $refreshPayload = [
            'sub' => $user->id,
            'type' => 'refresh',
            'iat' => time(),
            'exp' => time() + (30 * 24 * 60 * 60) // 30 days
        ];
        
        $refreshToken = $this->tokenManager->generate($refreshPayload);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_in' => 3600
        ];
    }

    private function updateLastLogin(User $user): void
    {
        // Update last login timestamp
        // This would typically be implemented in the repository
    }
}