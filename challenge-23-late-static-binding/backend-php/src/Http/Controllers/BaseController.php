<?php

namespace App\Http\Controllers;

class BaseController
{
    protected function response($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function jsonResponse($data, $statusCode = 200)
    {
        return $this->response($data, $statusCode);
    }

    protected function errorResponse($message, $statusCode = 400)
    {
        return $this->response(['error' => $message], $statusCode);
    }
}