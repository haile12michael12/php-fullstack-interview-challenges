<?php

namespace App\Storage;

use App\Core\Contracts\StorageInterface;
use App\Model\LogEntry;

class DatabaseStorage implements StorageInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->createTableIfNotExists();
    }

    public function save(LogEntry $logEntry): void
    {
        $sql = "INSERT INTO log_entries (id, level, message, context, timestamp, ip_address, user_agent) 
                VALUES (:id, :level, :message, :context, :timestamp, :ip_address, :user_agent)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $logEntry->getId(),
            ':level' => $logEntry->getLevel(),
            ':message' => $logEntry->getMessage(),
            ':context' => json_encode($logEntry->getContext()),
            ':timestamp' => $logEntry->getTimestamp(),
            ':ip_address' => $logEntry->getIpAddress(),
            ':user_agent' => $logEntry->getUserAgent()
        ]);
    }

    public function retrieve(string $id): ?LogEntry
    {
        $sql = "SELECT * FROM log_entries WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            $logEntry = new LogEntry();
            $logEntry->setId($data['id']);
            $logEntry->setLevel($data['level']);
            $logEntry->setMessage($data['message']);
            $logEntry->setContext(json_decode($data['context'], true) ?: []);
            $logEntry->setTimestamp($data['timestamp']);
            $logEntry->setIpAddress($data['ip_address']);
            $logEntry->setUserAgent($data['user_agent']);
            return $logEntry;
        }

        return null;
    }

    public function retrieveAll(array $filters = []): array
    {
        $sql = "SELECT * FROM log_entries ORDER BY timestamp DESC";
        $stmt = $this->pdo->query($sql);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $logEntries = [];
        foreach ($results as $data) {
            $logEntry = new LogEntry();
            $logEntry->setId($data['id']);
            $logEntry->setLevel($data['level']);
            $logEntry->setMessage($data['message']);
            $logEntry->setContext(json_decode($data['context'], true) ?: []);
            $logEntry->setTimestamp($data['timestamp']);
            $logEntry->setIpAddress($data['ip_address']);
            $logEntry->setUserAgent($data['user_agent']);
            $logEntries[] = $logEntry;
        }

        return $logEntries;
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM log_entries WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    private function createTableIfNotExists(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS log_entries (
            id VARCHAR(255) PRIMARY KEY,
            level VARCHAR(20) NOT NULL,
            message TEXT NOT NULL,
            context TEXT,
            timestamp DATETIME NOT NULL,
            ip_address VARCHAR(45),
            user_agent TEXT
        )";
        
        $this->pdo->exec($sql);
    }
}