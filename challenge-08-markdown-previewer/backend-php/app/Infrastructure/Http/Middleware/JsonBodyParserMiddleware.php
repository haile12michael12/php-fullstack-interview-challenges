<?php

namespace App\Infrastructure\Http\Middleware;

class JsonBodyParserMiddleware
{
    public function handle(): void
    {
        // Ensure JSON content type for POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
            strpos($_SERVER['CONTENT_TYPE'], 'application/json') === false) {
            $_SERVER['CONTENT_TYPE'] = 'application/json';
        }

        // Parse JSON body if content type is JSON
        if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            $input = file_get_contents('php://input');
            $json = json_decode($input, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                $_POST = $json;
            }
        }
    }
}