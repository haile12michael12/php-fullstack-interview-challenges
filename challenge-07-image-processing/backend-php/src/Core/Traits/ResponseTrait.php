<?php

namespace App\Core\Traits;

trait ResponseTrait
{
    protected function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function successResponse(string $message, array $data = [], int $statusCode = 200): void
    {
        $this->jsonResponse([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    protected function errorResponse(string $message, int $statusCode = 400, array $errors = []): void
    {
        $this->jsonResponse([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
}