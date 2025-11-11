<?php

namespace App\ORM\Relationship;

use App\ORM\Relationship as BaseRelationship;

class BelongsTo extends BaseRelationship
{
    protected $ownerKey;
    protected $relation;

    public function __construct($related, $parent, $foreignKey, $ownerKey, $relation)
    {
        parent::__construct($related, $parent, $foreignKey, $ownerKey);
        $this->ownerKey = $ownerKey;
        $this->relation = $relation;
    }

    public function getResults()
    {
        $foreignKey = $this->foreignKey;
        $ownerKey = $this->ownerKey;
        
        $relatedInstance = new $this->related;
        return $relatedInstance::where($ownerKey, $this->parent->$foreignKey)->first();
    }

    public function addConstraints()
    {
        // Add constraints for belongs to relationship
    }
}