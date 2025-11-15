<?php

use Factories\DBFactoryProvider;
use Interfaces\DBFactoryInterface;
use Drivers\MySQLConnection;
use Drivers\PostgresConnection;
use Drivers\SQLiteConnection;

class FactoryTest extends PHPUnit\Framework\TestCase
{
    public function testMySQLFactoryCreation()
    {
        $factory = DBFactoryProvider::getFactory('mysql');
        $this->assertInstanceOf(DBFactoryInterface::class, $factory);
        
        $connection = $factory->createConnection();
        $this->assertInstanceOf(MySQLConnection::class, $connection);
    }
    
    public function testPostgresFactoryCreation()
    {
        $factory = DBFactoryProvider::getFactory('postgres');
        $this->assertInstanceOf(DBFactoryInterface::class, $factory);
        
        $connection = $factory->createConnection();
        $this->assertInstanceOf(PostgresConnection::class, $connection);
    }
    
    public function testSQLiteFactoryCreation()
    {
        $factory = DBFactoryProvider::getFactory('sqlite');
        $this->assertInstanceOf(DBFactoryInterface::class, $factory);
        
        $connection = $factory->createConnection();
        $this->assertInstanceOf(SQLiteConnection::class, $connection);
    }
    
    public function testInvalidFactoryType()
    {
        $this->expectException(InvalidArgumentException::class);
        DBFactoryProvider::getFactory('invalid');
    }
}