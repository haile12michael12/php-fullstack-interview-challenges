# Challenge 28: Strategy Pattern

## Description
This challenge focuses on implementing the Strategy design pattern to create a payment processing system with multiple gateway strategies. You'll learn to encapsulate algorithms and make them interchangeable at runtime.

## Learning Objectives
- Implement the Strategy design pattern
- Encapsulate algorithms and behaviors
- Make algorithms interchangeable at runtime
- Eliminate conditional statements with polymorphism
- Create flexible and extensible payment systems
- Understand algorithm families and contexts

## Requirements
- PHP 8.1+
- Composer
- Understanding of design patterns
- Knowledge of payment processing concepts

## Features to Implement
1. Strategy Interface
   - Payment method abstraction
   - Unified payment interface
   - Transaction handling

2. Concrete Strategies
   - Credit card payment
   - PayPal payment
   - Bank transfer
   - Cryptocurrency payment

3. Context Class
   - Payment processor
   - Strategy switching
   - Transaction management

4. Advanced Features
   - Dynamic strategy selection
   - Payment validation
   - Fee calculation
   - Transaction logging

## Project Structure
```
challenge-28-strategy-pattern/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Strategy/
│   │   │   ├── PaymentStrategyInterface.php
│   │   │   ├── CreditCardPayment.php
│   │   │   ├── PayPalPayment.php
│   │   │   ├── BankTransferPayment.php
│   │   │   └── CryptoPayment.php
│   │   ├── Context/
│   │   │   ├── PaymentProcessor.php
│   │   │   └── TransactionManager.php
│   │   └── Service/
│   │       └── PaymentService.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── PaymentForm.jsx
│   │   │   └── PaymentMethods.jsx
│   │   └── services/
│   │       └── paymentService.js
│   ├── package.json
│   └── vite.config.js
└── README.md
```

## Setup Instructions
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Copy `.env.example` to `.env` and configure your settings
4. Start the development server with `php server.php`
5. Navigate to the `frontend-react` directory
6. Run `npm install` to install frontend dependencies
7. Run `npm run dev` to start the frontend development server

## API Endpoints
- `GET /api/payment/methods` - List available payment methods
- `POST /api/payment/process` - Process payment with selected strategy
- `GET /api/payment/strategies` - Get strategy details
- `POST /api/payment/validate` - Validate payment details
- `GET /api/transactions/history` - Get transaction history

## Evaluation Criteria
- Proper implementation of Strategy pattern interfaces
- Clean separation of payment algorithms
- Effective strategy switching mechanism
- Robust payment validation
- Clean and maintainable code structure
- Comprehensive test coverage

## Resources
- [Strategy Pattern](https://en.wikipedia.org/wiki/Strategy_pattern)
- [PHP Design Patterns](https://designpatternsphp.readthedocs.io/en/latest/Behavioral/Strategy/README.html)
- [Payment Processing Best Practices](https://stripe.com/docs/payments/payment-methods)