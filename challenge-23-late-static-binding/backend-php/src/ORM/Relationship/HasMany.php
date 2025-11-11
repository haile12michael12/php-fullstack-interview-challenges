<?php

namespace App\ORM\Relationship;

use App\ORM\Relationship as BaseRelationship;

class HasMany extends BaseRelationship
{
    public function getResults()
    {
        $foreignKey = $this->foreignKey;
        $localKey = $this->localKey;
        
        $relatedInstance = new $this->related;
        return $relatedInstance::where($foreignKey, $this->parent->$localKey)->get();
    }

    public function addConstraints()
    {
        // Add constraints for has many relationship
    }
}