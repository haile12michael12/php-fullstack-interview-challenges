<?php

namespace App\Storage;

use App\Core\Contracts\StorageInterface;
use App\Model\LogEntry;

class FileStorage implements StorageInterface
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->ensureFileExists();
    }

    public function save(LogEntry $logEntry): void
    {
        $data = json_encode($logEntry->toArray()) . PHP_EOL;
        file_put_contents($this->filePath, $data, FILE_APPEND | LOCK_EX);
    }

    public function retrieve(string $id): ?LogEntry
    {
        // Implementation would read from file and find entry by ID
        // This is a simplified version
        return null;
    }

    public function retrieveAll(array $filters = []): array
    {
        // Implementation would read all entries from file
        // This is a simplified version
        return [];
    }

    public function delete(string $id): bool
    {
        // Implementation would delete entry by ID
        // This is a simplified version
        return false;
    }

    private function ensureFileExists(): void
    {
        if (!file_exists($this->filePath)) {
            $directory = dirname($this->filePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            touch($this->filePath);
        }
    }
}