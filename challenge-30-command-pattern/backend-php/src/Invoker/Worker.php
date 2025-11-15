<?php

namespace App\Invoker;

use App\Command\CommandInterface;

class Worker
{
    private CommandQueue $queue;
    private CommandHistory $history;
    private bool $running = false;

    public function __construct(CommandQueue $queue, CommandHistory $history)
    {
        $this->queue = $queue;
        $this->history = $history;
    }

    /**
     * Start the worker
     */
    public function start(): void
    {
        $this->running = true;
        echo "Worker started\n";
    }

    /**
     * Stop the worker
     */
    public function stop(): void
    {
        $this->running = false;
        echo "Worker stopped\n";
    }

    /**
     * Process commands in the queue
     *
     * @return int Number of commands processed
     */
    public function processQueue(): int
    {
        if (!$this->running) {
            throw new \RuntimeException('Worker is not running');
        }

        $processed = 0;
        
        while (!$this->queue->isEmpty() && $this->running) {
            $command = $this->queue->dequeue();
            
            if ($command instanceof CommandInterface) {
                try {
                    echo "Executing command: " . $command->getName() . "\n";
                    $result = $command->execute();
                    $this->history->push($command);
                    $processed++;
                    
                    // Simulate some processing time
                    usleep(100000); // 0.1 seconds
                } catch (\Exception $e) {
                    echo "Error executing command: " . $e->getMessage() . "\n";
                }
            }
        }
        
        return $processed;
    }

    /**
     * Execute a single command immediately
     *
     * @param CommandInterface $command
     * @return mixed
     */
    public function executeCommand(CommandInterface $command)
    {
        try {
            echo "Executing command: " . $command->getName() . "\n";
            $result = $command->execute();
            $this->history->push($command);
            return $result;
        } catch (\Exception $e) {
            echo "Error executing command: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Undo the last command
     *
     * @return bool
     */
    public function undoLastCommand(): bool
    {
        $command = $this->history->undo();
        
        if ($command instanceof CommandInterface) {
            try {
                echo "Undoing command: " . $command->getName() . "\n";
                $command->undo();
                return true;
            } catch (\Exception $e) {
                echo "Error undoing command: " . $e->getMessage() . "\n";
                // Re-add to history since undo failed
                $this->history->push($command);
                return false;
            }
        }
        
        return false;
    }

    /**
     * Redo the last undone command
     *
     * @return bool
     */
    public function redoLastCommand(): bool
    {
        $command = $this->history->redo();
        
        if ($command instanceof CommandInterface) {
            try {
                echo "Redoing command: " . $command->getName() . "\n";
                $result = $command->execute();
                return true;
            } catch (\Exception $e) {
                echo "Error redoing command: " . $e->getMessage() . "\n";
                // Remove from history since redo failed
                array_pop($this->history->getHistory());
                return false;
            }
        }
        
        return false;
    }

    /**
     * Check if worker is running
     *
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->running;
    }

    /**
     * Get queue status
     *
     * @return array
     */
    public function getQueueStatus(): array
    {
        return [
            'size' => $this->queue->size(),
            'isEmpty' => $this->queue->isEmpty(),
            'isFull' => $this->queue->isFull()
        ];
    }

    /**
     * Get history status
     *
     * @return array
     */
    public function getHistoryStatus(): array
    {
        return [
            'size' => $this->history->size(),
            'canUndo' => $this->history->canUndo(),
            'canRedo' => $this->history->canRedo()
        ];
    }
}