<?php

namespace App\ORM\Exception;

class QueryException extends \Exception
{
    protected $sql;
    protected $bindings;

    public function __construct($sql, $bindings, $message = "", $code = 0, \Throwable $previous = null)
    {
        $this->sql = $sql;
        $this->bindings = $bindings;
        
        parent::__construct($message, $code, $previous);
    }

    public function getSql()
    {
        return $this->sql;
    }

    public function getBindings()
    {
        return $this->bindings;
    }
}