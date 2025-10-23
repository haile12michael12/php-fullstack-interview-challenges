# Challenge 14: WebSocket Real-time Dashboard - Frontend

## Overview
This is the frontend React application for Challenge 14, which demonstrates real-time data visualization using WebSockets.

## Features
- Real-time WebSocket connection
- Live data visualization with Chart.js
- Dashboard with multiple metrics panels
- Connection status indicators
- Auto-reconnection logic

## Technologies Used
- React 18
- Vite for fast development
- Chart.js for data visualization
- WebSocket API for real-time communication

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
│   │   ├── Dashboard.jsx
│   │   ├── MetricsPanel.jsx
│   │   ├── ConnectionStatus.jsx
│   │   └── Chart.jsx
│   ├── hooks/
│   │   └── useWebSocket.js
│   ├── App.jsx
│   ├── index.css
│   └── main.jsx
├── index.html
├── package.json
├── vite.config.js
└── eslint.config.js
```

## WebSocket Integration
The frontend connects to the WebSocket server at `ws://localhost:8080/dashboard` for real-time data streaming.

## Development
- Run `npm run dev` to start the development server
- Run `npm run build` to create a production build
- Run `npm run preview` to preview the production build

## Learning Objectives
- Understanding WebSocket client-side implementation
- Building real-time UI updates in React
- Working with Chart.js for live data visualization
- Implementing connection management and error handling
- Creating responsive dashboard layouts