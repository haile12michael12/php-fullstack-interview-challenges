# Challenge 25: Fluent Interface

## Description
In this challenge, you'll create a query builder with fluent interface design. The Fluent Interface pattern allows for more readable and expressive code by chaining method calls together. You'll build a sophisticated SQL query builder that enables developers to construct complex database queries using a natural, English-like syntax.

## Learning Objectives
- Understand the Fluent Interface design pattern
- Implement method chaining for improved code readability
- Create expressive APIs with fluent interfaces
- Build SQL query builders with intuitive syntax
- Handle complex query construction with method chaining
- Implement proper immutability and state management
- Understand performance implications of fluent interfaces

## Requirements

### Core Features
1. **Query Builder with Method Chaining**
   - Implement SELECT, INSERT, UPDATE, DELETE statements
   - Support WHERE, ORDER BY, LIMIT, JOIN clauses
   - Enable method chaining for all query operations
   - Handle parameter binding for security
   - Support subqueries and nested conditions

2. **SQL Clause Construction**
   - Build SELECT clauses with column selection
   - Create FROM clauses with table specifications
   - Implement WHERE conditions with operators
   - Support ORDER BY with sorting options
   - Handle LIMIT and OFFSET for pagination

3. **Advanced Query Features**
   - Implement JOIN operations (INNER, LEFT, RIGHT, OUTER)
   - Support GROUP BY and HAVING clauses
   - Handle UNION and INTERSECT operations
   - Create subqueries and nested queries
   - Support aggregate functions (COUNT, SUM, AVG, etc.)

4. **Database Compatibility**
   - Support multiple SQL dialects (MySQL, PostgreSQL, SQLite)
   - Handle database-specific syntax variations
   - Implement proper escaping and quoting
   - Support prepared statements for security
   - Handle different data types appropriately

### Implementation Details
1. **Fluent Interface Design**
   ```php
   interface QueryBuilderInterface
   {
       public function select(array $columns): self;
       public function from(string $table): self;
       public function where(string $column, $value, string $operator = '='): self;
       public function orderBy(string $column, string $direction = 'ASC'): self;
       public function limit(int $limit): self;
       public function offset(int $offset): self;
       public function toSql(): string;
       public function getParameters(): array;
   }
   ```

2. **Method Chaining Implementation**
   ```php
   class QueryBuilder implements QueryBuilderInterface
   {
       public function select(array $columns): self { /* ... */ return $this; }
       public function from(string $table): self { /* ... */ return $this; }
       public function where(string $column, $value, string $operator = '='): self { /* ... */ return $this; }
       // ... other methods
   }
   ```

## Project Structure
```
challenge-25-fluent-interface/
├── backend-php/
│   ├── src/
│   │   ├── Query/
│   │   │   ├── QueryBuilderInterface.php
│   │   │   ├── QueryBuilder.php
│   │   │   ├── MySQLQueryBuilder.php
│   │   │   ├── PostgreSQLQueryBuilder.php
│   │   │   └── SQLiteQueryBuilder.php
│   │   ├── Clause/
│   │   │   ├── SelectClause.php
│   │   │   ├── FromClause.php
│   │   │   ├── WhereClause.php
│   │   │   ├── JoinClause.php
│   │   │   ├── OrderByClause.php
│   │   │   └── LimitClause.php
│   │   ├── Expression/
│   │   │   ├── Condition.php
│   │   │   ├── Join.php
│   │   │   └── Subquery.php
│   │   └── Exception/
│   │       ├── QueryException.php
│   │       └── InvalidClauseException.php
│   ├── config/
│   ├── public/
│   │   └── index.php
│   ├── tests/
│   ├── composer.json
│   ├── Dockerfile
│   └── README.md
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── FluentInterfaceDemo.jsx
│   │   │   ├── QueryBuilder.jsx
│   │   │   └── QueryExecutor.jsx
│   │   └── App.jsx
│   ├── package.json
│   ├── vite.config.js
│   ├── Dockerfile
│   └── README.md
├── docker-compose.yml
└── README.md
```

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 16+
- npm or yarn
- Docker (optional, for containerized deployment)

