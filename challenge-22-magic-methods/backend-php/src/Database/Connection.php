<?php

namespace App\Database;

class Connection
{
    protected \PDO $pdo;
    protected array $config;
    protected array $transactions = [];
    protected int $transactionLevel = 0;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'driver' => 'mysql',
            'host' => 'localhost',
            'port' => 3306,
            'database' => 'magic_methods',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ], $config);
        
        $this->connect();
    }

    protected function connect(): void
    {
        $dsn = "{$this->config['driver']}:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']};charset={$this->config['charset']}";
        $this->pdo = new \PDO($dsn, $this->config['username'], $this->config['password'], [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
    }

    public function prepare(string $sql): \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    public function query(string $sql): \PDOStatement
    {
        return $this->pdo->query($sql);
    }

    public function exec(string $sql): int
    {
        return $this->pdo->exec($sql);
    }

    public function lastInsertId(string $name = null): string
    {
        return $this->pdo->lastInsertId($name);
    }

    public function beginTransaction(): bool
    {
        $this->transactionLevel++;
        if ($this->transactionLevel === 1) {
            return $this->pdo->beginTransaction();
        }
        
        return true;
    }

    public function commit(): bool
    {
        if ($this->transactionLevel > 0) {
            $this->transactionLevel--;
            if ($this->transactionLevel === 0) {
                return $this->pdo->commit();
            }
        }
        
        return true;
    }

    public function rollBack(): bool
    {
        if ($this->transactionLevel > 0) {
            $this->transactionLevel = 0;
            return $this->pdo->rollBack();
        }
        
        return false;
    }

    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    public function __call(string $name, array $arguments)
    {
        // Delegate PDO methods
        if (method_exists($this->pdo, $name)) {
            return $this->pdo->$name(...$arguments);
        }
        
        throw new \BadMethodCallException("Method {$name} does not exist.");
    }

    public function __get(string $name)
    {
        return $this->pdo->$name ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->pdo->$name = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->pdo->$name);
    }

    public function __unset(string $name): void
    {
        unset($this->pdo->$name);
    }

    public function __toString(): string
    {
        return "Database Connection: {$this->config['driver']}://{$this->config['host']}:{$this->config['port']}/{$this->config['database']}";
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}