# Challenge 18: Generators and Iterators

This challenge demonstrates the power of PHP generators and iterators for efficient data processing.

## Project Structure

```
challenge-18-generators-iterators/
├── backend-php/
│   ├── config/
│   │   └── database.php
│   ├── data/
│   │   ├── large_dataset.csv
│   │   └── stream.txt
│   ├── public/
│   │   └── index.php
│   ├── src/
│   │   ├── Generator/
│   │   │   ├── CsvProcessor.php
│   │   │   ├── DatabaseIterator.php
│   │   │   ├── FileStreamer.php
│   │   │   ├── DataPipeline.php
│   │   │   └── AdvancedExamples.php
│   │   ├── Iterator/
│   │   │   ├── CustomCollection.php
│   │   │   ├── FilterIterator.php
│   │   │   ├── MapIterator.php
│   │   │   └── InfiniteSequence.php
│   │   └── Service/
│   │       └── DataProcessor.php
│   ├── tests/
│   │   ├── CsvProcessorTest.php
│   │   ├── IteratorTest.php
│   │   └── PipelineTest.php
│   ├── .env.example
│   ├── composer.json
│   ├── server.php
│   └── README.md
│
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── DataProcessor.jsx
│   │   │   ├── PerformanceChart.jsx
│   │   │   ├── StreamViewer.jsx
│   │   │   └── IteratorDemo.jsx
│   │   ├── services/
│   │   │   └── generatorService.js
│   │   ├── pages/
│   │   │   ├── HomePage.jsx
│   │   │   └── DashboardPage.jsx
│   │   ├── App.jsx
│   │   └── main.jsx
│   ├── public/
│   │   └── index.html
│   ├── package.json
│   ├── vite.config.js
│   └── README.md
│
└── README.md
```

## Features

### Backend (PHP)
1. **Generators**
   - CSV Processor: Efficiently process large CSV files
   - Database Iterator: Process database records in batches
   - File Streamer: Stream file contents in chunks
   - Data Pipeline: Chain operations using generators
   - Advanced Examples: Fibonacci, Primes, Directory traversal

2. **Iterators**
   - Custom Collection: Implements Iterator and Countable interfaces
   - Filter Iterator: Filter data using custom predicates
   - Map Iterator: Transform data using mapping functions
   - Infinite Sequence: Generate infinite sequences

### Frontend (React)
1. **Data Visualization**
   - Data Processor: Display processed CSV data
   - Performance Chart: Visualize generated sequences
   - Stream Viewer: Simulate data streaming
   - Iterator Demo: Interactive iterator demonstrations

## Installation

### Backend
```bash
cd backend-php
composer install
cp .env.example .env
# Update .env with your database credentials
```

### Frontend
```bash
cd frontend-react
npm install
```

## Running the Application

### Backend
```bash
cd backend-php
php server.php
```

### Frontend
```bash
cd frontend-react
npm run dev
```

## API Endpoints

- `GET /api/process/csv` - Process CSV data
- `GET /api/process/file` - Process file stream
- `GET /api/fibonacci/{limit}` - Generate Fibonacci sequence
- `GET /api/primes/{limit}` - Generate prime numbers
- `GET /api/stream` - Simulate data streaming

## Testing

### Backend
```bash
cd backend-php
./vendor/bin/phpunit
```

## Key Concepts Demonstrated

1. **Memory Efficiency**: Generators allow processing large datasets without loading everything into memory
2. **Lazy Evaluation**: Values are generated on-demand rather than all at once
3. **Pipeline Processing**: Chain operations together for efficient data transformation
4. **Custom Iterators**: Implement custom iteration logic for specific use cases
5. **Infinite Sequences**: Generate values on-demand without pre-computing