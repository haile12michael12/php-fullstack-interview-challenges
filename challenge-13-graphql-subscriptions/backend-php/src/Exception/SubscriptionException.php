<?php

namespace App\Exception;

use Exception;

class SubscriptionException extends Exception
{
    private $topic;
    
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Exception $previous = null,
        ?string $topic = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->topic = $topic;
    }
    
    public function getTopic(): ?string
    {
        return $this->topic;
    }
    
    public function toArray(): array
    {
        $result = [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'topic' => $this->topic
        ];
        
        return $result;
    }
}