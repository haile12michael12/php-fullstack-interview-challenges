<?php

declare(strict_types=1);

namespace SharedBackend\Core;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use SharedBackend\Core\Exceptions\DatabaseException;

/**
 * Advanced database abstraction layer with connection pooling and query optimization
 */
class Database
{
    private Connection $connection;
    private array $connections = [];
    private Config $config;
    private Logger $logger;

    public function __construct(Config $config, Logger $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->initializeDefaultConnection();
    }

    public function connection(string $name = null): Connection
    {
        if ($name === null) {
            return $this->connection;
        }

        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->createConnection($name);
        }

        return $this->connections[$name];
    }

    public function query(): QueryBuilder
    {
        return $this->connection->createQueryBuilder();
    }

    public function beginTransaction(): void
    {
        try {
            $this->connection->beginTransaction();
        } catch (Exception $e) {
            throw new DatabaseException('Failed to begin transaction: ' . $e->getMessage(), 0, $e);
        }
    }

    public function commit(): void
    {
        try {
            $this->connection->commit();
        } catch (Exception $e) {
            throw new DatabaseException('Failed to commit transaction: ' . $e->getMessage(), 0, $e);
        }
    }

    public function rollback(): void
    {
        try {
            $this->connection->rollback();
        } catch (Exception $e) {
            throw new DatabaseException('Failed to rollback transaction: ' . $e->getMessage(), 0, $e);
        }
    }

    public function executeQuery(string $sql, array $params = [], array $types = []): \Doctrine\DBAL\Result
    {
        try {
            $startTime = microtime(true);
            $result = $this->connection->executeQuery($sql, $params, $types);
            $executionTime = microtime(true) - $startTime;

            $this->logger->debug('Database query executed', [
                'sql' => $sql,
                'params' => $params,
                'execution_time' => $executionTime
            ]);

            return $result;
        } catch (Exception $e) {
            $this->logger->error('Database query failed', [
                'sql' => $sql,
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw new DatabaseException('Query execution failed: ' . $e->getMessage(), 0, $e);
        }
    }

    public function executeStatement(string $sql, array $params = [], array $types = []): int
    {
        try {
            $startTime = microtime(true);
            $result = $this->connection->executeStatement($sql, $params, $types);
            $executionTime = microtime(true) - $startTime;

            $this->logger->debug('Database statement executed', [
                'sql' => $sql,
                'params' => $params,
                'affected_rows' => $result,
                'execution_time' => $executionTime
            ]);

            return $result;
        } catch (Exception $e) {
            $this->logger->error('Database statement failed', [
                'sql' => $sql,
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw new DatabaseException('Statement execution failed: ' . $e->getMessage(), 0, $e);
        }
    }

    public function lastInsertId(): string|int
    {
        return $this->connection->lastInsertId();
    }

    public function isConnected(): bool
    {
        try {
            $this->connection->executeQuery('SELECT 1');
            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function reconnect(): void
    {
        try {
            $this->connection->close();
            $this->initializeDefaultConnection();
        } catch (Exception $e) {
            throw new DatabaseException('Failed to reconnect: ' . $e->getMessage(), 0, $e);
        }
    }

    private function initializeDefaultConnection(): void
    {
        $defaultConnection = $this->config->get('database.default', 'mysql');
        $this->connection = $this->createConnection($defaultConnection);
    }

    private function createConnection(string $name): Connection
    {
        $config = $this->config->get("database.connections.{$name}");
        
        if (!$config) {
            throw new DatabaseException("Database connection '{$name}' not configured");
        }

        $connectionParams = [
            'driver' => $config['driver'],
            'host' => $config['host'] ?? 'localhost',
            'port' => $config['port'] ?? 3306,
            'dbname' => $config['database'] ?? $config['dbname'] ?? null,
            'user' => $config['username'] ?? $config['user'] ?? 'root',
            'password' => $config['password'] ?? '',
            'charset' => $config['charset'] ?? 'utf8mb4',
        ];

        // Add SQLite specific configuration
        if ($config['driver'] === 'sqlite') {
            $connectionParams['path'] = $config['database'];
            unset($connectionParams['host'], $connectionParams['port'], $connectionParams['dbname']);
        }

        // Add PostgreSQL specific configuration
        if ($config['driver'] === 'pgsql') {
            $connectionParams['sslmode'] = $config['sslmode'] ?? 'prefer';
            if (isset($config['schema'])) {
                $connectionParams['schema'] = $config['schema'];
            }
        }

        try {
            $connection = DriverManager::getConnection($connectionParams);
            
            // Configure connection options
            $connection->getConfiguration()->setSQLLogger(null); // Disable SQL logging by default
            
            $this->logger->info("Database connection '{$name}' established");
            
            return $connection;
        } catch (Exception $e) {
            $this->logger->error("Failed to establish database connection '{$name}'", [
                'error' => $e->getMessage()
            ]);
            throw new DatabaseException("Failed to connect to database '{$name}': " . $e->getMessage(), 0, $e);
        }
    }
}
