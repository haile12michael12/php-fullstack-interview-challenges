<?php

namespace Src\Service;

use Src\Grammar\GrammarInterface;
use Src\Grammar\MySQLGrammar;
use Src\Query\SelectQuery;
use Src\Query\InsertQuery;
use Src\Query\UpdateQuery;
use Src\Query\DeleteQuery;
use PDO;

class QueryService
{
    /**
     * The database connection instance
     *
     * @var PDO
     */
    protected PDO $connection;

    /**
     * The grammar implementation
     *
     * @var GrammarInterface
     */
    protected GrammarInterface $grammar;

    /**
     * Create a new query service instance
     *
     * @param PDO $connection
     * @param GrammarInterface|null $grammar
     */
    public function __construct(PDO $connection, ?GrammarInterface $grammar = null)
    {
        $this->connection = $connection;
        $this->grammar = $grammar ?? new MySQLGrammar();
    }

    /**
     * Create a new select query
     *
     * @return SelectQuery
     */
    public function select(): SelectQuery
    {
        return new SelectQuery($this->connection, $this->grammar);
    }

    /**
     * Create a new insert query
     *
     * @return InsertQuery
     */
    public function insert(): InsertQuery
    {
        return new InsertQuery($this->connection, $this->grammar);
    }

    /**
     * Create a new update query
     *
     * @return UpdateQuery
     */
    public function update(): UpdateQuery
    {
        return new UpdateQuery($this->connection, $this->grammar);
    }

    /**
     * Create a new delete query
     *
     * @return DeleteQuery
     */
    public function delete(): DeleteQuery
    {
        return new DeleteQuery($this->connection, $this->grammar);
    }

    /**
     * Execute a raw SQL query
     *
     * @param string $sql
     * @param array $bindings
     * @return array
     */
    public function raw(string $sql, array $bindings = []): array
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute($bindings);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Begin a database transaction
     *
     * @return void
     */
    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    /**
     * Commit a database transaction
     *
     * @return void
     */
    public function commit(): void
    {
        $this->connection->commit();
    }

    /**
     * Rollback a database transaction
     *
     * @return void
     */
    public function rollback(): void
    {
        $this->connection->rollback();
    }

    /**
     * Get the database connection
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Get the grammar implementation
     *
     * @return GrammarInterface
     */
    public function getGrammar(): GrammarInterface
    {
        return $this->grammar;
    }
}