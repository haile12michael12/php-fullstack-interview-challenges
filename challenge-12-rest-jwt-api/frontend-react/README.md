# Challenge 12: REST API with JWT Authentication - Frontend

## Overview
This is the frontend React application for Challenge 12, which demonstrates JWT authentication with a PHP backend.

## Features
- User registration and login forms
- JWT token storage in localStorage
- Protected routes simulation
- Responsive design

## Technologies Used
- React 18
- Vite for fast development
- Axios for HTTP requests
- CSS Modules for styling

## Setup Instructions
1. Navigate to the frontend directory: `cd frontend-react`
2. Install dependencies: `npm install`
3. Start the development server: `npm run dev`
4. Open your browser to `http://localhost:3000`

## Project Structure
```
frontend-react/
├── src/
│   ├── App.css
│   ├── App.jsx
│   ├── index.css
│   └── main.jsx
├── index.html
├── package.json
├── vite.config.js
└── eslint.config.js
```

## API Integration
The frontend is configured to proxy API requests to `http://localhost:8000` where the PHP backend should be running.

## Development
- Run `npm run dev` to start the development server
- Run `npm run build` to create a production build
- Run `npm run preview` to preview the production build

## Learning Objectives
- Understanding JWT authentication flow in a full-stack application
- Implementing authentication state management in React
- Working with localStorage for token persistence
- Building responsive forms with React
- Using Vite for modern frontend development