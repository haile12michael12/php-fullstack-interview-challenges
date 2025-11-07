<?php

namespace App\Domain\Entities;

class Book
{
    private string $id;
    private string $title;
    private string $isbn;
    private string $description;
    private int $pageCount;
    private string $publishedDate;
    private array $authorIds;
    private array $genreIds;

    public function __construct(
        string $id,
        string $title,
        string $isbn,
        string $description,
        int $pageCount,
        string $publishedDate,
        array $authorIds,
        array $genreIds
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->isbn = $isbn;
        $this->description = $description;
        $this->pageCount = $pageCount;
        $this->publishedDate = $publishedDate;
        $this->authorIds = $authorIds;
        $this->genreIds = $genreIds;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPageCount(): int
    {
        return $this->pageCount;
    }

    public function getPublishedDate(): string
    {
        return $this->publishedDate;
    }

    public function getAuthorIds(): array
    {
        return $this->authorIds;
    }

    public function getGenreIds(): array
    {
        return $this->genreIds;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setPageCount(int $pageCount): void
    {
        $this->pageCount = $pageCount;
    }
}