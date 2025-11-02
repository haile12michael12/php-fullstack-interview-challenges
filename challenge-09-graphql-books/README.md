# Challenge 09: GraphQL Books API

## Description
This challenge focuses on building a GraphQL API for a book management system. You'll learn how to design GraphQL schemas, implement resolvers, handle complex queries and mutations, and create a flexible API that allows clients to request exactly the data they need. The challenge covers GraphQL fundamentals, performance optimization, and integration with existing data sources.

## Learning Objectives
- Design GraphQL schemas and type definitions
- Implement resolvers for queries and mutations
- Handle complex nested queries and relationships
- Optimize GraphQL performance with batching and caching
- Implement authentication and authorization
- Handle errors and validation in GraphQL
- Create efficient data loading strategies

## Requirements
- PHP 8.1+
- Composer
- Node.js 16+
- Understanding of GraphQL concepts
- Knowledge of REST APIs (for comparison)
- Docker (optional, for containerized deployment)

## Features to Implement
1. **GraphQL Schema**
   - Type definitions for books, authors, genres
   - Query operations for fetching data
   - Mutation operations for data modification
   - Input types and validation
   - Custom scalars and enums
   
2. **Data Operations**
   - Fetch books with filtering and sorting
   - Retrieve author and genre information
   - Create, update, and delete books
   - Search functionality across multiple fields
   - Pagination and cursor-based navigation
   
3. **Performance Optimization**
   - DataLoader for batching and caching
   - Query complexity analysis
   - Field-level caching strategies
   - Database query optimization
   - N+1 problem prevention
   
4. **Security and Validation**
   - Authentication and authorization
   - Input validation and sanitization
   - Rate limiting and abuse prevention
   - Error handling and logging
   - Schema introspection controls

## Project Structure
```
challenge-09-graphql-books/
├── backend-php/
│   ├── src/
│   │   ├── GraphQL/
│   │   │   ├── Schema/
│   │   │   │   ├── BookType.php
│   │   │   │   ├── AuthorType.php
│   │   │   │   ├── GenreType.php
│   │   │   │   └── MutationType.php
│   │   │   ├── Resolver/
│   │   │   │   ├── BookResolver.php
│   │   │   │   ├── AuthorResolver.php
│   │   │   │   └── QueryResolver.php
│   │   │   ├── DataLoader/
│   │   │   │   └── BookDataLoader.php
│   │   │   └── GraphQLService.php
│   │   ├── Model/
│   │   │   ├── Book.php
│   │   │   ├── Author.php
│   │   │   └── Genre.php
│   │   └── Exception/
│   │       ├── GraphQLException.php
│   │       └── ValidationException.php
│   ├── public/
│   │   └── index.php
│   ├── config/
│   ├── tests/
│   ├── composer.json
│   └── Dockerfile
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── BookList.jsx
│   │   │   ├── BookDetails.jsx
│   │   │   ├── AuthorProfile.jsx
│   │   │   └── SearchInterface.jsx
│   │   ├── services/
│   │   │   └── graphqlService.js
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
3. Start the GraphQL server:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the `frontend-react` directory
2. Run `npm install` to install dependencies
3. Run `npm run dev` to start the development server

### Docker Setup
1. From the challenge root directory, run:
   ```bash
   docker-compose up -d
   ```
2. Access the application at `http://localhost:3000`
3. GraphQL Playground: `http://localhost:8080/graphql`

## GraphQL Schema Examples

### Queries
```graphql
# Get all books
query {
  books {
    id
    title
    isbn
    publishedDate
    authors {
      name
      biography
    }
    genres {
      name
    }
  }
}

# Get book by ID
query {
  book(id: "1") {
    title
    description
    pageCount
    authors {
      name
    }
  }
}

# Search books
query {
  searchBooks(query: "PHP", limit: 10) {
    id
    title
    authors {
      name
    }
  }
}
```

### Mutations
```graphql
# Create a new book
mutation {
  createBook(input: {
    title: "Learning GraphQL"
    isbn: "978-1234567890"
    description: "A comprehensive guide to GraphQL"
    pageCount: 350
    publishedDate: "2023-01-15"
    authorIds: ["1", "2"]
    genreIds: ["3"]
  }) {
    id
    title
    authors {
      name
    }
  }
}

# Update a book
mutation {
  updateBook(id: "1", input: {
    title: "Updated Book Title"
    pageCount: 400
  }) {
    id
    title
    pageCount
  }
}
```

## Evaluation Criteria
- [ ] Well-designed GraphQL schema
- [ ] Efficient resolver implementations
- [ ] Proper error handling and validation
- [ ] Performance optimization techniques
- [ ] Security and authentication implementation
- [ ] Code quality and documentation
- [ ] Test coverage for GraphQL operations

## Resources
- [GraphQL Documentation](https://graphql.org/learn/)
- [GraphQL PHP Reference](https://webonyx.github.io/graphql-php/)
- [GraphQL Best Practices](https://graphql.org/learn/best-practices/)
- [DataLoader Pattern](https://github.com/graphql/dataloader)