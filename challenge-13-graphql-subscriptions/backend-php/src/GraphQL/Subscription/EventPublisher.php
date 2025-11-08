<?php

namespace App\GraphQL\Subscription;

use App\Model\Post;
use App\Model\Comment;

class EventPublisher
{
    private $subscriptionManager;
    
    public function __construct()
    {
        $this->subscriptionManager = SubscriptionManager::getInstance();
    }
    
    /**
     * Publish a post added event
     *
     * @param Post $post
     * @return void
     */
    public function publishPostAdded(Post $post): void
    {
        $this->subscriptionManager->publish('postAdded', $post);
    }
    
    /**
     * Publish a comment added event
     *
     * @param Comment $comment
     * @param string|null $postId
     * @return void
     */
    public function publishCommentAdded(Comment $comment, ?string $postId = null): void
    {
        // Publish to general commentAdded topic
        $this->subscriptionManager->publish('commentAdded', $comment);
        
        // If postId is provided, also publish to specific topic
        if ($postId) {
            $this->subscriptionManager->publish("commentAdded:{$postId}", $comment);
        }
    }
    
    /**
     * Publish a custom event
     *
     * @param string $event
     * @param mixed $payload
     * @return void
     */
    public function publishEvent(string $event, $payload): void
    {
        $this->subscriptionManager->publish($event, $payload);
    }
}