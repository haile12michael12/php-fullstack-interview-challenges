<?php

namespace App\Container\Exception;

class NotFoundException extends ContainerException
{
    public function __construct(string $id)
    {
        parent::__construct("Service '{$id}' not found in container");
    }
}