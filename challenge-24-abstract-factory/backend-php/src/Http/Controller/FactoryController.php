<?php

namespace App\Http\Controller;

class FactoryController
{
    public function index()
    {
        return $this->jsonResponse([
            'message' => 'Abstract Factory Pattern Demo',
            'endpoints' => [
                'GET /api/factory/mysql' => 'Create MySQL connection',
                'GET /api/factory/postgresql' => 'Create PostgreSQL connection',
                'GET /api/factory/sqlite' => 'Create SQLite connection',
                'GET /api/factory/pool' => 'Connection pooling demo',
            ]
        ]);
    }

    public function createMySql()
    {
        try {
            $factory = new \App\Factory\MySqlFactory();
            $connection = $factory->createConnection();
            
            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'MySQL connection created successfully',
                'connection' => get_class($connection)
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createPostgreSql()
    {
        try {
            $factory = new \App\Factory\PostgreSqlFactory();
            $connection = $factory->createConnection();
            
            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'PostgreSQL connection created successfully',
                'connection' => get_class($connection)
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createSqlite()
    {
        try {
            $factory = new \App\Factory\SqliteFactory();
            $connection = $factory->createConnection();
            
            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'SQLite connection created successfully',
                'connection' => get_class($connection)
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function connectionPool()
    {
        try {
            $factory = new \App\Factory\MySqlFactory();
            $pool = new \App\Pool\ConnectionPool($factory, 5);
            
            // Get a few connections
            $conn1 = $pool->getConnection();
            $conn2 = $pool->getConnection();
            
            // Release one
            $pool->releaseConnection($conn1);
            
            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'Connection pool demo',
                'pool_size' => $pool->getSize(),
                'available_count' => $pool->getAvailableCount(),
                'max_size' => $pool->getMaxSize()
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    protected function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}