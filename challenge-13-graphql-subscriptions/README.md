# Challenge 13: GraphQL API with Subscriptions

## Description
This challenge focuses on building a GraphQL API with real-time subscriptions using PHP and React. You'll learn how to implement GraphQL queries, mutations, and subscriptions for real-time data updates. The challenge covers GraphQL fundamentals, real-time communication patterns, and creating responsive user interfaces that update in real-time.

## Learning Objectives
- Understanding GraphQL schema definition and type system
- Implementing GraphQL resolvers for queries and mutations
- Working with GraphQL queries and mutations for data manipulation
- Implementing real-time subscriptions using WebSockets
- Integrating GraphQL with React using Apollo Client
- Handling WebSocket connections for real-time updates
- Optimizing GraphQL performance with caching and batching
- Applying security best practices for GraphQL APIs

## Requirements
- PHP 8.1+
- Composer
- Node.js 16+
- GraphQL PHP library
- Ratchet for WebSocket support
- MySQL database
- Understanding of GraphQL concepts
- Knowledge of WebSocket communication
- Docker (optional, for containerized deployment)

## Features to Implement
1. **GraphQL Schema**
   - Type definitions for core entities
   - Query operations for data retrieval
   - Mutation operations for data modification
   - Subscription operations for real-time updates
   - Input types and validation schemas
   
2. **Real-time Subscriptions**
   - WebSocket server implementation
   - Subscription event publishing
   - Real-time data streaming
   - Connection management and authentication
   - Event filtering and filtering arguments
   
3. **Data Operations**
   - CRUD operations through GraphQL
   - Complex query resolution with relationships
   - Data validation and error handling
   - Performance optimization with DataLoader
   - Caching strategies for frequently accessed data
   
4. **Security and Performance**
   - Authentication and authorization for subscriptions
   - Rate limiting and abuse prevention
   - Query complexity analysis and limits
   - Secure WebSocket connection handling
   - Error handling and logging

## Project Structure
```
challenge-13-graphql-subscriptions/
├── backend-php/
│   ├── src/
│   │   ├── GraphQL/
│   │   │   ├── Schema/
│   │   │   │   ├── RootQuery.php
│   │   │   │   ├── RootMutation.php
│   │   │   │   ├── RootSubscription.php
│   │   │   │   └── TypeRegistry.php
│   │   │   ├── Resolver/
│   │   │   │   ├── QueryResolver.php
│   │   │   │   ├── MutationResolver.php
│   │   │   │   └── SubscriptionResolver.php
│   │   │   ├── Subscription/
│   │   │   │   ├── SubscriptionManager.php
│   │   │   │   └── EventPublisher.php
│   │   │   └── GraphQLService.php
│   │   ├── Model/
│   │   │   ├── User.php
│   │   │   ├── Post.php
│   │   │   └── Comment.php
│   │   └── Exception/
│   │       ├── GraphQLException.php
│   │       └── SubscriptionException.php
│   ├── public/
│   │   ├── index.php
│   │   └── websocket.php
│   ├── config/
│   ├── migrations/
│   ├── tests/
│   ├── composer.json
│   ├── Dockerfile
│   └── .env
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── PostList.jsx
│   │   │   ├── PostForm.jsx
│   │   │   ├── CommentSection.jsx
│   │   │   └── RealTimeFeed.jsx
│   │   ├── services/
│   │   │   └── graphqlService.js
│   │   ├── App.jsx
│   │   ├── App.css
│   │   ├── index.css
│   │   └── main.jsx
│   ├── index.html
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
- MySQL database
- Docker (optional)

### Backend Setup
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Copy `.env.example` to `.env` and configure your database settings
4. Start the GraphQL server:
   ```bash
   php public/index.php
   ```
5. Start the WebSocket server for subscriptions:
   ```bash
   php public/websocket.php
   ```

### Frontend Setup
1. Navigate to the `frontend-react` directory
2. Run `npm install` to install frontend dependencies
3. Run `npm run dev` to start the development server

### Docker Setup
1. From the challenge root directory, run:
   ```bash
   docker-compose up -d
   ```
2. Access the application at `http://localhost:3000`
3. GraphQL Playground: `http://localhost:8080/graphql`

## GraphQL Operations

### Queries
```graphql
# Get all posts
query {
  posts {
    id
    title
    content
    author {
      name
      email
    }
    createdAt
  }
}

# Get post by ID
query {
  post(id: "1") {
    title
    content
    comments {
      id
      content
      author {
        name
      }
      createdAt
    }
  }
}
```

### Mutations
```graphql
# Create a new post
mutation {
  createPost(input: {
    title: "New Post"
    content: "Post content here"
    authorId: "1"
  }) {
    id
    title
    content
    createdAt
  }
}

# Add a comment
mutation {
  addComment(input: {
    postId: "1"
    content: "Great post!"
    authorId: "2"
  }) {
    id
    content
    createdAt
  }
}
```

### Subscriptions
```graphql
# Subscribe to new posts
subscription {
  postAdded {
    id
    title
    content
    author {
      name
    }
    createdAt
  }
}

# Subscribe to new comments
subscription {
  commentAdded(postId: "1") {
    id
    content
    author {
      name
    }
    createdAt
  }
}
```

## Evaluation Criteria
- [ ] Well-designed GraphQL schema with proper types
- [ ] Efficient resolver implementations
- [ ] Working real-time subscriptions with WebSockets
- [ ] Proper error handling and validation
- [ ] Security implementation for GraphQL operations
- [ ] Performance optimization techniques
- [ ] Code quality and documentation
- [ ] Test coverage for GraphQL functionality

## Resources
- [GraphQL Documentation](https://graphql.org/learn/)
- [GraphQL PHP Reference](https://webonyx.github.io/graphql-php/)
- [Apollo Client Documentation](https://www.apollographql.com/docs/react/)
- [WebSocket Protocol](https://tools.ietf.org/html/rfc6455)
- [GraphQL Subscriptions](https://www.apollographql.com/docs/apollo-server/data/subscriptions/)