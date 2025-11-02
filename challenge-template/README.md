# Challenge Template

## Description
This is a template for creating new PHP challenges. Each challenge follows a consistent structure with backend PHP implementation, frontend React interface, Docker configuration, and comprehensive documentation.

## Learning Objectives
- Understand the challenge topic and its importance in modern PHP development
- Implement core features using best practices
- Build a full-stack application demonstrating the concept
- Apply security and performance considerations
- Create comprehensive tests

## Requirements
List the specific requirements for this challenge:
- Core features to implement
- Technologies to use
- Constraints or limitations
- Expected outcomes

## Features to Implement
- [ ] Feature 1
- [ ] Feature 2
- [ ] Feature 3
- [ ] Feature 4

## Project Structure
```
challenge-template/
├── backend-php/
│   ├── src/
│   │   └── [Challenge-specific directories and files]
│   ├── config/
│   ├── public/
│   │   └── index.php
│   ├── tests/
│   ├── composer.json
│   └── Dockerfile
├── frontend-react/
│   ├── src/
│   │   ├── components/
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
List the API endpoints for this challenge:
- `GET /api/[endpoint]` - Description
- `POST /api/[endpoint]` - Description

## Evaluation Criteria
- [ ] Implementation meets all requirements
- [ ] Code follows best practices and is well-organized
- [ ] Security considerations are addressed
- [ ] Performance is optimized
- [ ] Tests cover core functionality
- [ ] Documentation is clear and comprehensive

## Resources
- [Relevant Documentation](https://example.com)
- [Related Articles](https://example.com)
- [Best Practices Guide](https://example.com)