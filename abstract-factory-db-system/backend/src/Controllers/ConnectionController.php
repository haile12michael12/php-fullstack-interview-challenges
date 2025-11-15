<?php

namespace Controllers;

use Services\ConnectionManager;
use Core\Logger;

class ConnectionController
{
    private $connectionManager;

    public function __construct()
    {
        $this->connectionManager = ConnectionManager::getInstance();
    }

    public function testConnection()
    {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['db_type']) || !isset($input['db_config'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required parameters: db_type, db_config']);
            return;
        }
        
        $dbType = $input['db_type'];
        $dbConfig = $input['db_config'];
        
        try {
            $connection = $this->connectionManager->getConnection($dbType, $dbConfig);
            
            if ($connection->isConnected()) {
                echo json_encode([
                    'success' => true,
                    'message' => "Successfully connected to {$dbType} database"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => "Failed to connect to {$dbType} database"
                ]);
            }
        } catch (\Exception $e) {
            Logger::getInstance()->error("Connection test failed: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => "Connection failed: " . $e->getMessage()
            ]);
        }
    }
}