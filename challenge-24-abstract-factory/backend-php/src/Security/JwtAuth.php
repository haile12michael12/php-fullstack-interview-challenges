<?php

namespace App\Security;

class JwtAuth
{
    protected $secretKey;
    protected $algorithm = 'HS256';

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function generateToken(array $payload): string
    {
        // Add issued at time
        $payload['iat'] = time();
        
        // Add expiration time (1 hour by default)
        if (!isset($payload['exp'])) {
            $payload['exp'] = time() + 3600;
        }
        
        // Create header
        $header = $this->base64UrlEncode(json_encode(['typ' => 'JWT', 'alg' => $this->algorithm]));
        
        // Create payload
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));
        
        // Create signature
        $signature = $this->base64UrlEncode(hash_hmac('sha256', $header . '.' . $payloadEncoded, $this->secretKey, true));
        
        // Return the token
        return $header . '.' . $payloadEncoded . '.' . $signature;
    }

    public function validateToken(string $token): bool
    {
        try {
            $payload = $this->decodeToken($token);
            return isset($payload['exp']) && $payload['exp'] > time();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function decodeToken(string $token): array
    {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            throw new \Exception('Invalid token format');
        }
        
        $header = json_decode($this->base64UrlDecode($parts[0]), true);
        $payload = json_decode($this->base64UrlDecode($parts[1]), true);
        $signature = $parts[2];
        
        // Verify signature
        $expectedSignature = $this->base64UrlEncode(hash_hmac('sha256', $parts[0] . '.' . $parts[1], $this->secretKey, true));
        
        if ($signature !== $expectedSignature) {
            throw new \Exception('Invalid signature');
        }
        
        return $payload;
    }

    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    protected function base64UrlDecode(string $data): string
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}