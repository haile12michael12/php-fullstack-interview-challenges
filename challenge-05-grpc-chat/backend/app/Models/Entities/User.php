<?php

namespace App\Models\Entities;

use App\Core\Model;

class User extends Model
{
    protected int $id;
    protected string $username;
    protected string $email;
    protected string $password;
    protected string $created_at;
    protected string $updated_at;
    protected bool $is_online = false;
    protected string $last_seen;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function isOnline(): bool
    {
        return $this->is_online;
    }

    public function getLastSeen(): string
    {
        return $this->last_seen;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
        $this->setAttribute('username', $username);
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
        $this->setAttribute('email', $email);
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
        $this->setAttribute('password', $password);
    }

    public function setOnline(bool $online): void
    {
        $this->is_online = $online;
        $this->setAttribute('is_online', $online);
    }

    public function setLastSeen(string $lastSeen): void
    {
        $this->last_seen = $lastSeen;
        $this->setAttribute('last_seen', $lastSeen);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_online' => $this->is_online,
            'last_seen' => $this->last_seen
        ];
    }
}