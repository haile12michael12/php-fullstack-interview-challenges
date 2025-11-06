<?php

namespace Challenge02\Infrastructure\Security;

use Challenge02\Contracts\Auth\TokenInterface;

class JwtTokenManager implements TokenInterface
{
    private string $secret;
    private string $algorithm;
    private array $blacklistedTokens;

    public function __construct(string $secret, string $algorithm = 'HS256')
    {
        $this->secret = $secret;
        $this->algorithm = $algorithm;
        $this->blacklistedTokens = [];
    }

    public function generate(array $payload): string
    {
        // In a real implementation, you would use the Firebase JWT library
        // For now, we'll return a mock token
        return base64_encode(json_encode($payload));
    }

    public function validate(string $token): bool
    {
        if ($this->isBlacklisted($token)) {
            return false;
        }

        try {
            $this->getPayload($token);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function refresh(string $refreshToken): array
    {
        $payload = $this->getPayload($refreshToken);
        
        if (!isset($payload['type']) || $payload['type'] !== 'refresh') {
            throw new \Exception('Invalid refresh token');
        }

        $newPayload = [
            'sub' => $payload['sub'],
            'email' => $payload['email'] ?? '',
            'roles' => $payload['roles'] ?? [],
            'iat' => time(),
            'exp' => time() + 3600 // 1 hour
        ];

        $accessToken = $this->generate($newPayload);
        
        $newRefreshPayload = [
            'sub' => $payload['sub'],
            'type' => 'refresh',
            'iat' => time(),
            'exp' => time() + (30 * 24 * 60 * 60) // 30 days
        ];
        
        $newRefreshToken = $this->generate($newRefreshPayload);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $newRefreshToken,
            'token_type' => 'Bearer',
            'expires_in' => 3600
        ];
    }

    public function invalidate(string $token): bool
    {
        $this->blacklistedTokens[] = $token;
        return true;
    }

    public function getPayload(string $token): array
    {
        // In a real implementation, you would use the Firebase JWT library
        // For now, we'll decode the mock token
        $payload = json_decode(base64_decode($token), true);
        
        if (!$payload) {
            throw new \Exception('Invalid token');
        }
        
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new \Exception('Token has expired');
        }
        
        return $payload;
    }

    private function isBlacklisted(string $token): bool
    {
        return in_array($token, $this->blacklistedTokens);
    }
}