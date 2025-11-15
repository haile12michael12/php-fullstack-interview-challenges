<?php

namespace App\Messenger\Message;

/**
 * Publish news message
 * 
 * Represents a message to publish news articles asynchronously
 */
class PublishNewsMessage
{
    private string $title;
    private string $content;

    /**
     * Constructor
     *
     * @param string $title The news title
     * @param string $content The news content
     */
    public function __construct(string $title, string $content)
    {
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * Get the news title
     *
     * @return string The news title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the news content
     *
     * @return string The news content
     */
    public function getContent(): string
    {
        return $this->content;
    }
}