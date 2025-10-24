# Challenge 64: GraphQL Server

## Description
In this challenge, you'll build a GraphQL server that provides a flexible and efficient alternative to REST APIs. You'll learn how to define schemas, implement resolvers, and handle complex queries with nested relationships.

## Learning Objectives
- Understand GraphQL fundamentals and schema definition
- Implement GraphQL schemas and types
- Create resolvers for fetching data
- Handle mutations for data modification
- Implement query optimization and batching
- Add authentication and authorization

## Requirements
Create a GraphQL server with the following features:

1. **Schema Design**:
   - Object types with fields and relationships
   - Query and mutation root types
   - Input types for mutations
   - Enums and interfaces
   - Unions for polymorphic types
   - Custom scalars

2. **Resolver Implementation**:
   - Field resolvers for data fetching
   - Nested field resolution
   - Argument handling
   - Context passing
   - Error handling in resolvers
   - Async resolver support

3. **Performance Optimization**:
   - DataLoader for batching and caching
   - Query complexity analysis
   - Field-level caching
   - Database query optimization
   - Pagination implementation

4. **Security Features**:
   - Authentication integration
   - Authorization checks
   - Query depth limiting
   - Query cost analysis
   - Rate limiting
   - Input validation

## Features to Implement
- [ ] GraphQL schema definition
- [ ] Object types with relationships
- [ ] Query resolvers for data fetching
- [ ] Mutation resolvers for data modification
- [ ] DataLoader for batching and caching
- [ ] Authentication and authorization
- [ ] Query complexity and depth limiting
- [ ] Error handling and validation
- [ ] Pagination support
- [ ] Custom scalars implementation

## Project Structure
```
backend-php/
├── src/
│   ├── GraphQL/
│   │   ├── Schema/
│   │   │   ├── types.php
│   │   │   ├── queries.php
│   │   │   ├── mutations.php
│   │   │   └── schema.php
│   │   ├── Resolvers/
│   │   │   ├── UserResolver.php
│   │   │   ├── PostResolver.php
│   │   │   └── CommentResolver.php
│   │   ├── Directives/
│   │   │   └── AuthDirective.php
│   │   ├── Loaders/
│   │   │   └── DataLoader.php
│   │   └── Middleware/
│   │       ├── AuthMiddleware.php
│   │       └── ValidationMiddleware.php
│   ├── Http/
│   │   ├── Request.php
│   │   └── Response.php
│   └── Models/
├── public/
│   └── index.php
├── config/
│   └── graphql.php
└── vendor/

frontend-react/
├── src/
│   ├── graphql/
│   │   ├── queries/
│   │   ├── mutations/
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

## Example Schema
```graphql
type User {
  id: ID!
  name: String!
  email: String!
  posts: [Post!]!
  createdAt: String!
}

type Post {
  id: ID!
  title: String!
  content: String!
  author: User!
  comments: [Comment!]!
  createdAt: String!
}

type Comment {
  id: ID!
  content: String!
  author: User!
  post: Post!
  createdAt: String!
}

type Query {
  users: [User!]!
  user(id: ID!): User
  posts: [Post!]!
  post(id: ID!): Post
}

type Mutation {
  createUser(input: CreateUserInput!): User!
  createPost(input: CreatePostInput!): Post!
  createComment(input: CreateCommentInput!): Comment!
}

input CreateUserInput {
  name: String!
  email: String!
}

input CreatePostInput {
  title: String!
  content: String!
  authorId: ID!
}

input CreateCommentInput {
  content: String!
  authorId: ID!
  postId: ID!
}
```

## Example Query
```graphql
query {
  users {
    id
    name
    posts {
      id
      title
      comments {
        id
        content
        author {
          name
        }
      }
    }
  }
}
```

## Example Mutation
```graphql
mutation {
  createUser(input: {
    name: "John Doe"
    email: "john@example.com"
  }) {
    id
    name
    email
  }
}
```

## Evaluation Criteria
- [ ] GraphQL schema is properly defined
- [ ] Resolvers fetch data correctly
- [ ] Nested queries work with relationships
- [ ] Mutations modify data as expected
- [ ] DataLoader optimizes database queries
- [ ] Authentication and authorization work
- [ ] Error handling is comprehensive
- [ ] Query complexity is limited
- [ ] Code is well-organized and documented
- [ ] Tests cover GraphQL functionality

## Resources
- [GraphQL Official Documentation](https://graphql.org/)
- [GraphQL PHP Reference](https://webonyx.github.io/graphql-php/)
- [Apollo GraphQL](https://www.apollographql.com/)
- [GraphQL Best Practices](https://graphql.org/learn/best-practices/)