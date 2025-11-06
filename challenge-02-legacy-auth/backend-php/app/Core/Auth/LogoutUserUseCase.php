<?php

namespace Challenge02\Core\Auth;

use Challenge02\Contracts\Auth\TokenInterface;

class LogoutUserUseCase
{
    private TokenInterface $tokenManager;

    public function __construct(TokenInterface $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    public function execute(string $token): bool
    {
        // Invalidate the token
        return $this->tokenManager->invalidate($token);
    }
}