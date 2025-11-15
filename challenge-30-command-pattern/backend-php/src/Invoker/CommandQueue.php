<?php

namespace App\Invoker;

use App\Command\CommandInterface;

class CommandQueue
{
    private array $queue = [];
    private int $maxSize;

    public function __construct(int $maxSize = 100)
    {
        $this->maxSize = $maxSize;
    }

    /**
     * Add a command to the queue
     *
     * @param CommandInterface $command
     * @return bool
     */
    public function enqueue(CommandInterface $command): bool
    {
        if (count($this->queue) >= $this->maxSize) {
            return false;
        }
        
        $this->queue[] = $command;
        return true;
    }

    /**
     * Remove and return the first command from the queue
     *
     * @return CommandInterface|null
     */
    public function dequeue(): ?CommandInterface
    {
        if (empty($this->queue)) {
            return null;
        }
        
        return array_shift($this->queue);
    }

    /**
     * Get the next command without removing it
     *
     * @return CommandInterface|null
     */
    public function peek(): ?CommandInterface
    {
        if (empty($this->queue)) {
            return null;
        }
        
        return $this->queue[0];
    }

    /**
     * Get the size of the queue
     *
     * @return int
     */
    public function size(): int
    {
        return count($this->queue);
    }

    /**
     * Check if the queue is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->queue);
    }

    /**
     * Check if the queue is full
     *
     * @return bool
     */
    public function isFull(): bool
    {
        return count($this->queue) >= $this->maxSize;
    }

    /**
     * Get all commands in the queue
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->queue;
    }

    /**
     * Clear the queue
     */
    public function clear(): void
    {
        $this->queue = [];
    }
}