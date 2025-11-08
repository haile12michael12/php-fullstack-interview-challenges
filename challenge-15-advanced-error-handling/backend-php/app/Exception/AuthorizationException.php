<?php

namespace App\Exception;

class AuthorizationException extends ApplicationException
{
    protected $requiredPermissions;

    public function __construct(
        string $message = "Access denied",
        int $code = 403,
        array $requiredPermissions = [],
        array $context = [],
        string $correlationId = null
    ) {
        parent::__construct($message, $code, null, $context, $correlationId, 'warning');
        $this->requiredPermissions = $requiredPermissions;
    }

    public function getRequiredPermissions(): array
    {
        return $this->requiredPermissions;
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['required_permissions'] = $this->requiredPermissions;
        return $data;
    }
}