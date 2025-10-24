# Challenge 21: SOLID Principles Implementation

## Description
In this challenge, you'll refactor legacy code to comply with all SOLID principles. SOLID is an acronym for five design principles that make software designs more understandable, flexible, and maintainable. You'll work with a poorly designed e-commerce system and transform it into a well-structured, extensible architecture that follows Single Responsibility, Open/Closed, Liskov Substitution, Interface Segregation, and Dependency Inversion principles.

## Learning Objectives
- Understand and apply all five SOLID principles
- Identify violations of SOLID principles in existing code
- Refactor legacy code to improve design
- Create maintainable and extensible architectures
- Implement proper separation of concerns
- Build testable and loosely coupled systems

## Requirements

### Core Features
1. **Single Responsibility Principle (SRP)**
   - Ensure each class has only one reason to change
   - Separate concerns into distinct classes
   - Create focused, cohesive modules
   - Eliminate god objects and multi-purpose classes

2. **Open/Closed Principle (OCP)**
   - Make classes open for extension but closed for modification
   - Use inheritance and interfaces for extensibility
   - Implement strategy patterns for varying behaviors
   - Create plugin architectures where appropriate

3. **Liskov Substitution Principle (LSP)**
   - Ensure derived classes can substitute base classes
   - Maintain behavioral compatibility in inheritance
   - Avoid breaking contracts in subclass implementations
   - Implement proper polymorphic behavior

4. **Interface Segregation Principle (ISP)**
   - Create small, focused interfaces
   - Avoid fat interfaces with many methods
   - Implement role-based interfaces
   - Apply the principle of least knowledge

5. **Dependency Inversion Principle (DIP)**
   - Depend on abstractions, not concretions
   - Use dependency injection for loose coupling
   - Implement inversion of control patterns
   - Create stable abstraction layers

### Implementation Details
1. **Before and After Comparison**
   - Provide the original legacy code
   - Show refactored code following SOLID principles
   - Document the transformation process
   - Explain the benefits of each refactoring

2. **E-Commerce System Components**
   - Product management
   - Order processing
   - Payment handling
   - Inventory management
   - Notification system
   - Reporting features

## Project Structure
```
challenge-21-solid-principles/
├── backend-php/
│   ├── src/
│   │   ├── Legacy/ (Original problematic code)
│   │   ├── Refactored/ (SOLID-compliant implementation)
│   │   │   ├── Product/
│   │   │   │   ├── Product.php
│   │   │   │   ├── ProductRepository.php
│   │   │   │   └── ProductService.php
│   │   │   ├── Order/
│   │   │   │   ├── Order.php
│   │   │   │   ├── OrderProcessor.php
│   │   │   │   └── OrderRepository.php
│   │   │   ├── Payment/
│   │   │   │   ├── PaymentProcessor.php
│   │   │   │   ├── PaymentMethodInterface.php
│   │   │   │   ├── CreditCardProcessor.php
│   │   │   │   └── PayPalProcessor.php
│   │   │   ├── Inventory/
│   │   │   │   ├── InventoryManager.php
│   │   │   │   └── StockChecker.php
│   │   │   ├── Notification/
│   │   │   │   ├── NotificationService.php
│   │   │   │   ├── NotificationInterface.php
│   │   │   │   ├── EmailNotification.php
│   │   │   │   └── SMSNotification.php
│   │   │   └── Report/
│   │   │       ├── ReportGenerator.php
│   │   │       ├── ReportInterface.php
│   │   │       ├── SalesReport.php
│   │   │       └── InventoryReport.php
│   │   └── Container/
│   │       ├── DIContainer.php
│   │       └── Configurator.php
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
│   │   │   ├── SolidPrinciplesDemo.jsx
│   │   │   ├── CodeComparison.jsx
│   │   │   └── PrincipleExplanation.jsx
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
1. Navigate to the [backend-php](file:///c%3A/projects/php-fullstack-challenges/challenge-21-solid-principles/backend-php) directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Run the refactored application:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the [frontend-react](file:///c%3A/projects/php-fullstack-challenges/challenge-21-solid-principles/frontend-react) directory
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

### SOLID Principles Demo
- **GET** `/solid/principles` - Get explanation of all SOLID principles
- **GET** `/solid/before-refactor` - View legacy code examples
- **GET** `/solid/after-refactor` - View refactored code examples
- **POST** `/solid/analyze` - Analyze code for SOLID violations

## Implementation Details

### Single Responsibility Principle
Example violation:
```php
// Violation - Order class doing too much
class Order
{
    public function calculateTotal() { /* ... */ }
    public function processPayment() { /* ... */ }
    public function sendEmail() { /* ... */ }
    public function updateInventory() { /* ... */ }
}
```

SOLID-compliant solution:
```php
// Separate responsibilities into distinct classes
class Order
{
    public function calculateTotal() { /* ... */ }
}

