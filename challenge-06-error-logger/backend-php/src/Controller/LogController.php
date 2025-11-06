<?php

namespace App\Controller;

use App\Service\LogService;

class LogController
{
    private LogService $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function index(): void
    {
        $logs = $this->logService->getAllLogs();
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['logs' => $logs]);
    }

    public function show(string $id): void
    {
        $log = $this->logService->getLogById($id);
        if ($log) {
            header('Content-Type: application/json');
            echo json_encode(['log' => $log]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Log not found']);
        }
    }

    public function delete(string $id): void
    {
        $result = $this->logService->deleteLog($id);
        if ($result) {
            echo json_encode(['message' => 'Log deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Log not found']);
        }
    }
}