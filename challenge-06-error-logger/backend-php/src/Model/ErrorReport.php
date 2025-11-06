<?php

namespace App\Model;

class ErrorReport
{
    private string $id;
    private string $title;
    private string $description;
    private int $count;
    private string $firstOccurrence;
    private string $lastOccurrence;
    private array $logEntries;

    public function __construct()
    {
        $this->logEntries = [];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function getFirstOccurrence(): string
    {
        return $this->firstOccurrence;
    }

    public function setFirstOccurrence(string $firstOccurrence): void
    {
        $this->firstOccurrence = $firstOccurrence;
    }

    public function getLastOccurrence(): string
    {
        return $this->lastOccurrence;
    }

    public function setLastOccurrence(string $lastOccurrence): void
    {
        $this->lastOccurrence = $lastOccurrence;
    }

    public function getLogEntries(): array
    {
        return $this->logEntries;
    }

    public function setLogEntries(array $logEntries): void
    {
        $this->logEntries = $logEntries;
    }

    public function addLogEntry(LogEntry $logEntry): void
    {
        $this->logEntries[] = $logEntry;
    }
}