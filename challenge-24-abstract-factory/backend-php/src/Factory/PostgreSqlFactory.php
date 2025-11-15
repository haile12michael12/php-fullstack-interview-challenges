<?php

namespace App\Factory;

use App\Connection\PostgreSqlConnection;
use App\Connection\ConnectionInterface;

class PostgreSqlFactory implements DatabaseFactoryInterface
{
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function createConnection(): ConnectionInterface
    {
        return new PostgreSqlConnection($this->config);
    }

    public function createQueryBuilder(): object
    {
        return new \App\Connection\PostgreSqlQueryBuilder();
    }

    public function createSchemaManager(): object
    {
        return new \App\Schema\PostgreSqlSchemaManager();
    }
}