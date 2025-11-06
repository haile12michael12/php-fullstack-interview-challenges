<?php

namespace Challenge02\Infrastructure\Security;

use Challenge02\Contracts\Auth\AuthInterface;
use Challenge02\Contracts\Auth\TokenInterface;
use Challenge02\Contracts\Repositories\UserRepositoryInterface;

class AuthManager implements AuthInterface
{
    private TokenInterface $tokenManager;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        TokenInterface $tokenManager,
        UserRepositoryInterface $userRepository
    ) {
        $this->tokenManager = $tokenManager;
        $this->userRepository = $userRepository;
    }

    public function authenticate(array $credentials): bool
    {
        // Implementation would verify credentials and return true/false
        return true;
    }

    public function login(array $credentials): array
    {
        // Implementation would handle login logic
        return [];
    }

    public function logout(string $token): bool
    {
        // Implementation would handle logout logic
        return $this->tokenManager->invalidate($token);
    }

    public function register(array $data): array
    {
        // Implementation would handle registration logic
        return [];
    }

    public function getUserFromToken(string $token): object
    {
        $payload = $this->tokenManager->getPayload($token);
        $user = $this->userRepository->findById($payload['sub']);
        
        if (!$user) {
            throw new \Exception('User not found');
        }
        
        return $user;
    }

    public function validateToken(string $token): bool
    {
        return $this->tokenManager->validate($token);
    }
}