<?php

namespace App\Connection;

class MySqlConnection implements ConnectionInterface
{
    protected $pdo;
    protected $config;
    protected $connected = false;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'host' => 'localhost',
            'port' => 3306,
            'database' => 'abstract_factory',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ], $config);
    }

    public function connect(): void
    {
        if ($this->connected) {
            return;
        }

        $dsn = "mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']};charset={$this->config['charset']}";
        
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