<?php

namespace App\Database;

class Connection
{
    protected static $instance = null;
    protected $pdo;
    protected $config;

    protected function __construct($config = [])
    {
        $this->config = array_merge([
            'driver' => 'mysql',
            'host' => 'localhost',
            'port' => 3306,
            'database' => 'lsb_challenge',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ], $config);
        
        $this->connect();
    }

    protected function connect()
    {
        $dsn = "{$this->config['driver']}:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']};charset={$this->config['charset']}";
        $this->pdo = new \PDO($dsn, $this->config['username'], $this->config['password'], [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
    }

    public static function getInstance($config = [])
    {
        if (static::$instance === null) {
            static::$instance = new static($config);
        }
        return static::$instance;
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function query($sql)
    {
        return $this->pdo->query($sql);
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}