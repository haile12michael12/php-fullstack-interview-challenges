<?php

namespace App\GraphQL\Schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\GraphQL\Schema\TypeRegistry;

class RootQuery extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Query',
            'fields' => function() {
                return [
                    'users' => [
                        'type' => Type::nonNull(Type::listOf(TypeRegistry::userType())),
                        'resolve' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return [];
                        }
                    ],
                    'user' => [
                        'type' => TypeRegistry::userType(),
                        'args' => [
                            'id' => Type::nonNull(Type::id())
                        ],
                        'resolve' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return null;
                        }
                    ],
                    'posts' => [
                        'type' => Type::nonNull(Type::listOf(TypeRegistry::postType())),
                        'resolve' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return [];
                        }
                    ],
                    'post' => [
                        'type' => TypeRegistry::postType(),
                        'args' => [
                            'id' => Type::nonNull(Type::id())
                        ],
                        'resolve' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return null;
                        }
                    ],
                    'searchPosts' => [
                        'type' => Type::nonNull(Type::listOf(TypeRegistry::postType())),
                        'args' => [
                            'query' => Type::nonNull(Type::string())
                        ],
                        'resolve' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return [];
                        }
                    ]
                ];
            }
        ];

        parent::__construct($config);
    }
}