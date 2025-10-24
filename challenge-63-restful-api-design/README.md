# Challenge 63: RESTful API Design

## Description
In this challenge, you'll design and implement a RESTful API following best practices for resource modeling, HTTP methods, status codes, and error handling. This is essential for building scalable and maintainable web services.

## Learning Objectives
- Understand REST architectural principles
- Design resource-oriented APIs
- Implement proper HTTP methods and status codes
- Create consistent API responses
- Handle authentication and authorization
- Document APIs effectively

## Requirements
Create a RESTful API with the following features:

1. **Resource Design**:
   - Proper noun-based resource naming
   - Hierarchical resource relationships
   - Consistent URI structure
   - Plural nouns for collections
   - Predictable resource locations

2. **HTTP Methods**:
   - GET for retrieving resources
   - POST for creating resources
   - PUT for updating entire resources
   - PATCH for partial updates
   - DELETE for removing resources
   - Proper idempotency handling

3. **Response Handling**:
   - Standard HTTP status codes
   - Consistent JSON response format
   - Error response structure
   - Pagination for collections
   - Filtering, sorting, and searching

4. **API Security**:
   - Authentication with API keys or JWT
   - Rate limiting
   - Input validation
   - Secure headers
   - CORS handling

## Features to Implement
- [ ] RESTful resource endpoints
- [ ] Proper HTTP methods implementation
- [ ] Standard status codes (200, 201, 404, etc.)
- [ ] JSON response format consistency
- [ ] Error handling with descriptive messages
- [ ] Pagination for large datasets
- [ ] Filtering and sorting capabilities
- [ ] Authentication and authorization
- [ ] Rate limiting
- [ ] API documentation

## Project Structure
```
backend-php/
├── src/
│   ├── Api/
│   │   ├── Controllers/
│   │   │   ├── ApiController.php
│   │   │   ├── UserController.php
│   │   │   ├── PostController.php
│   │   │   └── CommentController.php
│   │   ├── Resources/
│   │   │   ├── UserResource.php
│   │   │   ├── PostResource.php
│   │   │   └── CommentResource.php
│   │   ├── Transformers/
│   │   │   └── Transformer.php
│   │   └── Middleware/
│   │       ├── AuthMiddleware.php
│   │       └── RateLimitMiddleware.php
│   ├── Http/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── StatusCode.php
│   └── Models/
├── public/
│   └── index.php
├── config/
│   └── api.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── client.js
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

## API Endpoints
```
# Users
GET    /api/users           # List users
POST   /api/users           # Create user
GET    /api/users/{id}      # Get user
PUT    /api/users/{id}      # Update user
PATCH  /api/users/{id}      # Partially update user
DELETE /api/users/{id}      # Delete user

# Posts
GET    /api/posts           # List posts
POST   /api/posts           # Create post
GET    /api/posts/{id}      # Get post
PUT    /api/posts/{id}      # Update post
PATCH  /api/posts/{id}      # Partially update post
DELETE /api/posts/{id}      # Delete post

# Comments
GET    /api/posts/{postId}/comments  # List comments for post
POST   /api/posts/{postId}/comments  # Create comment
GET    /api/comments/{id}            # Get comment
PUT    /api/comments/{id}            # Update comment
DELETE /api/comments/{id}            # Delete comment
```

## Example Response Format
```json
{
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2023-01-01T12:00:00Z"
  },
  "links": {
    "self": "/api/users/1",
    "posts": "/api/users/1/posts"
  }
}
```

## Error Response Format
```json
{
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "The given data was invalid.",
    "details": {
      "email": ["The email field is required."]
    }
  }
}
```

## Evaluation Criteria
- [ ] API follows RESTful principles
- [ ] Proper HTTP methods are used
- [ ] Status codes are appropriate
- [ ] Response format is consistent
- [ ] Error handling is comprehensive
- [ ] Authentication is implemented
- [ ] Rate limiting protects the API
- [ ] Documentation is clear and complete
- [ ] Code is well-organized and documented
- [ ] Tests cover API endpoints

## Resources
- [REST API Design](https://restfulapi.net/)
- [HTTP Status Codes](https://httpstatuses.com/)
- [JSON:API Specification](https://jsonapi.org/)
- [RESTful Web APIs Book](https://www.amazon.com/RESTful-Web-APIs-Leonard-Richardson/dp/1449358063)