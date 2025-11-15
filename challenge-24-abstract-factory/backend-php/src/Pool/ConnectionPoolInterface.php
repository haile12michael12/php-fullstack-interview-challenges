<?php

namespace App\Pool;

use App\Connection\ConnectionInterface;

interface ConnectionPoolInterface
{
    public function getConnection(): ConnectionInterface;
    
    public function releaseConnection(ConnectionInterface $connection): void;
    
    public function getSize(): int;
    
    public function getAvailableCount(): int;
    
    public function getMaxSize(): int;
    
    public function setMaxSize(int $maxSize): void;
}