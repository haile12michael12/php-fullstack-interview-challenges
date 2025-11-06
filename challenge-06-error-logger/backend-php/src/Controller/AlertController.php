<?php

namespace App\Controller;

use App\Service\AlertService;

class AlertController
{
    private AlertService $alertService;

    public function __construct(AlertService $alertService)
    {
        $this->alertService = $alertService;
    }

    public function sendAlert(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $message = $input['message'] ?? '';
        $level = $input['level'] ?? 'error';
        
        if (empty($message)) {
            http_response_code(400);
            echo json_encode(['error' => 'Message is required']);
            return;
        }
        
        $result = $this->alertService->sendAlert($message, $level);
        if ($result) {
            echo json_encode(['message' => 'Alert sent successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to send alert']);
        }
    }
}