<?php

namespace App\ORM;

abstract class Relationship
{
    protected $related;
    protected $parent;
    protected $foreignKey;
    protected $localKey;

    public function __construct($related, $parent, $foreignKey, $localKey)
    {
        $this->related = $related;
        $this->parent = $parent;
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
    }

    abstract public function getResults();

    public function addConstraints()
    {
        // Add constraints to the query
    }
}