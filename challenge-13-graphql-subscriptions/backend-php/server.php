<?php

require_once __DIR__ . '/vendor/autoload.php';

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Ratchet\App as RatchetApp;

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Simple GraphQL schema for demonstration
$userType = new ObjectType([
    'name' => 'User',
    'fields' => [
        'id' => Type::id(),
        'name' => Type::string(),
        'email' => Type::string(),
    ]
]);

$queryType = new ObjectType([
    'name' => 'Query',
    'fields' => [
        'users' => [
            'type' => Type::listOf($userType),
            'resolve' => function() {
                return [
                    ['id' => '1', 'name' => 'John Doe', 'email' => 'john@example.com'],
                    ['id' => '2', 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
                ];
            }
        ]
    ]
]);

$schema = new Schema([
    'query' => $queryType
]);

// For this challenge, we'll just show how to set up the server
echo "GraphQL server would start on port " . ($_ENV['GRAPHQL_PORT'] ?? 8080) . "\n";
echo "Schema defined with User type and users query\n";
echo "In a full implementation, you would:\n";
echo "1. Implement all types and resolvers\n";
echo "2. Add mutations and subscriptions\n";
echo "3. Connect to a real database\n";
echo "4. Add authentication and authorization\n";