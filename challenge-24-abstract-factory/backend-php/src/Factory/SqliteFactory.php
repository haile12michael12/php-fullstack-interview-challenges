<?php

namespace App\Factory;

use App\Connection\SqliteConnection;
use App\Connection\ConnectionInterface;

class SqliteFactory implements DatabaseFactoryInterface
{
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function createConnection(): ConnectionInterface
    {
        return new SqliteConnection($this->config);
    }

    public function createQueryBuilder(): object
    {
        return new \App\Connection\SqliteQueryBuilder();
    }

    public function createSchemaManager(): object
    {
        return new \App\Schema\SqliteSchemaManager();
    }
}