### Backend Setup
1. Navigate to the [backend-php](file:///c%3A/projects/php-fullstack-challenges/challenge-25-fluent-interface/backend-php) directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Start the development server:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the [frontend-react](file:///c%3A/projects/php-fullstack-challenges/challenge-25-fluent-interface/frontend-react) directory
2. Install JavaScript dependencies:
   ```bash
   npm install
   ```
3. Start the development server:
   ```bash
   npm run dev
   ```

### Docker Setup
1. From the challenge root directory, run:
   ```bash
   docker-compose up -d
   ```
2. Access the application at `http://localhost:3000`

## API Endpoints

### Fluent Interface Demo
- **GET** `/fluent/query/build` - Build query using fluent interface
- **POST** `/fluent/query/execute` - Execute built query
- **GET** `/fluent/query/history` - View query history
- **GET** `/fluent/syntax` - View supported syntax

## Implementation Details

### Fluent Interface Pattern Overview
The Fluent Interface pattern allows for method chaining by returning the current object instance from each method:

1. **Basic Fluent Interface Implementation**
   ```php
   class QueryBuilder
   {
       private array $select = [];
       private string $from = '';
       private array $where = [];
       private array $orderBy = [];
       private ?int $limit = null;
       private ?int $offset = null;
       
       public function select(array $columns): self
       {
           $this->select = $columns;
           return $this; // Return $this for method chaining
       }
       
       public function from(string $table): self
       {
           $this->from = $table;
           return $this;
       }
       
       public function where(string $column, $value, string $operator = '='): self
       {
           $this->where[] = [
               'column' => $column,
               'value' => $value,
               'operator' => $operator
           ];
           return $this;
       }
       
       public function orderBy(string $column, string $direction = 'ASC'): self
       {
           $this->orderBy[] = [
               'column' => $column,
               'direction' => strtoupper($direction)
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
       
       public function toSql(): string
       {
           $sql = 'SELECT ';
           $sql .= empty($this->select) ? '*' : implode(', ', $this->select);
           $sql .= " FROM {$this->from}";
           
           if (!empty($this->where)) {
               $conditions = [];
               foreach ($this->where as $condition) {
                   $conditions[] = "{$condition['column']} {$condition['operator']} ?";
               }
               $sql .= ' WHERE ' . implode(' AND ', $conditions);
           }
           
           if (!empty($this->orderBy)) {
               $orderClauses = [];
               foreach ($this->orderBy as $order) {
                   $orderClauses[] = "{$order['column']} {$order['direction']}";
               }
               $sql .= ' ORDER BY ' . implode(', ', $orderClauses);
           }
           
           if ($this->limit !== null) {
               $sql .= " LIMIT {$this->limit}";
           }
           
           if ($this->offset !== null) {
               $sql .= " OFFSET {$this->offset}";
           }
           
           return $sql;
       }
       
       public function getParameters(): array
       {
           return array_column($this->where, 'value');
       }
   }
   
   // Usage with method chaining:
   $query = (new QueryBuilder())
       ->select(['id', 'name', 'email'])
       ->from('users')
       ->where('age', 18, '>=')
       ->where('status', 'active')
       ->orderBy('name', 'ASC')
       ->limit(10)
       ->offset(0);
   
   echo $query->toSql();
   // Output: SELECT id, name, email FROM users WHERE age >= ? AND status = ? ORDER BY name ASC LIMIT 10 OFFSET 0
   ```

2. **Advanced Fluent Interface with Immutability**
   ```php
   class ImmutableQueryBuilder
   {
       private array $select;
       private string $from;
       private array $where;
       private array $orderBy;
       private ?int $limit;
       private ?int $offset;
       
       public function __construct(
           array $select = [],
           string $from = '',
           array $where = [],
           array $orderBy = [],
           ?int $limit = null,
           ?int $offset = null
       ) {
           $this->select = $select;
           $this->from = $from;
           $this->where = $where;
           $this->orderBy = $orderBy;
           $this->limit = $limit;
           $this->offset = $offset;
       }
       
       public function select(array $columns): self
       {
           $clone = clone $this;
           $clone->select = $columns;
           return $clone; // Return new instance for immutability
       }
       
       public function from(string $table): self
       {
           $clone = clone $this;
           $clone->from = $table;
           return $clone;
       }
       
       public function where(string $column, $value, string $operator = '='): self
       {
           $clone = clone $this;
           $clone->where[] = [
               'column' => $column,
               'value' => $value,
               'operator' => $operator
           ];
           return $clone;
       }
       
       // ... other methods
   }
   ```

3. **JOIN Operations with Fluent Interface**
   ```php
   class QueryBuilder
   {
       private array $joins = [];
       
       public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): self
       {
           $this->joins[] = [
               'type' => strtoupper($type),
               'table' => $table,
               'first' => $first,
               'operator' => $operator,
               'second' => $second
           ];
           return $this;
       }
       
       public function leftJoin(string $table, string $first, string $operator, string $second): self
       {
           return $this->join($table, $first, $operator, $second, 'LEFT');
       }
       
       public function rightJoin(string $table, string $first, string $operator, string $second): self
       {
           return $this->join($table, $first, $operator, $second, 'RIGHT');
       }
       
       private function buildJoinClause(): string
       {
           if (empty($this->joins)) {
               return '';
           }
           
           $clauses = [];
           foreach ($this->joins as $join) {
               $clauses[] = "{$join['type']} JOIN {$join['table']} ON {$join['first']} {$join['operator']} {$join['second']}";
           }
           
           return ' ' . implode(' ', $clauses);
       }
       
       public function toSql(): string
       {
           $sql = 'SELECT ';
           $sql .= empty($this->select) ? '*' : implode(', ', $this->select);
           $sql .= " FROM {$this->from}";
           $sql .= $this->buildJoinClause(); // Add JOIN clauses
           
           // ... rest of SQL building
           return $sql;
       }
   }
   
   // Usage:
   $query = (new QueryBuilder())
       ->select(['u.name', 'p.title'])
       ->from('users u')
       ->leftJoin('posts p', 'u.id', '=', 'p.user_id')
       ->where('u.status', 'active')
       ->orderBy('u.name');
   ```

4. **Subqueries and Nested Conditions**
   ```php
   class QueryBuilder
   {
       public function whereIn(string $column, QueryBuilder $subquery): self
       {
           $this->where[] = [
               'type' => 'subquery',
               'column' => $column,
               'subquery' => $subquery
           ];
           return $this;
       }
       
       public function whereExists(QueryBuilder $subquery): self
       {
           $this->where[] = [
               'type' => 'exists',
               'subquery' => $subquery
           ];
           return $this;
       }
       
       private function buildWhereClause(): string
       {
           if (empty($this->where)) {
               return '';
           }
           
           $clauses = [];
           foreach ($this->where as $condition) {
               if ($condition['type'] ?? '' === 'subquery') {
                   $clauses[] = "{$condition['column']} IN ({$condition['subquery']->toSql()})";
               } elseif ($condition['type'] ?? '' === 'exists') {
                   $clauses[] = "EXISTS ({$condition['subquery']->toSql()})";
               } else {
                   $clauses[] = "{$condition['column']} {$condition['operator']} ?";
               }
           }
           
           return ' WHERE ' . implode(' AND ', $clauses);
       }
   }
   
   // Usage:
   $subquery = (new QueryBuilder())
       ->select(['user_id'])
       ->from('orders')
       ->where('total', 1000, '>=');
   
   $query = (new QueryBuilder())
       ->select(['*'])
       ->from('users')
       ->whereIn('id', $subquery)
       ->orderBy('name');
   ```

### Database-Specific Implementations
Different databases may require different SQL syntax:

1. **MySQL Query Builder**
   ```php
   class MySQLQueryBuilder extends QueryBuilder
   {
       public function limit(int $limit, ?int $offset = null): self
       {
           $this->limit = $limit;
           if ($offset !== null) {
               $this->offset = $offset;
           }
           return $this;
       }
       
       protected function buildLimitClause(): string
       {
           if ($this->limit === null) {
               return '';
           }
           
           $clause = " LIMIT {$this->limit}";
           if ($this->offset !== null) {
               $clause .= " OFFSET {$this->offset}";
           }
           
           return $clause;
       }
   }
   ```

2. **PostgreSQL Query Builder**
   ```php
   class PostgreSQLQueryBuilder extends QueryBuilder
   {
       public function limit(int $limit, ?int $offset = null): self
       {
           $this->limit = $limit;
           if ($offset !== null) {
               $this->offset = $offset;
           }
           return $this;
       }
       
       protected function buildLimitClause(): string
       {
           $clause = '';
           if ($this->limit !== null) {
               $clause .= " LIMIT {$this->limit}";
           }
           if ($this->offset !== null) {
               $clause .= " OFFSET {$this->offset}";
           }
           return $clause;
       }
   }
   ```

### Frontend Interface
The React frontend should:
1. Demonstrate fluent interface concepts
2. Provide a visual query builder interface
3. Show method chaining in action
4. Display generated SQL queries
5. Allow execution of built queries

## Evaluation Criteria
1. **Correctness** (30%)
   - Proper implementation of fluent interface pattern
   - Accurate SQL generation
   - Correct method chaining behavior

2. **Design Quality** (25%)
   - Clean, well-structured code
   - Proper separation of concerns
   - Maintainable architecture

3. **Functionality** (20%)
   - Complete query builder feature set
   - Support for complex queries
   - Database compatibility

4. **Performance** (15%)
   - Efficient query building
   - Proper parameter handling
   - Minimal overhead

5. **Educational Value** (10%)
   - Clear explanations of fluent interface pattern
   - Practical examples and use cases
   - Interactive demonstrations

## Resources
1. [Fluent Interface Pattern - Martin Fowler](https://martinfowler.com/bliki/FluentInterface.html)
2. [Design Patterns: Elements of Reusable Object-Oriented Software](https://www.amazon.com/Design-Patterns-Elements-Reusable-Object-Oriented/dp/0201633612)
3. [PHP Method Chaining](https://www.php.net/manual/en/language.oop5.chaining.php)
4. [Laravel Query Builder](https://laravel.com/docs/queries)
5. [Doctrine Query Builder](https://www.doctrine-project.org/projects/doctrine-orm/en/2.9/reference/query-builder.html)
6. [Fluent Interface Best Practices](https://www.sitepoint.com/the-fluent-interface-pattern-in-php/)

## Stretch Goals
1. Implement query caching for performance
2. Add support for stored procedures
3. Create a visual query plan analyzer
4. Implement database migration support
5. Add performance monitoring and logging
6. Create a query optimization assistant
7. Implement database-specific optimization features