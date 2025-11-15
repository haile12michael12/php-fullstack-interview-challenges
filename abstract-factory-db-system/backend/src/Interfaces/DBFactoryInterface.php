<?php

namespace Interfaces;

use Interfaces\DBConnectionInterface;

interface DBFactoryInterface
{
    public function createConnection(): DBConnectionInterface;
}