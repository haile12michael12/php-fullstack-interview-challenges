<?php

namespace App\Storage;

use App\Core\Contracts\StorageInterface;
use App\Model\LogEntry;

class ExternalStorage implements StorageInterface
{
    private string $apiUrl;
    private string $apiKey;

    public function __construct(string $apiUrl, string $apiKey)
    {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    public function save(LogEntry $logEntry): void
    {
        $data = $logEntry->toArray();
        
        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\n" .
                           "Authorization: Bearer {$this->apiKey}\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
            ],
        ];
        
        $context = stream_context_create($options);
        file_get_contents($this->apiUrl, false, $context);
    }

    public function retrieve(string $id): ?LogEntry
    {
        // Implementation would call external API to retrieve log entry
        // This is a simplified version
        return null;
    }

    public function retrieveAll(array $filters = []): array
    {
        // Implementation would call external API to retrieve all log entries
        // This is a simplified version
        return [];
    }

    public function delete(string $id): bool
    {
        // Implementation would call external API to delete log entry
        // This is a simplified version
        return false;
    }
}