<?php

namespace App\Application;

use App\Factory\DatabaseFactoryInterface;
use App\Pool\ConnectionPool;

class DatabaseManager
{
    protected $factory;
    protected $pool;
    protected $connections = [];

    public function __construct(DatabaseFactoryInterface $factory, int $poolSize = 10)
    {
        $this->factory = $factory;
        $this->pool = new ConnectionPool($factory, $poolSize);
    }

    public function getConnection()
    {
        return $this->pool->getConnection();
    }

    public function releaseConnection($connection)
    {
        $this->pool->releaseConnection($connection);
    }

    public function query(string $sql, array $params = [])
    {
        $connection = $this->getConnection();
        try {
            $result = $connection->query($sql, $params);
            $this->releaseConnection($connection);
            return $result;
        } catch (\Exception $e) {
            $this->releaseConnection($connection);
            throw $e;
        }
    }

    public function execute(string $sql, array $params = [])
    {
        $connection = $this->getConnection();
        try {
            $result = $connection->execute($sql, $params);
            $this->releaseConnection($connection);
            return $result;
        } catch (\Exception $e) {
            $this->releaseConnection($connection);
            throw $e;
        }
    }

    public function getPoolStats()
    {
        return [
            'pool_size' => $this->pool->getSize(),
            'available_connections' => $this->pool->getAvailableCount(),
            'max_size' => $this->pool->getMaxSize()
        ];
    }
}