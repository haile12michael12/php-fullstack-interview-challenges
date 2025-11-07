<?php

namespace App\Models\Repositories;

use App\Contracts\Repository\UserRepositoryInterface;

class MemoryUserRepository implements UserRepositoryInterface
{
    private array $users = [];
    private int $nextId = 1;

    public function findById(int $id): ?array
    {
        return $this->users[$id] ?? null;
    }

    public function findByEmail(string $email): ?array
    {
        foreach ($this->users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }

    public function create(array $data): int
    {
        $id = $this->nextId++;
        $data['id'] = $id;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->users[$id] = $data;
        return $id;
    }

    public function update(int $id, array $data): bool
    {
        if (!isset($this->users[$id])) {
            return false;
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->users[$id] = array_merge($this->users[$id], $data);
        return true;
    }

    public function delete(int $id): bool
    {
        if (!isset($this->users[$id])) {
            return false;
        }
        
        unset($this->users[$id]);
        return true;
    }

    public function all(): array
    {
        return $this->users;
    }

    public function findByUsername(string $username): ?array
    {
        foreach ($this->users as $user) {
            if ($user['username'] === $username) {
                return $user;
            }
        }
        return null;
    }
}