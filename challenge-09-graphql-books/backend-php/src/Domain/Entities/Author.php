<?php

namespace App\Domain\Entities;

class Author
{
    private string $id;
    private string $name;
    private string $biography;

    public function __construct(string $id, string $name, string $biography)
    {
        $this->id = $id;
        $this->name = $name;
        $this->biography = $biography;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBiography(): string
    {
        return $this->biography;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setBiography(string $biography): void
    {
        $this->biography = $biography;
    }
}