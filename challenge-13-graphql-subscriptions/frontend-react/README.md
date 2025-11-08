# Challenge 13: GraphQL API with Subscriptions - Frontend

## Overview
This is the frontend React application for Challenge 13, which demonstrates GraphQL API integration with real-time subscriptions.

## Features
- GraphQL query and mutation examples
- Real-time subscription implementation
- Apollo Client integration
- Responsive design

## Technologies Used
- React 18
- Vite for fast development
- Apollo Client for GraphQL
- GraphQL WebSocket link for subscriptions

## Setup Instructions
1. Navigate to the frontend directory: `cd frontend-react`
2. Install dependencies: `npm install`
3. Start the development server: `npm run dev`
4. Open your browser to `http://localhost:3000`

## Project Structure
```
frontend-react/
├── src/
│   ├── components/
│   │   ├── PostList.jsx
│   │   ├── PostForm.jsx
│   │   ├── CommentSection.jsx
│   │   └── RealTimeFeed.jsx
│   ├── services/
│   │   └── graphqlService.js
│   ├── App.jsx
│   ├── App.css
│   ├── index.css
│   └── main.jsx
├── index.html
├── package.json
├── vite.config.js
└── Dockerfile
```

## API Integration
The frontend is configured to connect to the GraphQL server at `ws://localhost:8081/graphql` for subscriptions and `http://localhost:8080/graphql` for queries and mutations.

## Development
- Run `npm run dev` to start the development server
- Run `npm run build` to create a production build
- Run `npm run preview` to preview the production build

## Learning Objectives
- Understanding GraphQL client-side integration
- Implementing real-time updates with subscriptions
- Working with Apollo Client for state management
- Building responsive UIs with React and GraphQL data