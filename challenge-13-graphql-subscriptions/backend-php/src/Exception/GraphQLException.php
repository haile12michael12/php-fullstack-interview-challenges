<?php

namespace App\Exception;

use Exception;

class GraphQLException extends Exception
{
    private $category;
    private $validationErrors;
    
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Exception $previous = null,
        string $category = "graphql",
        array $validationErrors = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->category = $category;
        $this->validationErrors = $validationErrors;
    }
    
    public function getCategory(): string
    {
        return $this->category;
    }
    
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
    
    public function toArray(): array
    {
        $result = [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'category' => $this->category
        ];
        
        if (!empty($this->validationErrors)) {
            $result['validationErrors'] = $this->validationErrors;
        }
        
        return $result;
    }
}