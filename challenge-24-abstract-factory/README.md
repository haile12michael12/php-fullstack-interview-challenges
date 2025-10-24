# Challenge 24: Abstract Factory Pattern

## Description
In this challenge, you'll build a database connection factory supporting multiple drivers. The Abstract Factory pattern provides an interface for creating families of related or dependent objects without specifying their concrete classes. You'll create a flexible database abstraction layer that can instantiate different database drivers (MySQL, PostgreSQL, SQLite, etc.) while maintaining a consistent interface.

## Learning Objectives
- Understand the Abstract Factory design pattern
- Implement factory interfaces and concrete implementations
- Create families of related objects
- Build database abstraction layers
- Handle multiple driver implementations
- Maintain consistency across different implementations
- Implement proper error handling and validation

## Requirements

### Core Features
1. **Abstract Factory Interface**
   - Define interface for creating database connections
   - Create interfaces for related database components
   - Implement factory methods for different object types
   - Support extensibility for new database drivers
   - Maintain consistent object interfaces

2. **Concrete Factory Implementations**
   - Implement MySQL database factory
   - Implement PostgreSQL database factory
   - Implement SQLite database factory
   - Create database-specific connection objects
   - Handle driver-specific configurations

3. **Database Connection Objects**
   - Implement connection interfaces
   - Create concrete connection implementations
   - Handle connection establishment and closure
   - Support query execution and result handling
   - Implement proper resource management

4. **Query Builder Integration**
   - Create database-agnostic query builders
   - Handle driver-specific SQL dialects
   - Support prepared statements
   - Implement result set abstractions
   - Handle transactions consistently

### Implementation Details
1. **Factory Interface**
   ```php
   interface DatabaseFactoryInterface
   {
       public function createConnection(): ConnectionInterface;
       public function createQueryBuilder(): QueryBuilderInterface;
       public function createSchemaManager(): SchemaManagerInterface;
   }
   ```

2. **Connection Interface**
   ```php
   interface ConnectionInterface
   {
       public function connect(): void;
       public function disconnect(): void;
       public function query(string $sql, array $params = []): ResultSetInterface;
       public function prepare(string $sql): StatementInterface;
       public function beginTransaction(): void;
       public function commit(): void;
       public function rollback(): void;
   }
   ```

