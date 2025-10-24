# Challenge 26: Value Objects

## Description
In this challenge, you'll implement immutable value objects with validation. Value Objects are immutable objects that are defined by their attributes rather than their identity. They play a crucial role in domain-driven design by ensuring data integrity and encapsulating business rules. You'll create a comprehensive set of value objects for an e-commerce domain, each with proper validation, equality comparison, and immutability guarantees.

## Learning Objectives
- Understand the concept and purpose of Value Objects
- Implement immutable objects with proper encapsulation
- Create validation logic within value objects
- Implement equality comparison based on attributes
- Build reusable, type-safe value objects
- Understand the difference between Value Objects and Entities
- Implement proper error handling and validation

## Requirements

### Core Features
1. **Immutable Value Objects**
   - Implement true immutability with no setter methods
   - Ensure objects cannot be modified after creation
   - Create proper constructors with validation
   - Implement defensive copying for complex attributes
   - Handle object initialization safely

2. **Validation and Business Rules**
   - Implement comprehensive validation logic
   - Create custom validation rules for each value type
   - Handle validation errors with appropriate exceptions
   - Support complex validation scenarios
   - Implement validation composition

3. **Equality and Comparison**
   - Implement proper equality comparison based on attributes
   - Override equality operators where appropriate
   - Create hash code implementations for collections
   - Handle null values and edge cases
   - Implement comparison logic for ordered values

4. **Type Safety and Reusability**
   - Create strongly-typed value objects
   - Implement factory methods for common creation patterns
   - Support serialization and deserialization
   - Handle type conversion and casting
   - Create reusable value object base classes

### Implementation Details
1. **Value Object Base Class**
   ```php
   abstract class ValueObject
   {
       abstract public function equals(ValueObject $other): bool;
       abstract public function toArray(): array;
       abstract public function __toString(): string;
   }
   ```

2. **Concrete Value Object Implementation**
   ```php
   final class Email extends ValueObject
   {
       private string $email;
       
       public function __construct(string $email)
       {
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
               throw new InvalidArgumentException("Invalid email format: {$email}");
           }
           $this->email = $email;
       }
       
       public function equals(ValueObject $other): bool
       {
           return $other instanceof self && $this->email === $other->email;
       }
       
       public function getValue(): string
       {
           return $this->email;
       }
       
       public function __toString(): string
       {
           return $this->email;
       }
   }
   ```

