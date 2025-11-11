<?php

use PHPUnit\Framework\TestCase;
use App\Database\QueryBuilder;

class QueryBuilderTest extends TestCase
{
    public function testQueryBuilderInstantiation()
    {
        $queryBuilder = new QueryBuilder('users');
        $this->assertInstanceOf(QueryBuilder::class, $queryBuilder);
    }
    
    public function testSelectClause()
    {
        $queryBuilder = new QueryBuilder('users');
        $queryBuilder->select('id', 'name');
        
        $sql = $queryBuilder->toSql();
        $this->assertStringContainsString('SELECT id, name FROM users', $sql);
    }
    
    public function testWhereClause()
    {
        $queryBuilder = new QueryBuilder('users');
        $queryBuilder->where('name', 'John');
        
        $sql = $queryBuilder->toSql();
        $this->assertStringContainsString('WHERE name = ?', $sql);
    }
    
    public function testOrderByClause()
    {
        $queryBuilder = new QueryBuilder('users');
        $queryBuilder->orderBy('name', 'desc');
        
        $sql = $queryBuilder->toSql();
        $this->assertStringContainsString('ORDER BY name desc', $sql);
    }
    
    public function testLimitClause()
    {
        $queryBuilder = new QueryBuilder('users');
        $queryBuilder->limit(10);
        
        $sql = $queryBuilder->toSql();
        $this->assertStringContainsString('LIMIT 10', $sql);
    }
}