<?php

use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testMySqlFactoryCreation()
    {
        $factory = new \App\Factory\MySqlFactory();
        $this->assertInstanceOf(\App\Factory\DatabaseFactoryInterface::class, $factory);
    }
    
    public function testPostgreSqlFactoryCreation()
    {
        $factory = new \App\Factory\PostgreSqlFactory();
        $this->assertInstanceOf(\App\Factory\DatabaseFactoryInterface::class, $factory);
    }
    
    public function testSqliteFactoryCreation()
    {
        $factory = new \App\Factory\SqliteFactory();
        $this->assertInstanceOf(\App\Factory\DatabaseFactoryInterface::class, $factory);
    }
    
    public function testMySqlConnectionCreation()
    {
        $factory = new \App\Factory\MySqlFactory();
        $connection = $factory->createConnection();
        $this->assertInstanceOf(\App\Connection\ConnectionInterface::class, $connection);
    }
    
    public function testPostgreSqlConnectionCreation()
    {
        $factory = new \App\Factory\PostgreSqlFactory();
        $connection = $factory->createConnection();
        $this->assertInstanceOf(\App\Connection\ConnectionInterface::class, $connection);
    }
    
    public function testSqliteConnectionCreation()
    {
        $factory = new \App\Factory\SqliteFactory();
        $connection = $factory->createConnection();
        $this->assertInstanceOf(\App\Connection\ConnectionInterface::class, $connection);
    }
}