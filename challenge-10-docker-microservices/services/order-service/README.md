# Order Service

## Description
The Order Service manages customer orders and transactions for the Docker Microservices Challenge.

## Features
- Order creation and management
- Order status tracking
- Transaction processing

## API Endpoints
- `GET /orders/{id}` - Get order by ID
- `POST /orders` - Create a new order

## Technologies
- PHP 8.1
- MySQL
- Docker

## Setup
1. Build the service: `docker-compose build order-service`
2. Start the service: `docker-compose up order-service`
3. Access the service at `http://localhost:8083`