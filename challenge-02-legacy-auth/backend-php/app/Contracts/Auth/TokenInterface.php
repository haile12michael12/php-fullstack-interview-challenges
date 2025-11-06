<?php

namespace Challenge02\Contracts\Auth;

interface TokenInterface
{
    public function generate(array $payload): string;
    public function validate(string $token): bool;
    public function refresh(string $refreshToken): array;
    public function invalidate(string $token): bool;
    public function getPayload(string $token): array;
}