<?php

namespace App\GraphQL;

use GraphQL\Type\Schema;
use GraphQL\Type\Definition\Type;
use GraphQL\GraphQL as GraphQLLib;
use App\GraphQL\Schema\RootQuery;
use App\GraphQL\Schema\RootMutation;
use App\GraphQL\Schema\RootSubscription;
use App\GraphQL\Resolver\QueryResolver;
use App\GraphQL\Resolver\MutationResolver;
use App\GraphQL\Resolver\SubscriptionResolver;

class GraphQLService
{
    private $schema;
    
    public function __construct()
    {
        $this->schema = new Schema([
            'query' => new RootQuery(),
            'mutation' => new RootMutation(),
            'subscription' => new RootSubscription()
        ]);
    }
    
    /**
     * Execute a GraphQL query
     *
     * @param string $query
     * @param array|null $variables
     * @param mixed|null $context
     * @return array
     */
    public function executeQuery(string $query, ?array $variables = null, $context = null): array
    {
        try {
            $result = GraphQLLib::executeQuery(
                $this->schema,
                $query,
                null,
                $context,
                $variables
            );
            
            return $result->toArray();
        } catch (\Exception $e) {
            return [
                'errors' => [
                    [
                        'message' => $e->getMessage(),
                        'locations' => [],
                        'path' => []
                    ]
                ]
            ];
        }
    }
    
    /**
     * Execute a GraphQL subscription
     *
     * @param string $query
     * @param array|null $variables
     * @param mixed|null $context
     * @return array
     */
    public function executeSubscription(string $query, ?array $variables = null, $context = null): array
    {
        try {
            $result = GraphQLLib::executeQuery(
                $this->schema,
                $query,
                null,
                $context,
                $variables
            );
            
            return $result->toArray();
        } catch (\Exception $e) {
            return [
                'errors' => [
                    [
                        'message' => $e->getMessage(),
                        'locations' => [],
                        'path' => []
                    ]
                ]
            ];
        }
    }
    
    /**
     * Get the GraphQL schema
     *
     * @return Schema
     */
    public function getSchema(): Schema
    {
        return $this->schema;
    }
}