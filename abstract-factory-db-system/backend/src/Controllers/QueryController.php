<?php

namespace Controllers;

use Services\QueryService;
use Core\Logger;

class QueryController
{
    private $queryService;

    public function __construct()
    {
        $this->queryService = new QueryService();
    }

    public function executeQuery()
    {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['db_type']) || !isset($input['db_config']) || !isset($input['sql'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required parameters: db_type, db_config, sql']);
            return;
        }
        
        $dbType = $input['db_type'];
        $dbConfig = $input['db_config'];
        $sql = $input['sql'];
        $params = $input['params'] ?? [];
        
        // Determine if this is a SELECT query or not
        $isSelectQuery = stripos(trim($sql), 'SELECT') === 0;
        
        if ($isSelectQuery) {
            $result = $this->queryService->executeQuery($dbType, $dbConfig, $sql, $params);
        } else {
            $result = $this->queryService->executeNonQuery($dbType, $dbConfig, $sql, $params);
        }
        
        if ($result['success']) {
            echo json_encode($result);
        } else {
            http_response_code(500);
            echo json_encode($result);
        }
    }
}