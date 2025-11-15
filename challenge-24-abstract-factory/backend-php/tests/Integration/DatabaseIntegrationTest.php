<?php

use PHPUnit\Framework\TestCase;

class DatabaseIntegrationTest extends TestCase
{
    public function testDatabaseManagerWithMySql()
    {
        $factory = new \App\Factory\MySqlFactory();
        $manager = new \App\Application\DatabaseManager($factory);
        
        $this->assertInstanceOf(\App\Application\DatabaseManager::class, $manager);
    }
    
    public function testDatabaseManagerWithPostgreSql()
    {
        $factory = new \App\Factory\PostgreSqlFactory();
        $manager = new \App\Application\DatabaseManager($factory);
        
        $this->assertInstanceOf(\App\Application\DatabaseManager::class, $manager);
    }
    
    public function testDatabaseManagerWithSqlite()
    {
        $factory = new \App\Factory\SqliteFactory();
        $manager = new \App\Application\DatabaseManager($factory);
        
        $this->assertInstanceOf(\App\Application\DatabaseManager::class, $manager);
    }
    
    public function testConnectionPool()
    {
        $factory = new \App\Factory\MySqlFactory();
        $pool = new \App\Pool\ConnectionPool($factory, 5);
        
        $this->assertInstanceOf(\App\Pool\ConnectionPoolInterface::class, $pool);
        $this->assertEquals(0, $pool->getSize());
        $this->assertEquals(5, $pool->getMaxSize());
    }
    
    public function testConfiguration()
    {
        $config = \App\Application\Configuration::getInstance();
        $this->assertNotNull($config);
        
        $dbConfig = \App\Application\Configuration::get('database');
        $this->assertIsArray($dbConfig);
    }
}