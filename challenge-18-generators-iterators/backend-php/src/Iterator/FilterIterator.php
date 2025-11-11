<?php

namespace App\Iterator;

class FilterIterator implements \Iterator
{
    private \Iterator $iterator;
    private $filter;
    private $current = null;
    private int $key = 0;

    public function __construct(\Iterator $iterator, callable $filter)
    {
        $this->iterator = $iterator;
        $this->filter = $filter;
        $this->rewind();
    }

    public function current(): mixed
    {
        return $this->current;
    }

    public function next(): void
    {
        $this->iterator->next();
        $this->findNextValid();
    }

    public function key(): int
    {
        return $this->key;
    }

    public function valid(): bool
    {
        return $this->iterator->valid() && $this->current !== null;
    }

    public function rewind(): void
    {
        $this->iterator->rewind();
        $this->key = 0;
        $this->findNextValid();
    }

    private function findNextValid(): void
    {
        $this->current = null;
        
        while ($this->iterator->valid()) {
            $item = $this->iterator->current();
            if (call_user_func($this->filter, $item)) {
                $this->current = $item;
                return;
            }
            $this->iterator->next();
            $this->key++;
        }
    }
}