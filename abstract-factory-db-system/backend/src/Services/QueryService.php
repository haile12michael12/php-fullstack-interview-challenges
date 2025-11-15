<?php

namespace Services;

use Services\ConnectionManager;
use Core\Logger;

class QueryService
{
    private $connectionManager;

    public function __construct()
    {
        $this->connectionManager = ConnectionManager::getInstance();
    }

    public function executeQuery(string $dbType, array $dbConfig, string $sql, array $params = [])
    {
        try {
            $connection = $this->connectionManager->getConnection($dbType, $dbConfig);
            $result = $connection->query($sql, $params);
            
            Logger::getInstance()->info("Query executed successfully on {$dbType}");
            return [
                'success' => true,
                'data' => $result,
                'error' => null
            ];
        } catch (\Exception $e) {
            Logger::getInstance()->error("Query execution failed on {$dbType}: " . $e->getMessage());
            return [
                'success' => false,
                'data' => null,
                'error' => $e->getMessage()
            ];
        }
    }

    public function executeNonQuery(string $dbType, array $dbConfig, string $sql, array $params = [])
    {
        try {
            $connection = $this->connectionManager->getConnection($dbType, $dbConfig);
            $result = $connection->execute($sql, $params);
            
            Logger::getInstance()->info("Non-query executed successfully on {$dbType}");
            return [
                'success' => true,
                'affected_rows' => $result ? 1 : 0,
                'error' => null
            ];
        } catch (\Exception $e) {
            Logger::getInstance()->error("Non-query execution failed on {$dbType}: " . $e->getMessage());
            return [
                'success' => false,
                'affected_rows' => 0,
                'error' => $e->getMessage()
            ];
        }
    }
}