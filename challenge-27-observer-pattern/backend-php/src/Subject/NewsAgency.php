<?php

namespace App\Subject;

/**
 * News agency subject implementation
 * 
 * Publishes news articles to registered observers
 */
class NewsAgency extends AbstractSubject
{
    private string $name;
    private array $news = [];

    /**
     * Constructor
     *
     * @param string $name The name of the news agency
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the news agency
     *
     * @return string The news agency name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Publish a news article
     *
     * @param string $title The news title
     * @param string $content The news content
     * @return void
     */
    public function publishNews(string $title, string $content): void
    {
        $newsItem = [
            'title' => $title,
            'content' => $content,
            'published_at' => new \DateTimeImmutable()
        ];

        $this->news[] = $newsItem;

        // Notify all observers about the new news
        $this->notify('news.published', $newsItem);
    }

    /**
     * Get all published news
     *
     * @return array The list of published news
     */
    public function getNews(): array
    {
        return $this->news;
    }

    /**
     * Get the latest news article
     *
     * @return array|null The latest news article or null if none exist
     */
    public function getLatestNews(): ?array
    {
        if (empty($this->news)) {
            return null;
        }

        return end($this->news);
    }
}