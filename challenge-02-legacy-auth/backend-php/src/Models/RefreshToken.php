<?php

declare(strict_types=1);

namespace Challenge02\Models;

/**
 * Refresh token model for JWT refresh functionality
 */
class RefreshToken
{
    public string $id;
    public string $user_id;
    public string $jti;
    public string $token;
    public string $expires_at;
    public string $created_at;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function isExpired(): bool
    {
        return strtotime($this->expires_at) < time();
    }
}