## Project Structure
```
challenge-24-abstract-factory/
├── backend-php/
│   ├── src/
│   │   ├── Database/
│   │   │   ├── Factory/
│   │   │   │   ├── DatabaseFactoryInterface.php
│   │   │   │   ├── MySQLFactory.php
│   │   │   │   ├── PostgreSQLFactory.php
│   │   │   │   └── SQLiteFactory.php
│   │   │   ├── Connection/
│   │   │   │   ├── ConnectionInterface.php
│   │   │   │   ├── MySQLConnection.php
│   │   │   │   ├── PostgreSQLConnection.php
│   │   │   │   └── SQLiteConnection.php
│   │   │   ├── Query/
│   │   │   │   ├── QueryBuilderInterface.php
│   │   │   │   ├── MySQLQueryBuilder.php
│   │   │   │   ├── PostgreSQLQueryBuilder.php
│   │   │   │   └── SQLiteQueryBuilder.php
│   │   │   ├── Result/
│   │   │   │   ├── ResultSetInterface.php
│   │   │   │   └── StatementInterface.php
│   │   │   └── Exception/
│   │   │       ├── DatabaseException.php
│   │   │       └── ConnectionException.php
│   │   └── Application/
│   │       ├── DatabaseManager.php
│   │       └── Configuration.php
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
│   │   │   ├── AbstractFactoryDemo.jsx
│   │   │   ├── DatabaseSelector.jsx
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
1. Navigate to the [backend-php](file:///c%3A/projects/php-fullstack-challenges/challenge-24-abstract-factory/backend-php) directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Start the development server:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the [frontend-react](file:///c%3A/projects/php-fullstack-challenges/challenge-24-abstract-factory/frontend-react) directory
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

### Abstract Factory Demo
- **GET** `/factory/drivers` - List available database drivers
- **POST** `/factory/connect` - Create connection with selected driver
- **POST** `/factory/query` - Execute query on selected connection
- **GET** `/factory/config` - View current configuration

## Implementation Details

### Abstract Factory Pattern Overview
The Abstract Factory pattern provides an interface for creating families of related or dependent objects without specifying their concrete classes:

1. **Abstract Factory Interface**
   ```php
   interface DatabaseFactoryInterface
   {
       public function createConnection(): ConnectionInterface;
       public function createQueryBuilder(): QueryBuilderInterface;
       public function createSchemaManager(): SchemaManagerInterface;
   }
   ```

2. **Concrete Factory Implementations**
   ```php
   class MySQLFactory implements DatabaseFactoryInterface
   {
       private array $config;
       
       public function __construct(array $config)
       {
           $this->config = $config;
       }
       
       public function createConnection(): ConnectionInterface
       {
           return new MySQLConnection($this->config);
       }
       
       public function createQueryBuilder(): QueryBuilderInterface
       {
           return new MySQLQueryBuilder();
       }
       
       public function createSchemaManager(): SchemaManagerInterface
       {
           return new MySQLSchemaManager($this->createConnection());
       }
   }
   
   class PostgreSQLFactory implements DatabaseFactoryInterface
   {
       private array $config;
       
       public function __construct(array $config)
       {
           $this->config = $config;
       }
       
       public function createConnection(): ConnectionInterface
       {
           return new PostgreSQLConnection($this->config);
       }
       
       public function createQueryBuilder(): QueryBuilderInterface
       {
           return new PostgreSQLQueryBuilder();
       }
       
       public function createSchemaManager(): SchemaManagerInterface
       {
           return new PostgreSQLSchemaManager($this->createConnection());
       }
   }
   ```

3. **Connection Interface and Implementations**
   ```php
   interface ConnectionInterface
   {
       public function connect(): void;
       public function disconnect(): void;
       public function query(string $sql, array $params = []): ResultSetInterface;
       public function prepare(string $sql): StatementInterface;
       public function beginTransaction(): void;
       public function commit(): void;
       public function rollback(): void;
       public function getLastInsertId(): ?int;
   }
   
   class MySQLConnection implements ConnectionInterface
   {
       private ?PDO $pdo = null;
       private array $config;
       
       public function __construct(array $config)
       {
           $this->config = $config;
       }
       
       public function connect(): void
       {
           if ($this->pdo === null) {
               $dsn = "mysql:host={$this->config['host']};dbname={$this->config['database']};charset={$this->config['charset']}";
               $this->pdo = new PDO($dsn, $this->config['username'], $this->config['password'], [
                   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
               ]);
           }
       }
       
       public function disconnect(): void
       {
           $this->pdo = null;
       }
       
       public function query(string $sql, array $params = []): ResultSetInterface
       {
           $this->connect();
           $stmt = $this->pdo->prepare($sql);
           $stmt->execute($params);
           return new MySQLResultSet($stmt->fetchAll());
       }
       
       public function prepare(string $sql): StatementInterface
       {
           $this->connect();
           return new MySQLStatement($this->pdo->prepare($sql));
       }
       
       public function beginTransaction(): void
       {
           $this->connect();
           $this->pdo->beginTransaction();
       }
       
       public function commit(): void
       {
           $this->connect();
           $this->pdo->commit();
       }
       
       public function rollback(): void
       {
           $this->connect();
           $this->pdo->rollback();
       }
       
       public function getLastInsertId(): ?int
       {
           $this->connect();
           return (int) $this->pdo->lastInsertId();
       }
   }
   ```

4. **Database Manager Using Abstract Factory**
   ```php
   class DatabaseManager
   {
       private DatabaseFactoryInterface $factory;
       private ?ConnectionInterface $connection = null;
       
       public function __construct(DatabaseFactoryInterface $factory)
       {
           $this->factory = $factory;
       }
       
       public function getConnection(): ConnectionInterface
       {
           if ($this->connection === null) {
               $this->connection = $this->factory->createConnection();
           }
           return $this->connection;
       }
       
       public function getQueryBuilder(): QueryBuilderInterface
       {
           return $this->factory->createQueryBuilder();
       }
       
       public function getSchemaManager(): SchemaManagerInterface
       {
           return $this->factory->createSchemaManager();
       }
       
       public static function create(string $driver, array $config): self
       {
           switch ($driver) {
               case 'mysql':
                   return new self(new MySQLFactory($config));
               case 'postgresql':
                   return new self(new PostgreSQLFactory($config));
               case 'sqlite':
                   return new self(new SQLiteFactory($config));
               default:
                   throw new InvalidArgumentException("Unsupported driver: {$driver}");
           }
       }
   }
   
   // Usage:
   $config = [
       'host' => 'localhost',
       'database' => 'test',
       'username' => 'root',
       'password' => 'password',
       'charset' => 'utf8mb4'
   ];
   
   $db = DatabaseManager::create('mysql', $config);
   $connection = $db->getConnection();
   $users = $connection->query('SELECT * FROM users');
   ```

### Query Builder Integration
Each database driver should have its own query builder implementation:

1. **Abstract Query Builder**
   ```php
   abstract class AbstractQueryBuilder implements QueryBuilderInterface
   {
       protected array $select = [];
       protected string $from = '';
       protected array $where = [];
       protected array $orderBy = [];
       protected ?int $limit = null;
       protected ?int $offset = null;
       
       public function select(array $columns): self
       {
           $this->select = $columns;
           return $this;
       }
       
       public function from(string $table): self
       {
           $this->from = $table;
           return $this;
       }
       
       public function where(string $column, $value, string $operator = '='): self
       {
           $this->where[] = [$column, $value, $operator];
           return $this;
       }
       
       public function orderBy(string $column, string $direction = 'ASC'): self
       {
           $this->orderBy[] = [$column, $direction];
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
       
       abstract public function toSql(): string;
       abstract public function getParameters(): array;
   }
   ```

2. **MySQL Query Builder**
   ```php
   class MySQLQueryBuilder extends AbstractQueryBuilder
   {
       public function toSql(): string
       {
           $sql = 'SELECT ';
           $sql .= empty($this->select) ? '*' : implode(', ', $this->select);
           $sql .= " FROM {$this->from}";
           
           if (!empty($this->where)) {
               $conditions = [];
               foreach ($this->where as $condition) {
                   $conditions[] = "{$condition[0]} {$condition[2]} ?";
               }
               $sql .= ' WHERE ' . implode(' AND ', $conditions);
           }
           
           if (!empty($this->orderBy)) {
               $orderClauses = [];
               foreach ($this->orderBy as $order) {
                   $orderClauses[] = "{$order[0]} {$order[1]}";
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
           return array_column($this->where, 1);
       }
   }
   ```

### Frontend Interface
The React frontend should:
1. Demonstrate the Abstract Factory pattern
2. Allow selection of different database drivers
3. Show how the same interface works with different implementations
4. Provide interactive query execution
5. Visualize the factory creation process

## Evaluation Criteria
1. **Correctness** (30%)
   - Proper implementation of Abstract Factory pattern
   - Accurate interface definitions and implementations
   - Correct handling of different database drivers

2. **Design Quality** (25%)
   - Clean, well-structured code
   - Proper separation of concerns
   - Maintainable architecture

3. **Functionality** (20%)
   - Complete database abstraction layer
   - Working connection implementations
   - Query builder integration

4. **Extensibility** (15%)
   - Easy to add new database drivers
   - Flexible factory interface
   - Consistent object interfaces

5. **Educational Value** (10%)
   - Clear explanations of Abstract Factory pattern
   - Practical examples and use cases
   - Interactive demonstrations

## Resources
1. [Abstract Factory Pattern - Wikipedia](https://en.wikipedia.org/wiki/Abstract_factory_pattern)
2. [Design Patterns: Elements of Reusable Object-Oriented Software](https://www.amazon.com/Design-Patterns-Elements-Reusable-Object-Oriented/dp/0201633612)
3. [PHP Design Patterns](https://designpatternsphp.readthedocs.io/en/latest/)
4. [Database Abstraction Layer Patterns](https://martinfowler.com/eaaCatalog/dataMapper.html)
5. [PDO Documentation](https://www.php.net/manual/en/book.pdo.php)
6. [Factory Method vs Abstract Factory](https://stackoverflow.com/questions/5739611/differences-between-abstract-factory-pattern-and-factory-method)

## Stretch Goals
1. Implement connection pooling with the factory pattern
2. Add support for NoSQL databases (MongoDB, Redis)
3. Create a configuration-based factory resolver
4. Implement database migration support
5. Add performance monitoring and logging
6. Create a visual factory creation flowchart
7. Implement database-specific optimization features