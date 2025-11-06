<?php

namespace App\Contracts\Services;

interface AuthServiceInterface
{
    public function authenticate(string $email, string $password): bool;
    public function register(array $data): array;
    public function logout(): void;
    public function getCurrentUser(): ?array;
    public function isLoggedIn(): bool;
    public function hashPassword(string $password): string;
    public function verifyPassword(string $password, string $hash): bool;
}