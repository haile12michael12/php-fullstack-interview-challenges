<?php

namespace Challenge02\Contracts\Repositories;

use Challenge02\Domain\User;

interface UserRepositoryInterface
{
    public function findById(string $id): ?User;
    public function findByEmail(string $email): ?User;
    public function save(User $user): bool;
    public function update(User $user): bool;
    public function delete(string $id): bool;
    public function exists(string $email): bool;
}