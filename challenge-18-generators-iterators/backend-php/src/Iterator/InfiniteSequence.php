<?php

namespace App\Iterator;

class InfiniteSequence implements \Iterator
{
    private int $current;
    private int $start;
    private int $step;

    public function __construct(int $start = 0, int $step = 1)
    {
        $this->start = $start;
        $this->step = $step;
        $this->current = $start;
    }

    public function current(): mixed
    {
        return $this->current;
    }

    public function next(): void
    {
        $this->current += $this->step;
    }

    public function key(): int
    {
        return $this->current;
    }

    public function valid(): bool
    {
        // Always valid for infinite sequence
        return true;
    }

    public function rewind(): void
    {
        $this->current = $this->start;
    }
}