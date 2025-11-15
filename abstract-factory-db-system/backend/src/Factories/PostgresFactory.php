<?php

namespace Factories;

use Interfaces\DBFactoryInterface;
use Interfaces\DBConnectionInterface;
use Drivers\PostgresConnection;

class PostgresFactory implements DBFactoryInterface
{
    private $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function createConnection(): DBConnectionInterface
    {
        return new PostgresConnection($this->config);
    }
}