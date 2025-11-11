<?php

namespace App\Services\Implementations;

use App\Services\Interfaces\LoggerInterface;

class DatabaseLogger implements LoggerInterface
{
    private \PDO $connection;
    private string $table;

    public function __construct(\PDO $connection, string $table = 'logs')
    {
        $this->connection = $connection;
        $this->table = $table;
    }

    public function log(string $level, string $message): void
    {
        $sql = "INSERT INTO {$this->table} (level, message, created_at) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$level, $message, date('Y-m-d H:i:s')]);
    }

    public function info(string $message): void
    {
        $this->log('INFO', $message);
    }

    public function error(string $message): void
    {
        $this->log('ERROR', $message);
    }

    public function debug(string $message): void
    {
        $this->log('DEBUG', $message);
    }

    public function warning(string $message): void
    {
        $this->log('WARNING', $message);
    }

    public function setTable(string $table): void
    {
        $this->table = $table;
    }
}