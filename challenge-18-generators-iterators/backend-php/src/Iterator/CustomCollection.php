<?php

namespace App\Iterator;

class CustomCollection implements \Iterator, \Countable
{
    private array $items = [];
    private int $position = 0;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function add($item): void
    {
        $this->items[] = $item;
    }

    public function current(): mixed
    {
        return $this->items[$this->position] ?? null;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getItems(): array
    {
        return $this->items;
    }
}