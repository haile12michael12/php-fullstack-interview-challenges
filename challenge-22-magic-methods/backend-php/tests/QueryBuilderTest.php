<?php

use PHPUnit\Framework\TestCase;
use App\ORM\QueryBuilder;
use App\Database\Connection;

class QueryBuilderTest extends TestCase
{
    private $connection;
    
    protected function setUp(): void
    {
        // Create a mock connection
        $this->connection = $this->createMock(Connection::class);
    }
    
    public function testQueryBuilderConstructor()
    {
        $queryBuilder = new QueryBuilder('users', $this->connection);
        $this->assertInstanceOf(QueryBuilder::class, $queryBuilder);
    }
    
    public function testWhereMethod()
    {
        $queryBuilder = new QueryBuilder('users', $this->connection);
        $queryBuilder->where('name', 'John');
        
        $this->assertStringContainsString('SELECT * FROM users', $queryBuilder->toSql());
        $this->assertStringContainsString('name = ?', $queryBuilder->toSql());
    }
    
    public function testMagicWhereMethods()
    {
        $queryBuilder = new QueryBuilder('users', $this->connection);
        
        // Test dynamic where method
        $queryBuilder->whereName('John');
        
        $this->assertStringContainsString('name = ?', $queryBuilder->toSql());
    }
    
    public function testOrderByMethod()
    {
        $queryBuilder = new QueryBuilder('users', $this->connection);
        $queryBuilder->orderBy('name', 'desc');
        
        $this->assertStringContainsString('ORDER BY name desc', $queryBuilder->toSql());
    }
    
    public function testLimitMethod()
    {
        $queryBuilder = new QueryBuilder('users', $this->connection);
        $queryBuilder->limit(10);
        
        $this->assertStringContainsString('LIMIT 10', $queryBuilder->toSql());
    }
    
    public function testSelectMethod()
    {
        $queryBuilder = new QueryBuilder('users', $this->connection);
        $queryBuilder->select('id', 'name');
        
        $this->assertStringContainsString('SELECT id, name FROM users', $queryBuilder->toSql());
    }
    
    public function testCountMethod()
    {
        $queryBuilder = new QueryBuilder('users', $this->connection);
        $sql = "SELECT COUNT(*) as count FROM users";
        
        $this->assertStringContainsString('COUNT(*)', $queryBuilder->toSql());
    }
}