<?php

namespace App\GraphQL\Schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\GraphQL\Schema\TypeRegistry;

class TypeRegistry
{
    private static $types = [];

    public static function userType(): ObjectType
    {
        return self::$types['user'] ??= new ObjectType([
            'name' => 'User',
            'fields' => function() {
                return [
                    'id' => Type::nonNull(Type::id()),
                    'name' => Type::nonNull(Type::string()),
                    'email' => Type::nonNull(Type::string()),
                    'posts' => [
                        'type' => Type::listOf(self::postType()),
                        'resolve' => [self::class, 'resolveUserPosts']
                    ],
                    'createdAt' => Type::nonNull(Type::string()),
                    'updatedAt' => Type::string()
                ];
            }
        ]);
    }

    public static function postType(): ObjectType
    {
        return self::$types['post'] ??= new ObjectType([
            'name' => 'Post',
            'fields' => function() {
                return [
                    'id' => Type::nonNull(Type::id()),
                    'title' => Type::nonNull(Type::string()),
                    'content' => Type::nonNull(Type::string()),
                    'author' => [
                        'type' => self::userType(),
                        'resolve' => [self::class, 'resolvePostAuthor']
                    ],
                    'comments' => [
                        'type' => Type::listOf(self::commentType()),
                        'resolve' => [self::class, 'resolvePostComments']
                    ],
                    'createdAt' => Type::nonNull(Type::string()),
                    'updatedAt' => Type::string()
                ];
            }
        ]);
    }

    public static function commentType(): ObjectType
    {
        return self::$types['comment'] ??= new ObjectType([
            'name' => 'Comment',
            'fields' => function() {
                return [
                    'id' => Type::nonNull(Type::id()),
                    'content' => Type::nonNull(Type::string()),
                    'author' => [
                        'type' => self::userType(),
                        'resolve' => [self::class, 'resolveCommentAuthor']
                    ],
                    'post' => [
                        'type' => self::postType(),
                        'resolve' => [self::class, 'resolveCommentPost']
                    ],
                    'createdAt' => Type::nonNull(Type::string())
                ];
            }
        ]);
    }

    // Resolver methods would typically be implemented in the resolver classes
    public static function resolveUserPosts($user, $args, $context, $info)
    {
        // This would be implemented in the actual resolver
        return [];
    }

    public static function resolvePostAuthor($post, $args, $context, $info)
    {
        // This would be implemented in the actual resolver
        return null;
    }

    public static function resolvePostComments($post, $args, $context, $info)
    {
        // This would be implemented in the actual resolver
        return [];
    }

    public static function resolveCommentAuthor($comment, $args, $context, $info)
    {
        // This would be implemented in the actual resolver
        return null;
    }

    public static function resolveCommentPost($comment, $args, $context, $info)
    {
        // This would be implemented in the actual resolver
        return null;
    }
}