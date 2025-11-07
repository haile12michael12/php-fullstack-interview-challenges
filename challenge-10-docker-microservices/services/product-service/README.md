# Product Service

## Description
The Product Service handles product catalog and inventory management for the Docker Microservices Challenge.

## Features
- Product creation and management
- Inventory tracking
- Product search and filtering

## API Endpoints
- `GET /products/{id}` - Get product by ID
- `POST /products` - Create a new product

## Technologies
- PHP 8.1
- MySQL
- Docker

## Setup
1. Build the service: `docker-compose build product-service`
2. Start the service: `docker-compose up product-service`
3. Access the service at `http://localhost:8082`