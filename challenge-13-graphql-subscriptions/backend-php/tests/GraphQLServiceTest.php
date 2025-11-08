<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\GraphQL\GraphQLService;

class GraphQLServiceTest extends TestCase
{
    public function testGraphQLServiceCanBeInstantiated()
    {
        $service = new GraphQLService();
        $this->assertInstanceOf(GraphQLService::class, $service);
    }
    
    public function testGraphQLSchemaIsCreated()
    {
        $service = new GraphQLService();
        $schema = $service->getSchema();
        $this->assertNotNull($schema);
    }
}