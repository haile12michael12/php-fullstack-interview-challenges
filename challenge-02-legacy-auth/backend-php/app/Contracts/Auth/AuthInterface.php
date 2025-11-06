<?php

namespace Challenge02\Contracts\Auth;

interface AuthInterface
{
    public function authenticate(array $credentials): bool;
    public function login(array $credentials): array;
    public function logout(string $token): bool;
    public function register(array $data): array;
    public function getUserFromToken(string $token): object;
    public function validateToken(string $token): bool;
}