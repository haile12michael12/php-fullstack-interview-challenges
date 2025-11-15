<?php

namespace Drivers;

use Interfaces\DBConnectionInterface;
use Core\Logger;

class PostgresConnection implements DBConnectionInterface
{
    private $pdo;
    private $config;
    private $connected = false;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'host' => 'localhost',
            'port' => 5432,
            'database' => 'test',
            'username' => 'postgres',
            'password' => '',
        ], $config);
    }

    public function connect(): void
    {
        if ($this->connected) {
            return;
        }

        try {
            $dsn = "pgsql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']}";
            $this->pdo = new \PDO($dsn, $this->config['username'], $this->config['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);
            
            $this->connected = true;
            Logger::getInstance()->info("PostgreSQL connection established");
        } catch (\PDOException $e) {
            Logger::getInstance()->error("PostgreSQL connection failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function disconnect(): void
    {
        $this->pdo = null;
        $this->connected = false;
        Logger::getInstance()->info("PostgreSQL connection closed");
    }

    public function isConnected(): bool
    {
        return $this->connected;
    }

    public function query(string $sql, array $params = [])
    {
        $this->connect();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function prepare(string $sql)
    {
        $this->connect();
        return $this->pdo->prepare($sql);
    }

    public function execute(string $sql, array $params = [])
    {
        $this->connect();
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function getLastInsertId()
    {
        $this->connect();
        return $this->pdo->lastInsertId();
    }
}