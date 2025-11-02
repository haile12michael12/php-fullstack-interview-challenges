# Challenge 50: Contract Testing

## Description
In this challenge, you'll implement consumer-driven contract testing for APIs. Contract testing ensures that services can communicate with each other correctly by defining and verifying contracts between consumers and providers. You'll learn to create contracts, generate pact files, and verify that services adhere to their agreed-upon interfaces.

## Learning Objectives
- Understand consumer-driven contract testing principles
- Implement contract testing between services
- Create and manage pact files
- Verify provider compliance with contracts
- Handle contract versioning and evolution
- Integrate contract testing into CI/CD pipelines

## Requirements
Create a contract testing system with the following features:

1. **Contract Definition**:
   - Define contracts between consumer and provider services
   - Specify request/response formats and expectations
   - Handle different HTTP methods and status codes
   - Support request/response headers and body validation

2. **Pact File Generation**:
   - Generate pact files from consumer tests
   - Store pact files in a central repository
   - Version pact files appropriately
   - Handle contract evolution and breaking changes

3. **Provider Verification**:
   - Verify provider implementations against pact files
   - Run provider verification tests
   - Handle verification results and reporting
   - Integrate with existing test frameworks

4. **Contract Management**:
   - Manage contract lifecycle and versioning
   - Handle contract changes and deprecations
   - Support canary releases and feature flags
   - Implement contract testing best practices

## Features to Implement
- [ ] Consumer contract definition and testing
- [ ] Pact file generation and storage
- [ ] Provider verification against contracts
- [ ] Contract versioning and evolution
- [ ] Breaking change detection
- [ ] Integration with CI/CD pipelines
- [ ] Contract repository management
- [ ] Reporting and monitoring

## Project Structure
```
challenge-50-contract-testing/
├── backend-php/
│   ├── src/
│   │   ├── Consumer/
│   │   │   ├── ContractTester.php
│   │   │   ├── PactGenerator.php
│   │   │   └── HttpClient.php
│   │   ├── Provider/
│   │   │   ├── ContractVerifier.php
│   │   │   ├── PactLoader.php
│   │   │   └── ServerMock.php
│   │   ├── Contract/
│   │   │   ├── Contract.php
│   │   │   ├── Interaction.php
│   │   │   ├── Request.php
│   │   │   └── Response.php
│   │   └── Repository/
│   │       ├── PactRepository.php
│   │       └── FileStorage.php
│   ├── config/
│   ├── public/
│   │   └── index.php
│   ├── tests/
│   │   ├── Consumer/
│   │   │   └── ContractTest.php
│   │   └── Provider/
│   │       └── VerificationTest.php
│   ├── composer.json
│   └── Dockerfile
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── ContractDashboard.jsx
│   │   │   ├── PactViewer.jsx
│   │   │   └── VerificationResults.jsx
│   │   ├── services/
│   │   │   └── contractService.js
│   │   ├── App.jsx
│   │   └── main.jsx
│   ├── package.json
│   ├── vite.config.js
│   └── Dockerfile
├── docker-compose.yml
└── README.md
```

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 16+
- Docker (optional)

### Backend Setup
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Start the development server with `php public/index.php`

### Frontend Setup
1. Navigate to the `frontend-react` directory
2. Run `npm install` to install dependencies
3. Run `npm run dev` to start the development server

### Docker Setup
1. From the challenge root directory, run `docker-compose up -d`
2. Access the application at `http://localhost:3000`

## API Endpoints
- `POST /api/contracts` - Create a new contract
- `GET /api/contracts` - List all contracts
- `GET /api/contracts/{id}` - Get contract details
- `POST /api/contracts/{id}/verify` - Verify provider against contract
- `GET /api/pacts` - List all pact files
- `POST /api/pacts/generate` - Generate pact from consumer tests

## Evaluation Criteria
- [ ] Contract definition and testing implementation
- [ ] Pact file generation and management
- [ ] Provider verification functionality
- [ ] Contract versioning and evolution handling
- [ ] Integration with CI/CD workflows
- [ ] Code quality and documentation
- [ ] Test coverage for contract testing components

## Resources
- [Pact Documentation](https://docs.pact.io/)
- [Consumer-Driven Contracts](https://martinfowler.com/articles/consumerDrivenContracts.html)
- [Contract Testing Best Practices](https://www.baeldung.com/cs/contract-testing)
- [Microservices Testing Strategies](https://martinfowler.com/articles/microservice-testing/)