<?php

namespace App\Utils;

class ResponseHelper
{
    /**
     * Create JSON response
     *
     * @param array $data
     * @param int $statusCode
     * @param array $headers
     * @return array
     */
    public static function json(array $data, int $statusCode = 200, array $headers = []): array
    {
        // In a real implementation, this would return a proper Response object
        // For this challenge, we'll return an array that represents the response
        return [
            'status_code' => $statusCode,
            'headers' => array_merge([
                'Content-Type' => 'application/json',
            ], $headers),
            'body' => json_encode($data)
        ];
    }

    /**
     * Create error response
     *
     * @param string $message
     * @param int $statusCode
     * @param array $errors
     * @param array $headers
     * @return array
     */
    public static function error(
        string $message,
        int $statusCode = 500,
        array $errors = [],
        array $headers = []
    ): array {
        $data = [
            'error' => true,
            'message' => $message,
            'timestamp' => date('c')
        ];

        if (!empty($errors)) {
            $data['errors'] = $errors;
        }

        return self::json($data, $statusCode, $headers);
    }

    /**
     * Create success response
     *
     * @param array $data
     * @param int $statusCode
     * @param array $headers
     * @return array
     */
    public static function success(array $data = [], int $statusCode = 200, array $headers = []): array
    {
        $response = [
            'success' => true,
            'timestamp' => date('c')
        ];

        if (!empty($data)) {
            $response = array_merge($response, $data);
        }

        return self::json($response, $statusCode, $headers);
    }
}