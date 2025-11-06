<?php

namespace Src\Query;

use Src\Grammar\GrammarInterface;
use PDO;

class DeleteQuery extends QueryBuilder
{
    /**
     * Create a new delete query instance
     *
     * @param PDO $connection
     * @param GrammarInterface $grammar
     */
    public function __construct(PDO $connection, GrammarInterface $grammar)
    {
        parent::__construct($connection, $grammar);
    }

    /**
     * Set the table which the query is targeting
     *
     * @param string $table
     * @return self
     */
    public function from(string $table): self
    {
        $this->components['table'] = $table;
        return $this;
    }

    /**
     * Execute the delete statement
     *
     * @return int
     */
    public function execute(): int
    {
        return parent::delete();
    }
}