<?php

namespace App\GraphQL\Schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\GraphQL\Schema\TypeRegistry;

class RootMutation extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Mutation',
            'fields' => function() {
                return [
                    'createUser' => [
                        'type' => Type::nonNull(TypeRegistry::userType()),
                        'args' => [
                            'name' => Type::nonNull(Type::string()),
                            'email' => Type::nonNull(Type::string()),
                            'password' => Type::nonNull(Type::string())
                        ],
                        'resolve' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return null;
                        }
                    ],
                    'createPost' => [
                        'type' => Type::nonNull(TypeRegistry::postType()),
                        'args' => [
                            'title' => Type::nonNull(Type::string()),
                            'content' => Type::nonNull(Type::string()),
                            'authorId' => Type::nonNull(Type::id())
                        ],
                        'resolve' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return null;
                        }
                    ],
                    'createComment' => [
                        'type' => Type::nonNull(TypeRegistry::commentType()),
                        'args' => [
                            'content' => Type::nonNull(Type::string()),
                            'authorId' => Type::nonNull(Type::id()),
                            'postId' => Type::nonNull(Type::id())
                        ],
                        'resolve' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return null;
                        }
                    ],
                    'updateUser' => [
                        'type' => Type::nonNull(TypeRegistry::userType()),
                        'args' => [
                            'id' => Type::nonNull(Type::id()),
                            'name' => Type::string(),
                            'email' => Type::string()
                        ],
                        'resolve' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return null;
                        }
                    ],
                    'updatePost' => [
                        'type' => Type::nonNull(TypeRegistry::postType()),
                        'args' => [
                            'id' => Type::nonNull(Type::id()),
                            'title' => Type::string(),
                            'content' => Type::string()
                        ],
                        'resolve' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return null;
                        }
                    ],
                    'deletePost' => [
                        'type' => Type::nonNull(Type::boolean()),
                        'args' => [
                            'id' => Type::nonNull(Type::id())
                        ],
                        'resolve' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return false;
                        }
                    ]
                ];
            }
        ];

        parent::__construct($config);
    }
}