# Challenge 65: API Documentation

## Description
In this challenge, you'll create comprehensive API documentation using OpenAPI/Swagger specifications. Good documentation is crucial for API adoption and developer experience, making it easier for other developers to understand and use your APIs.

## Learning Objectives
- Understand API documentation standards
- Create OpenAPI 3.0 specifications
- Document endpoints, parameters, and responses
- Generate interactive documentation
- Implement documentation versioning
- Automate documentation generation

## Requirements
Create API documentation with the following features:

1. **OpenAPI Specification**:
   - Complete API specification in YAML/JSON format
   - Endpoint definitions with HTTP methods
   - Request/response schemas
   - Parameter definitions
   - Authentication schemes
   - Example requests and responses

2. **Documentation Generation**:
   - Interactive documentation UI (Swagger UI or Redoc)
   - Code samples in multiple languages
   - Schema validation
   - Export capabilities (PDF, HTML)
   - Search functionality

3. **Content Organization**:
   - Logical grouping of endpoints
   - Clear descriptions and examples
   - Error response documentation
   - Authentication guides
   - Rate limiting information
   - Best practices and usage guidelines

4. **Automation Features**:
   - Documentation generation from code annotations
   - Schema validation and linting
   - Version management
   - Integration with CI/CD pipelines
   - Real-time documentation updates

## Features to Implement
- [ ] OpenAPI 3.0 specification file
- [ ] Endpoint documentation with examples
- [ ] Request/response schema definitions
- [ ] Authentication documentation
- [ ] Error response documentation
- [ ] Interactive documentation UI
- [ ] Code samples in multiple languages
- [ ] Documentation versioning
- [ ] Automated generation from annotations
- [ ] Schema validation

## Project Structure
```
backend-php/
├── src/
│   ├── Api/
│   │   ├── Controllers/
│   │   │   ├── ApiController.php
│   │   │   ├── UserController.php
│   │   │   └── PostController.php
│   │   └── Documentation/
│   │       ├── OpenApiGenerator.php
│   │       ├── annotations.php
│   │       └── swagger.php
│   ├── Http/
│   │   └── Request.php
│   └── Models/
├── public/
│   ├── index.php
│   └── docs/
│       ├── swagger-ui/
│       ├── openapi.yaml
│       └── index.html
├── config/
│   └── documentation.php
└── vendor/

frontend-react/
├── src/
│   ├── components/
│   ├── App.jsx
│   └── main.jsx
├── public/
└── package.json
```

## Setup Instructions
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Configure your web server to point to the `public` directory
4. Start the development server with `php -S localhost:8000 -t public`
5. Navigate to the `frontend-react` directory
6. Run `npm install` to install dependencies
7. Start the development server with `npm run dev`

## Example OpenAPI Specification
```yaml
openapi: 3.0.0
info:
  title: User Management API
  description: API for managing users and posts
  version: 1.0.0
servers:
  - url: https://api.example.com/v1
    description: Production server
paths:
  /users:
    get:
      summary: List all users
      description: Returns a list of all users
      responses:
        '200':
          description: A list of users
          content:
            application/json:
              schema:
                type: array
                items:
                  $ref: '#/components/schemas/User'
    post:
      summary: Create a new user
      description: Creates a new user account
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/CreateUserRequest'
      responses:
        '201':
          description: User created successfully
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/User'
```

## Documentation Endpoints
- `GET /docs` - Interactive API documentation
- `GET /docs/openapi.yaml` - OpenAPI specification file
- `GET /docs/swagger.json` - Swagger JSON format
- `GET /docs/redoc` - Redoc documentation UI

## Evaluation Criteria
- [ ] OpenAPI specification is complete and valid
- [ ] All endpoints are documented with examples
- [ ] Request/response schemas are defined
- [ ] Authentication is properly documented
- [ ] Error responses are documented
- [ ] Interactive documentation UI works
- [ ] Code samples are provided
- [ ] Documentation is organized logically
- [ ] Schema validation passes
- [ ] Code is well-organized and documented
- [ ] Tests cover documentation functionality

## Resources
- [OpenAPI Specification](https://swagger.io/specification/)
- [Swagger UI](https://swagger.io/tools/swagger-ui/)
- [Redoc](https://github.com/Redocly/redoc)
- [API Documentation Best Practices](https://swagger.io/resources/articles/best-practices-in-api-design/)