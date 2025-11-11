<?php

namespace App\Http\Controllers;

class InheritanceController extends BaseController
{
    public function index()
    {
        return $this->jsonResponse([
            'message' => 'Inheritance and Late Static Binding Demo',
            'concepts' => [
                'late_static_binding' => 'Demonstrates the use of late static binding in PHP',
                'active_record_pattern' => 'Implementation of the Active Record pattern',
                'factory_pattern' => 'Model factory for creating test data',
                'relationship_handling' => 'Eloquent-like relationship handling',
            ],
            'endpoints' => [
                'GET /api/inheritance/concepts' => 'List all inheritance concepts',
                'GET /api/inheritance/examples' => 'Show LSB examples',
                'POST /api/inheritance/factory' => 'Create models using factory',
            ]
        ]);
    }

    public function getConcepts()
    {
        return $this->jsonResponse([
            'concepts' => [
                [
                    'name' => 'Late Static Binding',
                    'description' => 'A feature that allows referencing the called class in a context of static inheritance',
                    'example' => 'static::method() vs self::method()',
                ],
                [
                    'name' => 'Active Record Pattern',
                    'description' => 'An approach to accessing data in a database',
                    'example' => 'User::find(1), Post::create([...])',
                ],
                [
                    'name' => 'Factory Pattern',
                    'description' => 'A creational pattern that uses factory methods to create objects',
                    'example' => 'Factory::create(User::class, [...])',
                ],
                [
                    'name' => 'Relationship Handling',
                    'description' => 'Managing relationships between different models',
                    'example' => '$user->posts(), $post->user()',
                ],
            ]
        ]);
    }

    public function getExamples()
    {
        return $this->jsonResponse([
            'examples' => [
                [
                    'concept' => 'Late Static Binding',
                    'code' => 'class Parent { public static function who() { return __CLASS__; } public static function test() { return static::who(); } } class Child extends Parent { public static function who() { return __CLASS__; } } echo Child::test(); // Outputs: Child',
                    'explanation' => 'The static:: keyword refers to the class that was initially called, not the class where the method is defined',
                ],
                [
                    'concept' => 'Active Record Pattern',
                    'code' => '$user = User::create([\'name\' => \'John\', \'email\' => \'john@example.com\']); $posts = $user->posts();',
                    'explanation' => 'Models can create and query themselves using static methods',
                ],
            ]
        ]);
    }

    public function createWithFactory()
    {
        try {
            // This would use the Factory class to create models
            $input = json_decode(file_get_contents('php://input'), true);
            
            return $this->jsonResponse([
                'message' => 'Factory creation endpoint',
                'data' => $input
            ], 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create with factory: ' . $e->getMessage());
        }
    }
}