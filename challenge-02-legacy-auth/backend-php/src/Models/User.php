<?php

declare(strict_types=1);

namespace Challenge02\Models;

/**
 * User model with advanced features
 */
class User
{
    public string $id;
    public string $email;
    public string $password;
    public string $first_name;
    public string $last_name;
    public ?string $phone;
    public bool $is_active;
    public ?string $email_verified_at;
    public ?string $last_login_at;
    public int $failed_login_attempts = 0;
    public bool $is_locked = false;
    public ?string $locked_until;
    public string $created_at;
    public string $updated_at;
    public array $roles = [];

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
        $data = get_object_vars($this);
        unset($data['password']); // Never expose password
        return $data;
    }

    public function getFullName(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    public function addRole(string $role): void
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    public function removeRole(string $role): void
    {
        $this->roles = array_filter($this->roles, fn($r) => $r !== $role);
    }

    public function isEmailVerified(): bool
    {
        return !empty($this->email_verified_at);
    }

    public function isLocked(): bool
    {
        return $this->is_locked && 
               ($this->locked_until === null || strtotime($this->locked_until) > time());
    }
}
