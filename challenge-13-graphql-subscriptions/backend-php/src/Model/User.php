<?php

namespace App\Model;

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $createdAt;
    private $updatedAt;
    
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?string $email = null,
        ?string $password = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id ?? uniqid();
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt ?? date('c');
        $this->updatedAt = $updatedAt;
    }
    
    // Getters
    public function getId(): string
    {
        return $this->id;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    public function getPassword(): ?string
    {
        return $this->password;
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
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->updatedAt = date('c');
    }
    
    public function setEmail(string $email): void
    {
        $this->email = $email;
        $this->updatedAt = date('c');
    }
    
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->updatedAt = date('c');
    }
    
    /**
     * Verify password
     *
     * @param string $password
     * @return bool
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
    
    /**
     * Get user as array for GraphQL
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];
    }
}