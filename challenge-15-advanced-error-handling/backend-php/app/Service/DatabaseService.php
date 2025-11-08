<?php

namespace App\Service;

use App\Exception\DatabaseException;
use App\Logger\LoggerFactory;
use PDO;
use PDOException;
use Psr\Log\LoggerInterface;

class DatabaseService
{
    private LoggerInterface $logger;
    private ?PDO $connection;
    private array $config;

    public function __construct(array $config = [])
    {
        $this->logger = LoggerFactory::create('database');
        $this->config = $config ?: $this->getDefaultConfig();
        $this->connection = null;
    }

    /**
     * Get database connection
     *
     * @return PDO
     * @throws DatabaseException
     */
    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO(
                    $this->config['dsn'],
                    $this->config['username'],
                    $this->config['password'],
                    $this->config['options']
                );
                
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->logger->info('Database connection established');
            } catch (PDOException $e) {
                $this->logger->error('Database connection failed', [
                    'message' => $e->getMessage(),
                    'dsn' => $this->config['dsn']
                ]);
                
                throw new DatabaseException(
                    "Database connection failed: " . $e->getMessage(),
                    500,
                    $e,
                    null,
                    [],
                    ['dsn' => $this->config['dsn']]
                );
            }
        }

        return $this->connection;
    }

    /**
     * Execute a query with error handling
     *
     * @param string $sql
     * @param array $params
     * @return array
     * @throws DatabaseException
     */
    public function query(string $sql, array $params = []): array
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            
            $this->logger->debug('Query executed successfully', [
                'sql' => $sql,
                'params' => $params
            ]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logger->error('Database query failed', [
                'message' => $e->getMessage(),
                'sql' => $sql,
                'params' => $params
            ]);
            
            throw new DatabaseException(
                "Database query failed: " . $e->getMessage(),
                500,
                $e,
                $sql,
                $params
            );
        }
    }

    /**
     * Execute a statement (INSERT, UPDATE, DELETE)
     *
     * @param string $sql
     * @param array $params
     * @return int Number of affected rows
     * @throws DatabaseException
     */
    public function execute(string $sql, array $params = []): int
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            
            $affectedRows = $stmt->rowCount();
            
            $this->logger->debug('Statement executed successfully', [
                'sql' => $sql,
                'params' => $params,
                'affected_rows' => $affectedRows
            ]);
            
            return $affectedRows;
        } catch (PDOException $e) {
            $this->logger->error('Database statement failed', [
                'message' => $e->getMessage(),
                'sql' => $sql,
                'params' => $params
            ]);
            
            throw new DatabaseException(
                "Database statement failed: " . $e->getMessage(),
                500,
                $e,
                $sql,
                $params
            );
        }
    }

    /**
     * Begin transaction
     *
     * @return void
     * @throws DatabaseException
     */
    public function beginTransaction(): void
    {
        try {
            $this->getConnection()->beginTransaction();
            $this->logger->debug('Database transaction started');
        } catch (PDOException $e) {
            $this->logger->error('Failed to start database transaction', [
                'message' => $e->getMessage()
            ]);
            
            throw new DatabaseException(
                "Failed to start database transaction: " . $e->getMessage(),
                500,
                $e
            );
        }
    }

    /**
     * Commit transaction
     *
     * @return void
     * @throws DatabaseException
     */
    public function commit(): void
    {
        try {
            $this->getConnection()->commit();
            $this->logger->debug('Database transaction committed');
        } catch (PDOException $e) {
            $this->logger->error('Failed to commit database transaction', [
                'message' => $e->getMessage()
            ]);
            
            throw new DatabaseException(
                "Failed to commit database transaction: " . $e->getMessage(),
                500,
                $e
            );
        }
    }

    /**
     * Rollback transaction
     *
     * @return void
     * @throws DatabaseException
     */
    public function rollback(): void
    {
        try {
            $this->getConnection()->rollback();
            $this->logger->debug('Database transaction rolled back');
        } catch (PDOException $e) {
            $this->logger->error('Failed to rollback database transaction', [
                'message' => $e->getMessage()
            ]);
            
            throw new DatabaseException(
                "Failed to rollback database transaction: " . $e->getMessage(),
                500,
                $e
            );
        }
    }

    /**
     * Get default database configuration
     *
     * @return array
     */
    private function getDefaultConfig(): array
    {
        return [
            'dsn' => getenv('DB_DSN') ?: 'mysql:host=localhost;dbname=test',
            'username' => getenv('DB_USERNAME') ?: 'root',
            'password' => getenv('DB_PASSWORD') ?: '',
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ];
    }

    /**
     * Close database connection
     *
     * @return void
     */
    public function close(): void
    {
        if ($this->connection !== null) {
            $this->connection = null;
            $this->logger->info('Database connection closed');
        }
    }
}