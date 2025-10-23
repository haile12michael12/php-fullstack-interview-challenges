# Challenge 13: GraphQL API with Subscriptions

## Overview
This challenge focuses on building a GraphQL API with real-time subscriptions using PHP and React. You'll learn how to implement GraphQL queries, mutations, and subscriptions for real-time data updates.

## Features
- GraphQL schema definition with types, queries, and mutations
- Real-time subscriptions using WebSockets
- GraphQL resolver implementation
- React frontend with Apollo Client
- Schema stitching and federation concepts

## Technologies Used
- PHP 8.1+
- GraphQL PHP library
- Ratchet for WebSocket support
- MySQL database
- React frontend with Apollo Client
- Vite for frontend build tooling

## Project Structure
```
challenge-13-graphql-subscriptions/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   ├── graphql/
│   │   ├── schema.graphql
│   │   ├── resolvers/
│   │   └── types/
│   ├── composer.json
│   └── server.php
└── frontend-react/
    ├── src/
    ├── package.json
    └── vite.config.js
```

## Setup Instructions
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Copy `.env.example` to `.env` and configure your database settings
4. Run database migrations
5. Start the GraphQL server: `php server.php`
6. Navigate to the `frontend-react` directory
7. Run `npm install` to install frontend dependencies
8. Run `npm run dev` to start the development server

## Learning Objectives
- Understanding GraphQL schema definition
- Implementing GraphQL resolvers
- Working with GraphQL queries and mutations
- Implementing real-time subscriptions
- Integrating GraphQL with React using Apollo Client
- Handling WebSocket connections for real-time updates