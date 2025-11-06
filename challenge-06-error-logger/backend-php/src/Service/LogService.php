<?php

namespace App\Service;

use App\Storage\FileStorage;
use App\Model\LogEntry;

class LogService
{
    private FileStorage $storage;

    public function __construct(FileStorage $storage)
    {
        $this->storage = $storage;
    }

    public function getAllLogs(array $filters = []): array
    {
        return $this->storage->retrieveAll($filters);
    }

    public function getLogById(string $id): ?LogEntry
    {
        return $this->storage->retrieve($id);
    }

    public function deleteLog(string $id): bool
    {
        return $this->storage->delete($id);
    }

    public function searchLogs(string $query): array
    {
        // Implementation for searching logs
        return [];
    }
}