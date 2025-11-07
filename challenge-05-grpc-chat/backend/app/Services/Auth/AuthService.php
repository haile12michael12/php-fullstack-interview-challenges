<?php

namespace App\Services\Auth;

use App\Contracts\Services\AuthServiceInterface;
use App\Contracts\Repository\UserRepositoryInterface;

class AuthService implements AuthServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private array $currentUser = null;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticate(string $email, string $password): bool
    {
        $user = $this->userRepository->findByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        if ($this->verifyPassword($password, $user['password'])) {
            $this->currentUser = $user;
            // In a real implementation, you would set a session or JWT token
            $_SESSION['user_id'] = $user['id'];
            return true;
        }
        
        return false;
    }

    public function register(array $data): array
    {
        // Check if user already exists
        if ($this->userRepository->findByEmail($data['email'])) {
            throw new \Exception('User with this email already exists');
        }
        
        if ($this->userRepository->findByUsername($data['username'])) {
            throw new \Exception('User with this username already exists');
        }
        
        // Hash the password
        $data['password'] = $this->hashPassword($data['password']);
        
        // Create the user
        $userId = $this->userRepository->create($data);
        
        // Return the user data
        return $this->userRepository->findById($userId);
    }

    public function logout(): void
    {
        $this->currentUser = null;
        // In a real implementation, you would destroy the session or JWT token
        unset($_SESSION['user_id']);
    }

    public function getCurrentUser(): ?array
    {
        if ($this->currentUser) {
            return $this->currentUser;
        }
        
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId) {
            $this->currentUser = $this->userRepository->findById($userId);
            return $this->currentUser;
        }
        
        return null;
    }

    public function isLoggedIn(): bool
    {
        return $this->getCurrentUser() !== null;
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}