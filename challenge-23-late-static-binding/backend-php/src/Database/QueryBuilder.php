<?php

namespace App\Database;

class QueryBuilder
{
    protected $connection;
    protected $table;
    protected $wheres = [];
    protected $bindings = [];
    protected $selects = ['*'];
    protected $orders = [];
    protected $limit;
    protected $offset;

    public function __construct($table, $connection = null)
    {
        $this->table = $table;
        $this->connection = $connection ?: Connection::getInstance();
    }

    public static function table($table)
    {
        return new static($table);
    }

    public function where($column, $operator = null, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = compact('column', 'operator', 'value');
        $this->bindings[] = $value;

        return $this;
    }

    public function select(...$columns)
    {
        $this->selects = $columns;
        return $this;
    }

    public function orderBy($column, $direction = 'asc')
    {
        $this->orders[] = compact('column', 'direction');
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function toSql()
    {
        $sql = "SELECT " . implode(', ', $this->selects) . " FROM {$this->table}";

        if (!empty($this->wheres)) {
            $sql .= " WHERE ";
            $whereConditions = [];
            foreach ($this->wheres as $where) {
                $whereConditions[] = "{$where['column']} {$where['operator']} ?";
            }
            $sql .= implode(' AND ', $whereConditions);
        }

        if (!empty($this->orders)) {
            $sql .= " ORDER BY ";
            $orderConditions = [];
            foreach ($this->orders as $order) {
                $orderConditions[] = "{$order['column']} {$order['direction']}";
            }
            $sql .= implode(', ', $orderConditions);
        }

        if ($this->limit) {
            $sql .= " LIMIT {$this->limit}";
        }

        if ($this->offset) {
            $sql .= " OFFSET {$this->offset}";
        }

        return $sql;
    }

    public function get()
    {
        $sql = $this->toSql();
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll();
    }

    public function first()
    {
        $result = $this->limit(1)->get();
        return $result ? $result[0] : null;
    }

    public function find($id)
    {
        return $this->where('id', $id)->first();
    }

    public function insert($data)
    {
        $columns = array_keys($data);
        $values = array_values($data);
        $placeholders = str_repeat('?,', count($values) - 1) . '?';

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES ({$placeholders})";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($values);
        
        return $this->connection->lastInsertId();
    }

    public function update($data)
    {
        $columns = array_keys($data);
        $setClause = implode(' = ?, ', $columns) . ' = ?';
        $values = array_values($data);

        $sql = "UPDATE {$this->table} SET {$setClause}";

        if (!empty($this->wheres)) {
            $sql .= " WHERE ";
            $whereConditions = [];
            foreach ($this->wheres as $where) {
                $whereConditions[] = "{$where['column']} {$where['operator']} ?";
            }
            $sql .= implode(' AND ', $whereConditions);
            $values = array_merge($values, $this->bindings);
        }

        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($values);
    }

    public function delete()
    {
        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->wheres)) {
            $sql .= " WHERE ";
            $whereConditions = [];
            foreach ($this->wheres as $where) {
                $whereConditions[] = "{$where['column']} {$where['operator']} ?";
            }
            $sql .= implode(' AND ', $whereConditions);
        }

        $stmt = $this->connection->prepare($sql);
        $bindings = !empty($this->wheres) ? $this->bindings : [];
        return $stmt->execute($bindings);
    }
}