# React Frontend Template

This is a React frontend template for PHP fullstack challenges.

## Features

- React 18 with hooks
- Vite for fast development
- ESLint for code quality
- Testing with Vitest and React Testing Library
- CSS modules for styling
- Axios for HTTP requests

## Requirements

- Node.js 16 or higher
- npm or yarn

## Setup

1. Navigate to the `frontend-react` directory
2. Install dependencies:
   ```bash
   npm install
   ```
3. Start the development server:
   ```bash
   npm run dev
   ```

## Available Scripts

- `npm run dev` - Start development server
- `npm run build` - Build for production
- `npm run lint` - Run ESLint
- `npm run preview` - Preview production build
- `npm run test` - Run tests

## Directory Structure

```
frontend-react/
├── public/           # Static assets
├── src/              # Source code
│   ├── components/   # React components
│   ├── services/     # API services
│   ├── App.css       # App styles
│   ├── App.jsx       # Main App component
│   ├── index.css     # Global styles
│   └── main.jsx      # Entry point
├── index.html        # HTML template
├── package.json      # NPM configuration
├── vite.config.js    # Vite configuration
└── README.md         # This file
```

## Development

The frontend is configured to proxy API requests to the backend server running on `http://localhost:8000`. Make sure the backend is running when developing.

## Building for Production

To create a production build:
```bash
npm run build
```

The build output will be in the `dist` directory.

## Testing

To run tests:
```bash
npm run test
```

## Contributing

This template is part of the PHP Fullstack Challenges project. For issues or improvements, please open a pull request.