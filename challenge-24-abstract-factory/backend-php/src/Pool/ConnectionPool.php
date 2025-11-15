<?php

namespace App\Pool;

use App\Connection\ConnectionInterface;
use App\Factory\DatabaseFactoryInterface;

class ConnectionPool implements ConnectionPoolInterface
{
    protected $factory;
    protected $connections = [];
    protected $available = [];
    protected $maxSize;
    protected $currentSize = 0;

    public function __construct(DatabaseFactoryInterface $factory, int $maxSize = 10)
    {
        $this->factory = $factory;
        $this->maxSize = $maxSize;
    }

    public function getConnection(): ConnectionInterface
    {
        if (!empty($this->available)) {
            return array_pop($this->available);
        }

        if ($this->currentSize < $this->maxSize) {
            $connection = $this->factory->createConnection();
            $this->connections[] = $connection;
            $this->currentSize++;
            return $connection;
        }

        throw new \Exception('Connection pool is full');
    }

    public function releaseConnection(ConnectionInterface $connection): void
    {
        if (in_array($connection, $this->connections, true)) {
            $this->available[] = $connection;
        }
    }

    public function getSize(): int
    {
        return $this->currentSize;
    }

    public function getAvailableCount(): int
    {
        return count($this->available);
    }

    public function getMaxSize(): int
    {
        return $this->maxSize;
    }

    public function setMaxSize(int $maxSize): void
    {
        $this->maxSize = $maxSize;
    }
}