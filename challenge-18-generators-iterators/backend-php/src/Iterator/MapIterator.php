<?php

namespace App\Iterator;

class MapIterator implements \Iterator
{
    private \Iterator $iterator;
    private $mapper;
    private $current = null;

    public function __construct(\Iterator $iterator, callable $mapper)
    {
        $this->iterator = $iterator;
        $this->mapper = $mapper;
    }

    public function current(): mixed
    {
        return $this->current;
    }

    public function next(): void
    {
        $this->iterator->next();
        $this->updateCurrent();
    }

    public function key(): int
    {
        return $this->iterator->key();
    }

    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    public function rewind(): void
    {
        $this->iterator->rewind();
        $this->updateCurrent();
    }

    private function updateCurrent(): void
    {
        if ($this->iterator->valid()) {
            $this->current = call_user_func($this->mapper, $this->iterator->current());
        }
    }
}