<?php

namespace Challenge02\Infrastructure\Persistence;

use Challenge02\Contracts\Repositories\UserRepositoryInterface;
use Challenge02\Domain\User;
use PDO;

class PdoUserRepository implements UserRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(string $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new User($data) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new User($data) : null;
    }

    public function save(User $user): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (id, email, password, first_name, last_name, phone, is_active, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        
        return $stmt->execute([
            $user->id,
            $user->email,
            $user->password,
            $user->first_name,
            $user->last_name,
            $user->phone,
            $user->is_active,
            $user->email_verified_at,
            $user->created_at,
            $user->updated_at
        ]);
    }

    public function update(User $user): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE users SET email = ?, password = ?, first_name = ?, last_name = ?, phone = ?, is_active = ?, email_verified_at = ?, updated_at = ? WHERE id = ?'
        );
        
        return $stmt->execute([
            $user->email,
            $user->password,
            $user->first_name,
            $user->last_name,
            $user->phone,
            $user->is_active,
            $user->email_verified_at,
            $user->updated_at,
            $user->id
        ]);
    }

    public function delete(string $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function exists(string $email): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
}