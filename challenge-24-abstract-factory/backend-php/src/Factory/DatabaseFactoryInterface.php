<?php

namespace App\Factory;

use App\Connection\ConnectionInterface;

interface DatabaseFactoryInterface
{
    public function createConnection(): ConnectionInterface;
    
    public function createQueryBuilder(): object;
    
    public function createSchemaManager(): object;
}