<?php

namespace Challenge02\Core\Auth;

use Challenge02\Contracts\Auth\TokenInterface;
use Challenge02\Domain\User;

class GenerateTokenUseCase
{
    private TokenInterface $tokenManager;

    public function __construct(TokenInterface $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    public function execute(User $user): array
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
}