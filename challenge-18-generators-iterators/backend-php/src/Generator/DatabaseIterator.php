<?php

namespace App\Generator;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;

class DatabaseIterator
{
    private Connection $connection;
    private string $tableName;
    private int $batchSize;

    public function __construct(string $tableName, int $batchSize = 100)
    {
        $this->tableName = $tableName;
        $this->batchSize = $batchSize;
        
        // Create connection using config
        $config = new Configuration();
        $connectionParams = [
            'dbname' => $_ENV['DB_NAME'] ?? 'generators_iterators',
            'user' => $_ENV['DB_USER'] ?? 'root',
            'password' => $_ENV['DB_PASS'] ?? '',
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'driver' => 'pdo_mysql',
        ];
        
        $this->connection = DriverManager::getConnection($connectionParams, $config);
    }

    /**
     * Generator that yields rows from a database table in batches
     */
    public function fetchRows(): \Generator
    {
        $offset = 0;
        
        do {
            $sql = "SELECT * FROM {$this->tableName} LIMIT :limit OFFSET :offset";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('limit', $this->batchSize);
            $stmt->bindValue('offset', $offset);
            
            $result = $stmt->executeQuery();
            $rows = $result->fetchAllAssociative();
            
            if (empty($rows)) {
                break;
            }
            
            foreach ($rows as $row) {
                yield $row;
            }
            
            $offset += $this->batchSize;
        } while (count($rows) === $this->batchSize);
    }

    /**
     * Generator that yields aggregated data
     */
    public function fetchAggregatedData(string $groupByColumn): \Generator
    {
        $sql = "SELECT {$groupByColumn}, COUNT(*) as count FROM {$this->tableName} GROUP BY {$groupByColumn}";
        $stmt = $this->connection->prepare($sql);
        $result = $stmt->executeQuery();
        
        while ($row = $result->fetchAssociative()) {
            yield $row;
        }
    }
}