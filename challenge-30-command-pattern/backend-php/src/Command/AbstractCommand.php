<?php

namespace App\Command;

abstract class AbstractCommand implements CommandInterface
{
    protected string $name;
    protected string $description;
    protected array $metadata;
    protected bool $executed = false;

    public function __construct(string $name, string $description, array $metadata = [])
    {
        $this->name = $name;
        $this->description = $description;
        $this->metadata = array_merge($metadata, [
            'created_at' => date('c'),
            'id' => uniqid()
        ]);
    }

    /**
     * Get command name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get command description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get command metadata
     *
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Mark command as executed
     */
    protected function markAsExecuted(): void
    {
        $this->executed = true;
        $this->metadata['executed_at'] = date('c');
    }

    /**
     * Mark command as undone
     */
    protected function markAsUndone(): void
    {
        $this->executed = false;
        $this->metadata['undone_at'] = date('c');
    }

    /**
     * Check if command has been executed
     *
     * @return bool
     */
    public function isExecuted(): bool
    {
        return $this->executed;
    }
}