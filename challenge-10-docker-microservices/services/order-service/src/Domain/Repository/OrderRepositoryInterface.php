<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Order;

interface OrderRepositoryInterface
{
    public function findById(int $id): ?Order;
    public function findByUserId(int $userId): array;
    public function findAll(): array;
    public function save(Order $order): void;
    public function delete(int $id): void;
}