<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;
    public function findByName(string $name): array;
    public function findAll(): array;
    public function save(Product $product): void;
    public function delete(int $id): void;
}