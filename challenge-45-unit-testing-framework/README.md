# Challenge 45: Unit Testing Framework

## Description
This challenge focuses on building a simple unit testing framework from scratch. You'll learn the internals of testing frameworks and how to create your own test runner.

## Learning Objectives
- Implement a unit testing framework from scratch
- Understand test discovery and execution
- Create assertion mechanisms
- Implement test fixtures and setup/teardown
- Handle test isolation and dependencies
- Generate test reports and metrics

## Requirements
- PHP 8.1+
- Composer
- Understanding of unit testing concepts
- Knowledge of testing frameworks

## Features to Implement
1. Test Runner Core
   - Test discovery
   - Test execution
   - Assertion handling
   - Test result collection
   - Report generation

2. Assertion Library
   - Equality assertions
   - Boolean assertions
   - Exception assertions
   - Custom assertions
   - Assertion failure handling

3. Test Organization
   - Test suites
   - Test groups
   - Test dependencies
   - Test filtering
   - Test ordering

4. Advanced Features
   - Mock object support
   - Code coverage integration
   - Parallel test execution
   - Test data providers
   - Continuous integration support

## Project Structure
```
challenge-45-unit-testing-framework/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Framework/
│   │   │   ├── TestRunner.php
│   │   │   ├── TestSuite.php
│   │   │   ├── TestCase.php
│   │   │   └── TestResult.php
│   │   ├── Assert/
│   │   │   ├── Assert.php
│   │   │   ├── AssertionException.php
│   │   │   └── ComparisonFailure.php
│   │   └── Service/
│   │       └── TestingService.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── TestRunner.jsx
│   │   │   └── TestResults.jsx
│   │   └── services/
│   │       └── testService.js
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
- `GET /api/tests/discover` - Discover available tests
- `POST /api/tests/run` - Run selected tests
- `GET /api/tests/results` - Get test results
- `POST /api/tests/assert` - Execute assertions
- `GET /api/tests/report` - Generate test report
- `POST /api/tests/configure` - Configure test settings

## Evaluation Criteria
- Proper implementation of testing framework core concepts
- Effective test discovery and execution
- Robust assertion mechanisms
- Clean test organization
- Comprehensive reporting features
- Comprehensive test coverage

## Resources
- [Unit Testing](https://en.wikipedia.org/wiki/Unit_testing)
- [Test-Driven Development](https://en.wikipedia.org/wiki/Test-driven_development)
- [PHPUnit Documentation](https://phpunit.readthedocs.io/)