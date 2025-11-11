<?php

namespace App\ORM;

use App\Database\Connection;

class QueryBuilder
{
    protected string $table;
    protected Connection $connection;
    protected array $wheres = [];
    protected array $bindings = [];
    protected array $orders = [];
    protected int $limit = 0;
    protected int $offset = 0;
    protected array $selects = ['*'];
    protected string $operation = 'select';

    public function __construct(string $table, Connection $connection)
    {
        $this->table = $table;
        $this->connection = $connection;
    }

    public function __call(string $method, array $parameters)
    {
        // Handle where methods
        if (strpos($method, 'where') === 0) {
            return $this->dynamicWhere($method, $parameters);
        }
        
        throw new \BadMethodCallException("Method {$method} does not exist.");
    }

    protected function dynamicWhere(string $method, array $parameters)
    {
        $field = strtolower(substr($method, 5)); // Remove 'where' prefix
        $operator = '=';
        $value = $parameters[0] ?? null;
        
        // Handle operators in method name (e.g., whereNameNot)
        if (strpos($field, 'not') !== false) {
            $field = str_replace('not', '', $field);
            $operator = '!=';
        }
        
        return $this->where($field, $operator, $value);
    }

    public function where(string $column, $operator = null, $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $this->wheres[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => 'and'
        ];
        
        $this->bindings[] = $value;
        
        return $this;
    }

    public function orWhere(string $column, $operator = null, $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $this->wheres[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => 'or'
        ];
        
        $this->bindings[] = $value;
        
        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orders[] = [
            'column' => $column,
            'direction' => strtolower($direction) === 'desc' ? 'desc' : 'asc'
        ];
        
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function select(...$columns): self
    {
        $this->selects = $columns;
        return $this;
    }

    public function toSql(): string
    {
        $sql = "SELECT " . implode(', ', $this->selects) . " FROM {$this->table}";
        
        if (!empty($this->wheres)) {
            $sql .= " WHERE ";
            $whereClauses = [];
            
            foreach ($this->wheres as $index => $where) {
                $whereClauses[] = ($index > 0 ? strtoupper($where['boolean']) . ' ' : '') . 
                                  "{$where['column']} {$where['operator']} ?";
            }
            
            $sql .= implode(' ', $whereClauses);
        }
        
        if (!empty($this->orders)) {
            $sql .= " ORDER BY ";
            $orderClauses = [];
            
            foreach ($this->orders as $order) {
                $orderClauses[] = "{$order['column']} {$order['direction']}";
            }
            
            $sql .= implode(', ', $orderClauses);
        }
        
        if ($this->limit > 0) {
            $sql .= " LIMIT {$this->limit}";
        }
        
        if ($this->offset > 0) {
            $sql .= " OFFSET {$this->offset}";
        }
        
        return $sql;
    }

    public function get(): array
    {
        $sql = $this->toSql();
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll();
    }

    public function first()
    {
        $result = $this->limit(1)->get();
        return $result[0] ?? null;
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        if (!empty($this->wheres)) {
            $sql .= " WHERE ";
            $whereClauses = [];
            
            foreach ($this->wheres as $index => $where) {
                $whereClauses[] = ($index > 0 ? strtoupper($where['boolean']) . ' ' : '') . 
                                  "{$where['column']} {$where['operator']} ?";
            }
            
            $sql .= implode(' ', $whereClauses);
        }
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($this->bindings);
        $result = $stmt->fetch();
        
        return (int)($result['count'] ?? 0);
    }

    public function insert(array $data): bool
    {
        $columns = array_keys($data);
        $values = array_values($data);
        $placeholders = str_repeat('?,', count($values) - 1) . '?';
        
        $sql = "INSERT INTO {$this->table} (" . implode(',', $columns) . ") VALUES ({$placeholders})";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($values);
    }

    public function update(array $data): int
    {
        if (empty($data)) {
            return 0;
        }
        
        $columns = array_keys($data);
        $values = array_values($data);
        
        $setClause = implode(' = ?, ', $columns) . ' = ?';
        $sql = "UPDATE {$this->table} SET {$setClause}";
        
        if (!empty($this->wheres)) {
            $sql .= " WHERE ";
            $whereClauses = [];
            
            foreach ($this->wheres as $index => $where) {
                $whereClauses[] = ($index > 0 ? strtoupper($where['boolean']) . ' ' : '') . 
                                  "{$where['column']} {$where['operator']} ?";
            }
            
            $sql .= implode(' ', $whereClauses);
            $values = array_merge($values, $this->bindings);
        }
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($values);
        return $stmt->rowCount();
    }

    public function delete(): int
    {
        $sql = "DELETE FROM {$this->table}";
        
        if (!empty($this->wheres)) {
            $sql .= " WHERE ";
            $whereClauses = [];
            
            foreach ($this->wheres as $index => $where) {
                $whereClauses[] = ($index > 0 ? strtoupper($where['boolean']) . ' ' : '') . 
                                  "{$where['column']} {$where['operator']} ?";
            }
            
            $sql .= implode(' ', $whereClauses);
        }
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->rowCount();
    }
}