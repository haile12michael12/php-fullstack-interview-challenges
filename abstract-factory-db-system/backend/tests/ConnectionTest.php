<?php

use Drivers\MySQLConnection;
use Drivers\PostgresConnection;
use Drivers\SQLiteConnection;
use Interfaces\DBConnectionInterface;

class ConnectionTest extends PHPUnit\Framework\TestCase
{
    public function testMySQLConnectionImplementsInterface()
    {
        $connection = new MySQLConnection();
        $this->assertInstanceOf(DBConnectionInterface::class, $connection);
    }
    
    public function testPostgresConnectionImplementsInterface()
    {
        $connection = new PostgresConnection();
        $this->assertInstanceOf(DBConnectionInterface::class, $connection);
    }
    
    public function testSQLiteConnectionImplementsInterface()
    {
        $connection = new SQLiteConnection();
        $this->assertInstanceOf(DBConnectionInterface::class, $connection);
    }
    
    public function testSQLiteConnectionWithMemoryDatabase()
    {
        $connection = new SQLiteConnection(['database' => ':memory:']);
        $this->assertInstanceOf(DBConnectionInterface::class, $connection);
        
        // Test connection
        $connection->connect();
        $this->assertTrue($connection->isConnected());
        
        // Test disconnection
        $connection->disconnect();
        $this->assertFalse($connection->isConnected());
    }
}