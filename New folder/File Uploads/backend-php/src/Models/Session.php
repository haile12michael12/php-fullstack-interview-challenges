<?php

declare(strict_types=1);

namespace Challenge02\Models;

/**
 * Session model for tracking user sessions
 */
class Session
{
    public string $id;
    public string $user_id;
    public string $ip_address;
    public string $user_agent;
    public string $expires_at;
    public ?string $ended_at;
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

    public function isActive(): bool
    {
        return !$this->isExpired() && empty($this->ended_at);
    }
}
