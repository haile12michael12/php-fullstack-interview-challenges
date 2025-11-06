<?php

namespace App\Contracts\Repository;

interface UserRepositoryInterface
{
    public function findById(int $id): ?array;
    public function findByEmail(string $email): ?array;
    public function create(array $data): int;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function all(): array;
    public function findByUsername(string $username): ?array;
}