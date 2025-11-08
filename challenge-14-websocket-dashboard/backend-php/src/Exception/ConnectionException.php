<?php

namespace App\Exception;

use Exception;

class ConnectionException extends Exception
{
    private $connectionId;
    
    public function __construct(string $message = "", int $code = 0, ?Exception $previous = null, ?string $connectionId = null)
    {
        parent::__construct($message, $code, $previous);
        $this->connectionId = $connectionId;
    }
    
    public function getConnectionId(): ?string
    {
        return $this->connectionId;
    }
    
    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'connection_id' => $this->connectionId
        ];
    }
}