<?php

namespace App\Domain\Entity;

class Order
{
    private int $id;
    private int $userId;
    private array $items;
    private float $totalAmount;
    private string $status;
    private \DateTimeImmutable $createdAt;

    public function __construct(
        int $id,
        int $userId,
        array $items,
        float $totalAmount,
        string $status,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->items = $items;
        $this->totalAmount = $totalAmount;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function setTotalAmount(float $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}