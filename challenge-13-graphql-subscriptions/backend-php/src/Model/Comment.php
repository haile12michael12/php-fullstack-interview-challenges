<?php

namespace App\Model;

class Comment
{
    private $id;
    private $content;
    private $authorId;
    private $postId;
    private $createdAt;
    
    public function __construct(
        ?string $id = null,
        ?string $content = null,
        ?string $authorId = null,
        ?string $postId = null,
        ?string $createdAt = null
    ) {
        $this->id = $id ?? uniqid();
        $this->content = $content;
        $this->authorId = $authorId;
        $this->postId = $postId;
        $this->createdAt = $createdAt ?? date('c');
    }
    
    // Getters
    public function getId(): string
    {
        return $this->id;
    }
    
    public function getContent(): ?string
    {
        return $this->content;
    }
    
    public function getAuthorId(): ?string
    {
        return $this->authorId;
    }
    
    public function getPostId(): ?string
    {
        return $this->postId;
    }
    
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
    
    // Setters
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
    
    public function setAuthorId(string $authorId): void
    {
        $this->authorId = $authorId;
    }
    
    public function setPostId(string $postId): void
    {
        $this->postId = $postId;
    }
    
    /**
     * Get comment as array for GraphQL
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'authorId' => $this->authorId,
            'postId' => $this->postId,
            'createdAt' => $this->createdAt
        ];
    }
}