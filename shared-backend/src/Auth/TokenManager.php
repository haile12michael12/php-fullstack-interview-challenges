<?php

namespace SharedBackend\Auth;

use SharedBackend\Core\Config;
use SharedBackend\Core\Exceptions\AuthException;

class TokenManager
{
    private $config;
    private $secretKey;
    
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->secretKey = $config->get('auth.secret_key');
        
        if (empty($this->secretKey)) {
            throw new AuthException('JWT secret key is not configured');
        }
    }
    
    /**
     * Generate access and refresh tokens
     * 
     * @param array $user
     * @return array
     */
    public function generateTokens(array $user): array
    {
        $now = time();
        
        // Access token
        $accessTokenPayload = [
            'sub' => $user['id'],
            'name' => $user['username'] ?? $user['email'] ?? '',
            'roles' => $user['roles'] ?? [],
            'iat' => $now,
            'exp' => $now + $this->config->get('auth.token_lifetime', 3600),
            'jti' => bin2hex(random_bytes(16))
        ];
        
        // Refresh token (longer lifetime)
        $refreshTokenPayload = [
            'sub' => $user['id'],
            'iat' => $now,
            'exp' => $now + $this->config->get('auth.refresh_token_lifetime', 86400 * 7),
            'jti' => bin2hex(random_bytes(16)),
            'type' => 'refresh'
        ];
        
        return [
            'access_token' => $this->encodeToken($accessTokenPayload),
            'refresh_token' => $this->encodeToken($refreshTokenPayload)
        ];
    }
    
    /**
     * Verify a token and return its payload
     * 
     * @param string $token
     * @param bool $isRefreshToken
     * @return array|null
     */
    public function verifyToken(string $token, bool $isRefreshToken = false): ?array
    {
        try {
            $payload = $this->decodeToken($token);
            
            // Verify token type for refresh tokens
            if ($isRefreshToken && (!isset($payload['type']) || $payload['type'] !== 'refresh')) {
                return null;
            }
            
            return $payload;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Encode a payload into a JWT token
     * 
     * @param array $payload
     * @return string
     */
    private function encodeToken(array $payload): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);
        
        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode($payload);
        
        $signature = hash_hmac('sha256', 
            $base64UrlHeader . "." . $base64UrlPayload, 
            $this->secretKey, 
            true
        );
        $base64UrlSignature = $this->base64UrlEncode($signature);
        
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }
    
    /**
     * Decode a JWT token and return the payload
     * 
     * @param string $token
     * @return array
     * @throws AuthException
     */
    private function decodeToken(string $token): array
    {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            throw new AuthException('Invalid token format');
        }
        
        [$base64UrlHeader, $base64UrlPayload, $base64UrlSignature] = $parts;
        
        // Verify signature
        $signature = $this->base64UrlDecode($base64UrlSignature);
        $expectedSignature = hash_hmac('sha256', 
            $base64UrlHeader . "." . $base64UrlPayload, 
            $this->secretKey, 
            true
        );
        
        if (!hash_equals($signature, $expectedSignature)) {
            throw new AuthException('Invalid token signature');
        }
        
        // Decode payload
        $payload = json_decode($this->base64UrlDecode($base64UrlPayload), true);
        
        // Check expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new AuthException('Token has expired');
        }
        
        return $payload;
    }
    
    /**
     * Base64Url encode
     * 
     * @param string $data
     * @return string
     */
    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    /**
     * Base64Url decode
     * 
     * @param string $data
     * @return string
     */
    private function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}