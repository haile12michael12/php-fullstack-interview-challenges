<?php

namespace Factories;

use Interfaces\DBFactoryInterface;
use Interfaces\DBConnectionInterface;
use Drivers\MySQLConnection;

class MySQLFactory implements DBFactoryInterface
{
    private $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function createConnection(): DBConnectionInterface
    {
        return new MySQLConnection($this->config);
    }
}