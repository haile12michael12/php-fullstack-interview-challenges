<?php

namespace App\Connection;

class PostgreSqlConnection implements ConnectionInterface
{
    protected $pdo;
    protected $config;
    protected $connected = false;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'host' => 'localhost',
            'port' => 5432,
            'database' => 'abstract_factory',
            'username' => 'postgres',
            'password' => '',
        ], $config);
    }

    public function connect(): void
    {
        if ($this->connected) {
            return;
        }

        $dsn = "pgsql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']}";
        
        $this->pdo = new \PDO($dsn, $this->config['username'], $this->config['password'], [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
        
        $this->connected = true;
    }

    public function disconnect(): void
    {
        $this->pdo = null;
        $this->connected = false;
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