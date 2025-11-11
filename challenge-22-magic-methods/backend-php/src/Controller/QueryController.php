<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Model\User;
use App\Model\Post;
use App\ORM\QueryBuilder;

class QueryController
{
    public function index(Request $request): Response
    {
        return new Response([
            'message' => 'Query Builder Demo',
            'endpoints' => [
                'GET /api/query/users' => 'Query users with filters',
                'GET /api/query/posts' => 'Query posts with filters',
                'POST /api/query/custom' => 'Execute custom query',
            ]
        ]);
    }

    public function queryUsers(Request $request): Response
    {
        try {
            $queryParams = $request->getQueryParams();
            
            // Build query using magic methods
            $query = User::query();
            
            // Apply filters from query parameters
            foreach ($queryParams as $key => $value) {
                if (method_exists($query, "where" . ucfirst($key))) {
                    $query = $query->{"where" . ucfirst($key)}($value);
                } elseif (strpos($key, 'where') === 0) {
                    $query = $query->$key($value);
                }
            }
            
            $users = $query->get();
            
            return new Response([
                'status' => 'success',
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return new Response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function queryPosts(Request $request): Response
    {
        try {
            $queryParams = $request->getQueryParams();
            
            // Build query using magic methods
            $query = Post::query();
            
            // Apply filters from query parameters
            foreach ($queryParams as $key => $value) {
                if (method_exists($query, "where" . ucfirst($key))) {
                    $query = $query->{"where" . ucfirst($key)}($value);
                } elseif (strpos($key, 'where') === 0) {
                    $query = $query->$key($value);
                }
            }
            
            $posts = $query->get();
            
            return new Response([
                'status' => 'success',
                'data' => $posts
            ]);
        } catch (\Exception $e) {
            return new Response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function customQuery(Request $request): Response
    {
        try {
            $data = $request->getParsedBody();
            $table = $data['table'] ?? '';
            $conditions = $data['conditions'] ?? [];
            
            if (!$table) {
                return new Response([
                    'status' => 'error',
                    'message' => 'Table name is required'
                ], 400);
            }
            
            // Create a query builder instance
            $connection = new \App\Database\Connection();
            $query = new QueryBuilder($table, $connection);
            
            // Apply conditions
            foreach ($conditions as $condition) {
                $column = $condition['column'] ?? '';
                $operator = $condition['operator'] ?? '=';
                $value = $condition['value'] ?? '';
                $boolean = $condition['boolean'] ?? 'and';
                
                if ($boolean === 'or') {
                    $query = $query->orWhere($column, $operator, $value);
                } else {
                    $query = $query->where($column, $operator, $value);
                }
            }
            
            $results = $query->get();
            
            return new Response([
                'status' => 'success',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return new Response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function __call(string $method, array $parameters)
    {
        // Handle dynamic action methods
        if (strpos($method, 'action') === 0) {
            $action = lcfirst(substr($method, 6));
            return $this->$action(...$parameters);
        }
        
        // Handle dynamic query methods
        if (strpos($method, 'query') === 0) {
            $model = lcfirst(substr($method, 5));
            return $this->dynamicQuery($model, ...$parameters);
        }
        
        throw new \BadMethodCallException("Method {$method} does not exist.");
    }

    protected function dynamicQuery(string $model, Request $request): Response
    {
        try {
            $modelClass = "App\\Model\\" . ucfirst($model);
            if (!class_exists($modelClass)) {
                return new Response([
                    'status' => 'error',
                    'message' => "Model {$model} not found"
                ], 404);
            }
            
            $queryParams = $request->getQueryParams();
            $query = $modelClass::query();
            
            // Apply filters
            foreach ($queryParams as $key => $value) {
                if (method_exists($query, "where" . ucfirst($key))) {
                    $query = $query->{"where" . ucfirst($key)}($value);
                }
            }
            
            $results = $query->get();
            
            return new Response([
                'status' => 'success',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return new Response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function __get(string $property)
    {
        return $this->$property ?? null;
    }

    public function __set(string $property, $value): void
    {
        $this->$property = $value;
    }

    public function __isset(string $property): bool
    {
        return isset($this->$property);
    }

    public function __unset(string $property): void
    {
        unset($this->$property);
    }

    public function __toString(): string
    {
        return "QueryController";
    }
}