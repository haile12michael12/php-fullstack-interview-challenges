<?php

namespace App\Factory;

use App\Connection\MySqlConnection;
use App\Connection\ConnectionInterface;

class MySqlFactory implements DatabaseFactoryInterface
{
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function createConnection(): ConnectionInterface
    {
        return new MySqlConnection($this->config);
    }

    public function createQueryBuilder(): object
    {
        return new \App\Connection\MySqlQueryBuilder();
    }

    public function createSchemaManager(): object
    {
        return new \App\Schema\MySqlSchemaManager();
    }
}