class PaymentProcessor
{
    public function process(Order $order) { /* ... */ }
}

class EmailService
{
    public function send(Order $order) { /* ... */ }
}

class InventoryManager
{
    public function update(Order $order) { /* ... */ }
}
```

### Open/Closed Principle
Example implementation:
```php
interface PaymentMethodInterface
{
    public function process(float $amount): bool;
}

class CreditCardProcessor implements PaymentMethodInterface
{
    public function process(float $amount): bool { /* ... */ }
}

class PayPalProcessor implements PaymentMethodInterface
{
    public function process(float $amount): bool { /* ... */ }
}

class OrderProcessor
{
    public function process(Order $order, PaymentMethodInterface $paymentMethod)
    {
        return $paymentMethod->process($order->getTotal());
    }
}
```

### Liskov Substitution Principle
Example implementation:
```php
abstract class Bird
{
    abstract public function move(): string;
}

class Sparrow extends Bird
{
    public function move(): string
    {
        return "Flying";
    }
}

class Penguin extends Bird
{
    public function move(): string
    {
        return "Swimming";
    }
}

// Both can substitute Bird without breaking the contract
function makeBirdMove(Bird $bird): string
{
    return $bird->move();
}
```

### Interface Segregation Principle
Example implementation:
```php
// Instead of one fat interface
interface WorkerInterface
{
    public function work();
    public function eat();
    public function sleep();
}

// Create focused interfaces
interface WorkableInterface
{
    public function work();
}

interface EatableInterface
{
    public function eat();
}

interface SleepableInterface
{
    public function sleep();
}

class Human implements WorkableInterface, EatableInterface, SleepableInterface
{
    public function work() { /* ... */ }
    public function eat() { /* ... */ }
    public function sleep() { /* ... */ }
}

class Robot implements WorkableInterface
{
    public function work() { /* ... */ }
}
```

### Dependency Inversion Principle
Example implementation:
```php
// Depend on abstractions
interface RepositoryInterface
{
    public function save($data);
    public function find($id);
}

class DatabaseRepository implements RepositoryInterface
{
    public function save($data) { /* ... */ }
    public function find($id) { /* ... */ }
}

class OrderService
{
    private RepositoryInterface $repository;
    
    // Inject abstraction, not concretion
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function processOrder(Order $order)
    {
        $this->repository->save($order);
    }
}
```

### Frontend Interface
The React frontend should:
1. Explain each SOLID principle with examples
2. Show before/after code comparisons
3. Provide interactive code analysis
4. Visualize the refactoring process
5. Offer quizzes to test understanding

## Evaluation Criteria
1. **Correctness** (30%)
   - Proper implementation of all five SOLID principles
   - Accurate identification of violations in legacy code
   - Complete refactoring of problematic code

2. **Design Quality** (25%)
   - Clear separation of concerns
   - Proper use of design patterns
   - Maintainable and extensible architecture

3. **Code Organization** (20%)
   - Well-structured project layout
   - Consistent naming conventions
   - Appropriate use of interfaces and abstractions

4. **Documentation** (15%)
   - Clear explanations of transformations
   - Helpful comments and annotations
   - Comprehensive README documentation

5. **Educational Value** (10%)
   - Clear presentation of concepts
   - Interactive learning elements
   - Practical examples and use cases

## Resources
1. [SOLID Principles Wikipedia](https://en.wikipedia.org/wiki/SOLID)
2. [Uncle Bob's Original Papers](https://blog.cleancoder.com/uncle-bob/2020/10/18/Solid-Relevance.html)
3. [Refactoring Guru - SOLID Principles](https://refactoring.guru/design-patterns/solid)
4. [Clean Code by Robert Martin](https://www.amazon.com/Clean-Code-Handbook-Software-Craftsmanship/dp/0132350882)
5. [Design Patterns: Elements of Reusable Object-Oriented Software](https://www.amazon.com/Design-Patterns-Elements-Reusable-Object-Oriented/dp/0201633612)
6. [PHP Best Practices](https://phpbestpractices.org/)

## Stretch Goals
1. Implement additional design patterns in the refactored code
2. Create automated tools to detect SOLID violations
3. Add more complex e-commerce features to refactor
4. Implement a plugin architecture for payment methods
5. Create performance benchmarks comparing before/after
6. Add comprehensive unit tests for all refactored components