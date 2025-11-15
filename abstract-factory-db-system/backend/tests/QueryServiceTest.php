<?php

use Services\QueryService;
use Services\ConnectionManager;

class QueryServiceTest extends PHPUnit\Framework\TestCase
{
    private $queryService;
    
    protected function setUp(): void
    {
        $this->queryService = new QueryService();
    }
    
    public function testQueryServiceInstantiation()
    {
        $this->assertInstanceOf(QueryService::class, $this->queryService);
    }
    
    public function testExecuteQueryWithSQLite()
    {
        // Test with SQLite in-memory database
        $dbConfig = ['database' => ':memory:'];
        
        // Create a table
        $createTableSql = "CREATE TABLE test (id INTEGER PRIMARY KEY, name TEXT)";
        $result = $this->queryService->executeNonQuery('sqlite', $dbConfig, $createTableSql);
        
        $this->assertTrue($result['success']);
        
        // Insert data
        $insertSql = "INSERT INTO test (name) VALUES (?)";
        $params = ['Test Name'];
        $result = $this->queryService->executeNonQuery('sqlite', $dbConfig, $insertSql, $params);
        
        $this->assertTrue($result['success']);
        
        // Select data
        $selectSql = "SELECT * FROM test";
        $result = $this->queryService->executeQuery('sqlite', $dbConfig, $selectSql);
        
        $this->assertTrue($result['success']);
        $this->assertNotEmpty($result['data']);
        $this->assertEquals('Test Name', $result['data'][0]['name']);
    }
}