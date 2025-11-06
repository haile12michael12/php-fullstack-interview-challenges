# Image Processing Application

A full-stack image processing application with PHP backend and React frontend.

## Project Structure

```
challenge-07-image-processing/
├── backend-php/
│   ├── src/
│   │   ├── Core/
│   │   │   ├── Contracts/
│   │   │   │   ├── ImageProcessorInterface.php
│   │   │   │   ├── OptimizerInterface.php
│   │   │   │   ├── FilterInterface.php
│   │   │   │   └── BatchProcessorInterface.php
│   │   │   ├── Traits/
│   │   │   │   ├── FileValidationTrait.php
│   │   │   │   └── ResponseTrait.php
│   │   │   └── Helpers/
│   │   │       ├── ImageUtils.php
│   │   │       └── Logger.php
│   │   │
│   │   ├── Image/
│   │   │   ├── ImageProcessor.php
│   │   │   ├── ImageFilter.php
│   │   │   ├── ImageOptimizer.php
│   │   │   └── ImageTransformer.php
│   │   │
│   │   ├── Service/
│   │   │   ├── BatchProcessor.php
│   │   │   ├── ImageService.php
│   │   │   └── PresetManager.php
│   │   │
│   │   ├── Controller/
│   │   │   ├── ImageController.php
│   │   │   └── BatchController.php
│   │   │
│   │   ├── Exception/
│   │   │   ├── ImageException.php
│   │   │   ├── ProcessingException.php
│   │   │   └── ValidationException.php
│   │   │
│   │   ├── Config/
│   │   │   ├── app.php
│   │   │   ├── paths.php
│   │   │   └── presets.php
│   │   │
│   │   └── bootstrap.php
│   │
│   ├── public/
│   │   ├── index.php
│   │   ├── uploads/
│   │   ├── optimized/
│   │   └── temp/
│   │
│   ├── tests/
│   │   ├── ImageProcessorTest.php
│   │   ├── ImageFilterTest.php
│   │   └── BatchProcessorTest.php
│   │
│   ├── composer.json
│   ├── phpunit.xml
│   ├── Dockerfile
│   ├── .env.example
│   └── README.md
│
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── ImageEditor.jsx
│   │   │   ├── Canvas.jsx
│   │   │   ├── Toolbar.jsx
│   │   │   ├── PreviewPanel.jsx
│   │   │   ├── BatchUploader.jsx
│   │   │   └── WatermarkTool.jsx
│   │   │
│   │   ├── pages/
│   │   │   ├── index.jsx
│   │   │   ├── Batch.jsx
│   │   │   └── Optimize.jsx
│   │   │
│   │   ├── hooks/
│   │   │   ├── useImageEditor.js
│   │   │   └── useBatchProcessing.js
│   │   │
│   │   ├── context/
│   │   │   └── EditorContext.jsx
│   │   │
│   │   ├── services/
│   │   │   └── imageService.js
│   │   │
│   │   ├── utils/
│   │   │   ├── fileUtils.js
│   │   │   ├── filters.js
│   │   │   └── constants.js
│   │   │
│   │   ├── App.jsx
│   │   └── main.jsx
│   │
│   ├── public/
│   │   ├── favicon.ico
│   │   └── logo.png
│   │
│   ├── package.json
│   ├── vite.config.js
│   ├── Dockerfile
│   └── README.md
│
├── docker-compose.yml
├── .gitignore
└── README.md
```

## Features

- Image upload and processing
- Batch processing capabilities
- Filter application (brightness, contrast, saturation, etc.)
- Image optimization and compression
- Preset management for common operations
- Responsive React frontend with real-time preview
- RESTful API backend with PHP
- Docker containerization for easy deployment

## Getting Started

### Prerequisites

- Docker and Docker Compose
- PHP 8.1+
- Node.js 16+
- Composer

### Installation

1. Clone the repository
2. Navigate to the project directory
3. Run `docker-compose up` to start the application

### Backend API

The backend API is available at `http://localhost:8000`

### Frontend Application

The frontend application is available at `http://localhost:3000`

## Development

### Backend Development

1. Navigate to the `backend-php` directory
2. Install dependencies: `composer install`
3. Start the development server

### Frontend Development

1. Navigate to the `frontend-react` directory
2. Install dependencies: `npm install`
3. Start the development server: `npm run dev`

## Testing

### Backend Testing

Run PHPUnit tests:
```bash
cd backend-php
./vendor/bin/phpunit
```

### Frontend Testing

Run frontend tests:
```bash
cd frontend-react
npm test
```

## Deployment

The application can be deployed using Docker Compose:

```bash
docker-compose up -d
```

## License

This project is licensed under the MIT License.