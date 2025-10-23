# Challenge 18: Generators and Iterators

## Description
This challenge focuses on implementing efficient data processing using PHP generators and iterators. You'll learn to process large datasets without consuming excessive memory, create custom iterators, and optimize performance for data-intensive operations.

## Learning Objectives
- Implement generators for memory-efficient data processing
- Create custom iterators using the Iterator interface
- Process large datasets without memory exhaustion
- Implement lazy evaluation patterns
- Optimize performance for data-intensive operations
- Handle infinite data sequences

## Requirements
- PHP 8.1+
- Composer
- Understanding of generators and iterators
- Basic knowledge of memory management

## Features to Implement
1. Generator Functions
   - Memory-efficient data processing
   - Yielding values on-demand
   - Generator delegation
   - Generator return expressions

2. Custom Iterators
   - Implementing the Iterator interface
   - Creating iterable data structures
   - Iterator aggregation
   - Recursive iterators

3. Performance Optimization
   - Processing large CSV files
   - Database result set iteration
   - Streaming data processing
   - Batch processing patterns

4. Advanced Patterns
   - Coroutine-like behavior
   - Pipeline processing
   - Infinite sequences
   - Data transformation chains

## Project Structure
```
challenge-18-generators-iterators/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Generator/
│   │   │   ├── CsvProcessor.php
│   │   │   ├── DatabaseIterator.php
│   │   │   ├── FileStreamer.php
│   │   │   └── DataPipeline.php
│   │   ├── Iterator/
│   │   │   ├── CustomCollection.php
│   │   │   ├── FilterIterator.php
│   │   │   ├── MapIterator.php
│   │   │   └── InfiniteSequence.php
│   │   └── Service/
│   │       └── DataProcessor.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── DataProcessor.jsx
│   │   │   └── PerformanceChart.jsx
│   │   └── services/
│   │       └── generatorService.js
│   ├── package.json
│   └── vite.config.js
└── README.md
```

## Setup Instructions
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Copy `.env.example` to `.env` and configure your settings
4. Start the development server with `php server.php`
5. Navigate to the `frontend-react` directory
6. Run `npm install` to install frontend dependencies
7. Run `npm run dev` to start the frontend development server

## API Endpoints
- `GET /api/generators/csv` - Process large CSV file using generators
- `GET /api/generators/database` - Iterate through database results
- `GET /api/generators/stream` - Stream data processing
- `GET /api/iterators/custom` - Custom iterator demonstration
- `GET /api/iterators/infinite` - Infinite sequence generation

## Evaluation Criteria
- Effective use of generators for memory efficiency
- Proper implementation of custom iterators
- Performance optimization for large datasets
- Clean and maintainable code structure
- Comprehensive test coverage
- Documentation quality

## Resources
- [PHP Generators Documentation](https://www.php.net/manual/en/language.generators.php)
- [Iterator Interface](https://www.php.net/manual/en/class.iterator.php)
- [SPL Iterators](https://www.php.net/manual/en/spl.iterators.php)