<?php

namespace SharedBackend\Auth;

use SharedBackend\Core\Config;
use SharedBackend\Core\Exceptions\AuthException;

class AuthManager
{
    private $config;
    private $tokenManager;
    private $userProvider;

    public function __construct(Config $config, TokenManager $tokenManager, UserProviderInterface $userProvider)
    {
        $this->config = $config;
        $this->tokenManager = $tokenManager;
        $this->userProvider = $userProvider;
    }

    /**
     * Authenticate a user with credentials
     * 
     * @param string $username
     * @param string $password
     * @return array User data with token
     * @throws AuthException
     */
    public function authenticate(string $username, string $password): array
    {
        $user = $this->userProvider->getUserByUsername($username);
        
        if (!$user || !$this->verifyPassword($password, $user['password'])) {
            throw new AuthException('Invalid credentials');
        }

        // Remove password from user data
        unset($user['password']);
        
        // Generate tokens
        $tokens = $this->tokenManager->generateTokens($user);
        
        return [
            'user' => $user,
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'expires_in' => $this->config->get('auth.token_lifetime', 3600)
        ];
    }

    /**
     * Verify a token and return user data
     * 
     * @param string $token
     * @return array|null User data or null if invalid
     */
    public function verifyToken(string $token): ?array
    {
        $payload = $this->tokenManager->verifyToken($token);
        if (!$payload) {
            return null;
        }
        
        return $this->userProvider->getUserById($payload['sub']);
    }

    /**
     * Refresh an access token using a refresh token
     * 
     * @param string $refreshToken
     * @return array New tokens
     * @throws AuthException
     */
    public function refreshToken(string $refreshToken): array
    {
        $payload = $this->tokenManager->verifyToken($refreshToken, true);
        if (!$payload) {
            throw new AuthException('Invalid refresh token');
        }
        
        $user = $this->userProvider->getUserById($payload['sub']);
        if (!$user) {
            throw new AuthException('User not found');
        }
        
        return $this->tokenManager->generateTokens($user);
    }

    /**
     * Verify a password against a hash
     * 
     * @param string $password
     * @param string $hash
     * @return bool
     */
    private function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Hash a password
     * 
     * @param string $password
     * @return string
     */
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3
        ]);
    }
}