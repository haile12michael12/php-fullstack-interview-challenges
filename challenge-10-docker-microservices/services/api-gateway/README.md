# API Gateway

## Description
The API Gateway routes requests to appropriate backend services for the Docker Microservices Challenge.

## Features
- Request routing
- Load balancing
- Rate limiting
- SSL termination

## Configuration
- Routes are defined in `config/routes.json`
- Rate limiting is configured in `config/rate-limit.conf`

## Technologies
- Nginx
- Docker

## Setup
1. Build the service: `docker-compose build api-gateway`
2. Start the service: `docker-compose up api-gateway`
3. Access the gateway at `http://localhost:8080`