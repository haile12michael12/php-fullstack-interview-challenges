# Frontend React - Advanced Error Handling

This is the frontend component of the Advanced Error Handling challenge, built with React and Vite.

## Features

- Comprehensive error handling with error boundaries
- API error handling with retry mechanisms
- Context-based error state management
- Notification system for user feedback
- Logging utilities with correlation ID tracking
- Fallback views for degraded experiences
- Maintenance mode handling

## Project Structure

```
src/
├── components/     # Reusable UI components
├── pages/          # Page components
├── services/       # Business logic and API clients
├── context/        # React context providers
├── hooks/          # Custom React hooks
├── utils/          # Utility functions
├── App.jsx         # Main application component
└── main.jsx        # Application entry point
```

## Getting Started

### Prerequisites

- Node.js 16+
- npm or yarn

### Installation

```bash
npm install
```

### Development

```bash
npm run dev
```

Runs the app in development mode on http://localhost:3000

### Build

```bash
npm run build
```

Builds the app for production to the `dist` folder.

### Preview

```bash
npm run preview
```

Preview the production build locally.

## Error Handling Components

### Error Boundary
Catches JavaScript errors anywhere in the child component tree and displays a fallback UI.

### Error Service
Centralized error handling service that processes different types of errors and notifies listeners.

### Retry Handler
Implements exponential backoff retry logic for failed API requests.

### Logger
Structured logging utility with different log levels and correlation ID tracking.

### Context Providers
Manage global error state and provide error handling functions to components.

## Environment Variables

Create a `.env` file in the root directory:

```
VITE_API_BASE_URL=http://localhost:8000/api
VITE_LOG_LEVEL=debug
```

## Docker

Build and run with Docker:

```bash
docker build -t frontend-react .
docker run -p 3000:80 frontend-react
```

## Testing

Run tests with Vitest:

```bash
npm run test
```

## Linting

Check for linting issues:

```bash
npm run lint
```

Fix linting issues automatically:

```bash
npm run lint:fix
```