<?php

namespace App\Model;

class Post
{
    private $id;
    private $title;
    private $content;
    private $authorId;
    private $createdAt;
    private $updatedAt;
    
    public function __construct(
        ?string $id = null,
        ?string $title = null,
        ?string $content = null,
        ?string $authorId = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id ?? uniqid();
        $this->title = $title;
        $this->content = $content;
        $this->authorId = $authorId;
        $this->createdAt = $createdAt ?? date('c');
        $this->updatedAt = $updatedAt;
    }
    
    // Getters
    public function getId(): string
    {
        return $this->id;
    }
    
    public function getTitle(): ?string
    {
        return $this->title;
    }
    
    public function getContent(): ?string
    {
        return $this->content;
    }
    
    public function getAuthorId(): ?string
    {
        return $this->authorId;
    }
    
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
    
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }
    
    // Setters
    public function setTitle(string $title): void
    {
        $this->title = $title;
        $this->updatedAt = date('c');
    }
    
    public function setContent(string $content): void
    {
        $this->content = $content;
        $this->updatedAt = date('c');
    }
    
    public function setAuthorId(string $authorId): void
    {
        $this->authorId = $authorId;
        $this->updatedAt = date('c');
    }
    
    /**
     * Get post as array for GraphQL
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'authorId' => $this->authorId,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];
    }
}