## Project Structure
```
challenge-26-value-objects/
├── backend-php/
│   ├── src/
│   │   ├── ValueObject/
│   │   │   ├── ValueObject.php
│   │   │   ├── Email.php
│   │   │   ├── Money.php
│   │   │   ├── Address.php
│   │   │   ├── PhoneNumber.php
│   │   │   ├── ProductId.php
│   │   │   ├── UserId.php
│   │   │   └── DateTimeRange.php
│   │   ├── Validation/
│   │   │   ├── ValidatorInterface.php
│   │   │   ├── ValidationException.php
│   │   │   └── ValidationError.php
│   │   └── Exception/
│   │       ├── InvalidValueException.php
│   │       └── ValueObjectException.php
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
│   │   │   ├── ValueObjectsDemo.jsx
│   │   │   ├── ValidationExplorer.jsx
│   │   │   └── EqualityDemo.jsx
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
1. Navigate to the [backend-php](file:///c%3A/projects/php-fullstack-challenges/challenge-26-value-objects/backend-php) directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Start the development server:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the [frontend-react](file:///c%3A/projects/php-fullstack-challenges/challenge-26-value-objects/frontend-react) directory
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

### Value Objects Demo
- **GET** `/value-objects/types` - List available value object types
- **POST** `/value-objects/create` - Create and validate value objects
- **POST** `/value-objects/compare` - Compare value objects for equality
- **GET** `/value-objects/examples` - View usage examples

## Implementation Details

### Value Objects Concept Overview
Value Objects are immutable objects defined by their attributes rather than their identity. Two value objects with the same attributes are considered equal, regardless of whether they are the same instance.

1. **Basic Value Object Implementation**
   ```php
   final class Email extends ValueObject
   {
       private string $email;
       
       public function __construct(string $email)
       {
           $this->validate($email);
           $this->email = $email;
       }
       
       private function validate(string $email): void
       {
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
               throw new InvalidArgumentException("Invalid email format: {$email}");
           }
           
           if (strlen($email) > 255) {
               throw new InvalidArgumentException("Email too long: {$email}");
           }
       }
       
       public function equals(ValueObject $other): bool
       {
           return $other instanceof self && $this->email === $other->email;
       }
       
       public function getValue(): string
       {
           return $this->email;
       }
       
       public function __toString(): string
       {
           return $this->email;
       }
       
       public function toArray(): array
       {
           return ['email' => $this->email];
       }
   }
   
   // Usage:
   $email1 = new Email('user@example.com');
   $email2 = new Email('user@example.com');
   
   // These are equal because they have the same value
   var_dump($email1->equals($email2)); // true
   
   // But they are different instances
   var_dump($email1 === $email2); // false
   ```

2. **Complex Value Object with Multiple Attributes**
   ```php
   final class Address extends ValueObject
   {
       private string $street;
       private string $city;
       private string $postalCode;
       private string $country;
       
       public function __construct(string $street, string $city, string $postalCode, string $country)
       {
           $this->validate($street, $city, $postalCode, $country);
           $this->street = $street;
           $this->city = $city;
           $this->postalCode = $postalCode;
           $this->country = $country;
       }
       
       private function validate(string $street, string $city, string $postalCode, string $country): void
       {
           if (empty($street)) {
               throw new InvalidArgumentException("Street cannot be empty");
           }
           
           if (empty($city)) {
               throw new InvalidArgumentException("City cannot be empty");
           }
           
           if (!preg_match('/^[A-Z0-9\s\-]+$/', $postalCode)) {
               throw new InvalidArgumentException("Invalid postal code format");
           }
           
           if (strlen($country) !== 2 || !ctype_alpha($country) || !ctype_upper($country)) {
               throw new InvalidArgumentException("Country must be a 2-letter uppercase code");
           }
       }
       
       public function equals(ValueObject $other): bool
       {
           return $other instanceof self &&
                  $this->street === $other->street &&
                  $this->city === $other->city &&
                  $this->postalCode === $other->postalCode &&
                  $this->country === $other->country;
       }
       
       public function getStreet(): string
       {
           return $this->street;
       }
       
       public function getCity(): string
       {
           return $this->city;
       }
       
       public function getPostalCode(): string
       {
           return $this->postalCode;
       }
       
       public function getCountry(): string
       {
           return $this->country;
       }
       
       public function __toString(): string
       {
           return "{$this->street}, {$this->city}, {$this->postalCode}, {$this->country}";
       }
       
       public function toArray(): array
       {
           return [
               'street' => $this->street,
               'city' => $this->city,
               'postalCode' => $this->postalCode,
               'country' => $this->country,
           ];
       }
   }
   ```

3. **Money Value Object with Currency**
   ```php
   final class Money extends ValueObject
   {
       private int $amount; // Stored in cents/smallest currency unit
       private string $currency;
       
       public function __construct(int $amount, string $currency)
       {
           $this->validate($amount, $currency);
           $this->amount = $amount;
           $this->currency = strtoupper($currency);
       }
       
       private function validate(int $amount, string $currency): void
       {
           if ($amount < 0) {
               throw new InvalidArgumentException("Amount cannot be negative");
           }
           
           if (strlen($currency) !== 3 || !ctype_alpha($currency) || !ctype_upper($currency)) {
               throw new InvalidArgumentException("Currency must be a 3-letter uppercase code");
           }
       }
       
       public function equals(ValueObject $other): bool
       {
           return $other instanceof self &&
                  $this->amount === $other->amount &&
                  $this->currency === $other->currency;
       }
       
       public function add(Money $other): self
       {
           if ($this->currency !== $other->currency) {
               throw new InvalidArgumentException("Cannot add different currencies");
           }
           
           return new self($this->amount + $other->amount, $this->currency);
       }
       
       public function subtract(Money $other): self
       {
           if ($this->currency !== $other->currency) {
               throw new InvalidArgumentException("Cannot subtract different currencies");
           }
           
           $result = $this->amount - $other->amount;
           if ($result < 0) {
               throw new InvalidArgumentException("Result cannot be negative");
           }
           
           return new self($result, $this->currency);
       }
       
       public function getAmount(): int
       {
           return $this->amount;
       }
       
       public function getCurrency(): string
       {
           return $this->currency;
       }
       
       public function getFormattedAmount(): string
       {
           $amount = $this->amount / 100;
           return number_format($amount, 2) . ' ' . $this->currency;
       }
       
       public function __toString(): string
       {
           return $this->getFormattedAmount();
       }
       
       public function toArray(): array
       {
           return [
               'amount' => $this->amount,
               'currency' => $this->currency,
               'formatted' => $this->getFormattedAmount(),
           ];
       }
   }
   ```

4. **DateTime Range Value Object**
   ```php
   final class DateTimeRange extends ValueObject
   {
       private DateTimeImmutable $start;
       private DateTimeImmutable $end;
       
       public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
       {
           if ($start >= $end) {
               throw new InvalidArgumentException("Start date must be before end date");
           }
           
           $this->start = $start;
           $this->end = $end;
       }
       
       public function equals(ValueObject $other): bool
       {
           return $other instanceof self &&
                  $this->start == $other->start &&
                  $this->end == $other->end;
       }
       
       public function getStart(): DateTimeImmutable
       {
           return $this->start;
       }
       
       public function getEnd(): DateTimeImmutable
       {
           return $this->end;
       }
       
       public function getDuration(): DateInterval
       {
           return $this->start->diff($this->end);
       }
       
       public function contains(DateTimeImmutable $date): bool
       {
           return $date >= $this->start && $date <= $this->end;
       }
       
       public function overlaps(DateTimeRange $other): bool
       {
           return $this->start < $other->end && $this->end > $other->start;
       }
       
       public function __toString(): string
       {
           return $this->start->format('Y-m-d H:i:s') . ' to ' . $this->end->format('Y-m-d H:i:s');
       }
       
       public function toArray(): array
       {
           return [
               'start' => $this->start->format(DateTime::ISO8601),
               'end' => $this->end->format(DateTime::ISO8601),
               'duration' => $this->getDuration()->format('%d days, %h hours, %i minutes'),
           ];
       }
   }
   ```

5. **Value Object with Factory Methods**
   ```php
   final class PhoneNumber extends ValueObject
   {
       private string $number;
       private string $countryCode;
       
       private function __construct(string $number, string $countryCode)
       {
           $this->number = $number;
           $this->countryCode = $countryCode;
       }
       
       public static function fromString(string $phoneNumber): self
       {
           // Parse phone number format: +1-234-567-8900 or +12345678900
           if (!preg_match('/^\+(\d{1,3})[\s\-]?([\d\s\-]+)$/', $phoneNumber, $matches)) {
               throw new InvalidArgumentException("Invalid phone number format: {$phoneNumber}");
           }
           
           $countryCode = $matches[1];
           $number = preg_replace('/[\s\-]/', '', $matches[2]);
           
           return new self($number, $countryCode);
       }
       
       public static function fromComponents(string $number, string $countryCode): self
       {
           // Validate components
           if (!ctype_digit($number)) {
               throw new InvalidArgumentException("Number must contain only digits");
           }
           
           if (!ctype_digit($countryCode) || strlen($countryCode) > 3) {
               throw new InvalidArgumentException("Invalid country code");
           }
           
           return new self($number, $countryCode);
       }
       
       public function equals(ValueObject $other): bool
       {
           return $other instanceof self &&
                  $this->number === $other->number &&
                  $this->countryCode === $other->countryCode;
       }
       
       public function getNumber(): string
       {
           return $this->number;
       }
       
       public function getCountryCode(): string
       {
           return $this->countryCode;
       }
       
       public function getInternationalFormat(): string
       {
           return "+{$this->countryCode} {$this->number}";
       }
       
       public function __toString(): string
       {
           return $this->getInternationalFormat();
       }
       
       public function toArray(): array
       {
           return [
               'number' => $this->number,
               'countryCode' => $this->countryCode,
               'international' => $this->getInternationalFormat(),
           ];
       }
   }
   ```

### Value Object Base Class
A common base class can provide shared functionality:

```php
abstract class ValueObject
{
    public function equals(ValueObject $other): bool
    {
        if (get_class($this) !== get_class($other)) {
            return false;
        }
        
        return $this->toArray() === $other->toArray();
    }
    
