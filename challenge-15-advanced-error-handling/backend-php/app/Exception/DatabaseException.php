<?php

namespace App\Exception;

use Throwable;

class DatabaseException extends ApplicationException
{
    protected $query;
    protected $bindings;

    public function __construct(
        string $message = "Database error occurred",
        int $code = 500,
        ?Throwable $previous = null,
        string $query = null,
        array $bindings = [],
        array $context = [],
        string $correlationId = null
    ) {
        parent::__construct($message, $code, $previous, $context, $correlationId, 'error');
        $this->query = $query;
        $this->bindings = $bindings;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['query'] = $this->query;
        $data['bindings'] = $this->bindings;
        return $data;
    }
}