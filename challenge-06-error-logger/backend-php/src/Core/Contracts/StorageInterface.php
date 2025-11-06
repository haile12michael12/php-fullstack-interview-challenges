<?php

namespace App\Core\Contracts;

use App\Model\LogEntry;

interface StorageInterface
{
    public function save(LogEntry $logEntry): void;
    public function retrieve(string $id): ?LogEntry;
    public function retrieveAll(array $filters = []): array;
    public function delete(string $id): bool;
}