    public function hash(): string
    {
        return md5(serialize($this->toArray()));
    }
    
    abstract public function toArray(): array;
    abstract public function __toString(): string;
}
```

### Frontend Interface
The React frontend should:
1. Demonstrate value object concepts
2. Show validation in action
3. Visualize equality comparisons
4. Display creation and usage examples
5. Provide interactive validation testing

## Evaluation Criteria
1. **Correctness** (30%)
   - Proper implementation of immutable value objects
   - Accurate validation logic
   - Correct equality comparison
   - Proper error handling

2. **Design Quality** (25%)
   - Clean, well-structured code
   - Proper encapsulation
   - Maintainable architecture

3. **Functionality** (20%)
   - Complete set of value objects
   - Comprehensive validation
   - Useful business logic methods

4. **Type Safety** (15%)
   - Strong typing throughout
   - Proper interface implementation
   - Safe serialization/deserialization

5. **Educational Value** (10%)
   - Clear explanations of value objects
   - Practical examples and use cases
   - Interactive demonstrations

## Resources
1. [Value Object Pattern - Martin Fowler](https://martinfowler.com/bliki/ValueObject.html)
2. [Domain-Driven Design by Eric Evans](https://www.amazon.com/Domain-Driven-Design-Tackling-Complexity-Software/dp/0321125215)
3. [Implementing Domain-Driven Design by Vaughn Vernon](https://www.amazon.com/Implementing-Domain-Driven-Design-Vaughn-Vernon/dp/0321834577)
4. [PHP Immutable Objects](https://www.php.net/manual/en/language.oop5.final.php)
5. [Value Objects in PHP](https://www.sitepoint.com/value-objects-in-php/)
6. [Equality and Hash Codes in PHP](https://www.php.net/manual/en/language.oop5.object-comparison.php)

## Stretch Goals
1. Implement value object serialization/deserialization
2. Add support for complex validation rules
3. Create a value object generator tool
4. Implement performance benchmarks
5. Add comprehensive unit tests
6. Create a visual value object explorer
7. Implement value object collections