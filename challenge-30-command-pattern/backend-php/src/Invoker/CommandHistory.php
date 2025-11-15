<?php

namespace App\Invoker;

use App\Command\CommandInterface;

class CommandHistory
{
    private array $history = [];
    private array $undoneCommands = [];
    private int $maxSize;

    public function __construct(int $maxSize = 100)
    {
        $this->maxSize = $maxSize;
    }

    /**
     * Add a command to history
     *
     * @param CommandInterface $command
     */
    public function push(CommandInterface $command): void
    {
        // Clear undone commands when a new command is added
        $this->undoneCommands = [];
        
        $this->history[] = $command;
        
        // Limit history size
        if (count($this->history) > $this->maxSize) {
            array_shift($this->history);
        }
    }

    /**
     * Undo the last command
     *
     * @return CommandInterface|null
     */
    public function undo(): ?CommandInterface
    {
        if (empty($this->history)) {
            return null;
        }
        
        $command = array_pop($this->history);
        $this->undoneCommands[] = $command;
        
        // Limit undone commands size
        if (count($this->undoneCommands) > $this->maxSize) {
            array_shift($this->undoneCommands);
        }
        
        return $command;
    }

    /**
     * Redo the last undone command
     *
     * @return CommandInterface|null
     */
    public function redo(): ?CommandInterface
    {
        if (empty($this->undoneCommands)) {
            return null;
        }
        
        $command = array_pop($this->undoneCommands);
        $this->history[] = $command;
        
        // Limit history size
        if (count($this->history) > $this->maxSize) {
            array_shift($this->history);
        }
        
        return $command;
    }

    /**
     * Get command history
     *
     * @return array
     */
    public function getHistory(): array
    {
        return $this->history;
    }

    /**
     * Get undone commands
     *
     * @return array
     */
    public function getUndoneCommands(): array
    {
        return $this->undoneCommands;
    }

    /**
     * Check if undo is possible
     *
     * @return bool
     */
    public function canUndo(): bool
    {
        return !empty($this->history);
    }

    /**
     * Check if redo is possible
     *
     * @return bool
     */
    public function canRedo(): bool
    {
        return !empty($this->undoneCommands);
    }

    /**
     * Clear history
     */
    public function clear(): void
    {
        $this->history = [];
        $this->undoneCommands = [];
    }

    /**
     * Get history size
     *
     * @return int
     */
    public function size(): int
    {
        return count($this->history);
    }
}