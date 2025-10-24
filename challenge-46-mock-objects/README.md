# Challenge 46: Mock Objects

## Description
This challenge focuses on creating a mocking library for isolating dependencies in tests. You'll learn to create mock objects that simulate real objects for testing purposes.

## Learning Objectives
- Implement a mocking library from scratch
- Create mock objects with expectations
- Handle method call verification
- Implement stubbing and faking
- Understand test isolation principles
- Build flexible mocking APIs

## Requirements
- PHP 8.1+
- Composer
- Understanding of unit testing concepts
- Knowledge of mocking patterns

## Features to Implement
1. Mock Object Core
   - Object creation
   - Method interception
   - Call tracking
   - Return value configuration
   - Exception throwing

2. Expectation System
   - Call count expectations
   - Argument matching
   - Call order verification
   - Custom matchers
   - Flexible constraints

3. Stubbing Features
   - Return value stubbing
   - Callback-based responses
   - Property stubbing
   - Magic method handling
   - Partial mocking

4. Advanced Features
   - Mock verification
   - Mock reset and cleanup
   - Mock serialization
   - Integration with testing frameworks
   - Performance optimization

## Project Structure
```
challenge-46-mock-objects/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Mock/
│   │   │   ├── MockBuilder.php
│   │   │   ├── MockObject.php
│   │   │   ├── MethodExpectation.php
│   │   │   └── CallVerifier.php
│   │   ├── Stub/
│   │   │   ├── ReturnValueStub.php
│   │   │   ├── ExceptionStub.php
│   │   │   ├── CallbackStub.php
│   │   │   └── PropertyStub.php
│   │   └── Service/
│   │       └── MockingService.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── MockBuilder.jsx
│   │   │   └── MockInspector.jsx
│   │   └── services/
│   │       └── mockService.js
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
- `POST /api/mocks/create` - Create new mock object
- `POST /api/mocks/expect` - Set method expectations
- `POST /api/mocks/stub` - Configure return values
- `POST /api/mocks/verify` - Verify mock expectations
- `GET /api/mocks/calls` - Get method call history
- `POST /api/mocks/reset` - Reset mock object

## Evaluation Criteria
- Proper implementation of mocking library core concepts
- Effective method interception and tracking
- Robust expectation system
- Flexible stubbing capabilities
- Clean integration with testing workflows
- Comprehensive test coverage

## Resources
- [Mock Object](https://en.wikipedia.org/wiki/Mock_object)
- [Test Double Patterns](https://martinfowler.com/articles/mocksArentStubs.html)
- [PHPUnit Mock Objects](https://phpunit.readthedocs.io/en/9.5/test-doubles.html)