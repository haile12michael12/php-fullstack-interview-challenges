<?php

namespace App\Service;

use App\Command\CommandInterface;
use App\Invoker\CommandQueue;
use App\Invoker\CommandHistory;
use App\Invoker\Worker;

class CommandService
{
    private CommandQueue $queue;
    private CommandHistory $history;
    private Worker $worker;

    public function __construct()
    {
        $this->queue = new CommandQueue();
        $this->history = new CommandHistory();
        $this->worker = new Worker($this->queue, $this->history);
    }

    /**
     * Queue a command for later execution
     *
     * @param CommandInterface $command
     * @return bool
     */
    public function queueCommand(CommandInterface $command): bool
    {
        return $this->queue->enqueue($command);
    }

    /**
     * Execute a command immediately
     *
     * @param CommandInterface $command
     * @return mixed
     */
    public function executeCommand(CommandInterface $command)
    {
        return $this->worker->executeCommand($command);
    }

    /**
     * Process all queued commands
     *
     * @return int Number of commands processed
     */
    public function processQueue(): int
    {
        if (!$this->worker->isRunning()) {
            $this->worker->start();
        }
        
        return $this->worker->processQueue();
    }

    /**
     * Undo the last command
     *
     * @return bool
     */
    public function undoLastCommand(): bool
    {
        return $this->worker->undoLastCommand();
    }

    /**
     * Redo the last undone command
     *
     * @return bool
     */
    public function redoLastCommand(): bool
    {
        return $this->worker->redoLastCommand();
    }

    /**
     * Get command history
     *
     * @return array
     */
    public function getHistory(): array
    {
        $history = $this->history->getHistory();
        return $this->formatCommandsForOutput($history);
    }

    /**
     * Get undone commands
     *
     * @return array
     */
    public function getUndoneCommands(): array
    {
        $undone = $this->history->getUndoneCommands();
        return $this->formatCommandsForOutput($undone);
    }

    /**
     * Get queue status
     *
     * @return array
     */
    public function getQueueStatus(): array
    {
        return $this->worker->getQueueStatus();
    }

    /**
     * Get history status
     *
     * @return array
     */
    public function getHistoryStatus(): array
    {
        return $this->worker->getHistoryStatus();
    }

    /**
     * Start the worker
     */
    public function startWorker(): void
    {
        $this->worker->start();
    }

    /**
     * Stop the worker
     */
    public function stopWorker(): void
    {
        $this->worker->stop();
    }

    /**
     * Clear command history
     */
    public function clearHistory(): void
    {
        $this->history->clear();
    }

    /**
     * Clear command queue
     */
    public function clearQueue(): void
    {
        $this->queue->clear();
    }

    /**
     * Format commands for output
     *
     * @param array $commands
     * @return array
     */
    private function formatCommandsForOutput(array $commands): array
    {
        $formatted = [];
        
        foreach ($commands as $command) {
            if ($command instanceof CommandInterface) {
                $formatted[] = [
                    'name' => $command->getName(),
                    'description' => $command->getDescription(),
                    'metadata' => $command->getMetadata(),
                    'executed' => $command->isExecuted()
                ];
            }
        }
        
        return $formatted;
    }
}