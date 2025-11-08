<?php

namespace App\GraphQL\Schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\GraphQL\Schema\TypeRegistry;

class RootSubscription extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Subscription',
            'fields' => function() {
                return [
                    'postAdded' => [
                        'type' => Type::nonNull(TypeRegistry::postType()),
                        'resolve' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return $root;
                        },
                        'subscribe' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return null;
                        }
                    ],
                    'commentAdded' => [
                        'type' => Type::nonNull(TypeRegistry::commentType()),
                        'args' => [
                            'postId' => Type::id()
                        ],
                        'resolve' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return $root;
                        },
                        'subscribe' => function($root, $args, $context, $info) {
                            // This would be implemented in the actual resolver
                            return null;
                        }
                    ]
                ];
            }
        ];

        parent::__construct($config);
    }
}