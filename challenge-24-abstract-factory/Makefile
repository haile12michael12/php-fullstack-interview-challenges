# Makefile for Challenge 24: Abstract Factory

# Variables
DOCKER_COMPOSE = docker-compose
PHP_SERVICE = backend
NODE_SERVICE = frontend

# Default target
.PHONY: help
help:
	@echo "Challenge 24: Abstract Factory"
	@echo ""
	@echo "Usage:"
	@echo "  make setup            Install all dependencies"
	@echo "  make start            Start all services"
	@echo "  make stop             Stop all services"
	@echo "  make restart          Restart all services"
	@echo "  make backend          Start backend services only"
	@echo "  make frontend         Start frontend services only"
	@echo "  make test             Run all tests"
	@echo "  make test-php         Run PHP tests"
	@echo "  make test-js          Run JavaScript tests"
	@echo "  make build            Build Docker images"
	@echo "  make clean            Remove Docker containers and volumes"
	@echo "  make logs             View service logs"
	@echo "  make shell-php        Open shell in PHP container"
	@echo "  make shell-node       Open shell in Node container"

# Setup project
.PHONY: setup
setup:
	@echo "Setting up project dependencies..."
	cd backend-php && composer install
	cd frontend-react && npm install
	@echo "Setup complete!"

# Start all services
.PHONY: start
start:
	@echo "Starting all services..."
	$(DOCKER_COMPOSE) up -d
	@echo "Services started!"
	@echo "Backend: http://localhost:8000"
	@echo "Frontend: http://localhost:3000"

# Stop all services
.PHONY: stop
stop:
	@echo "Stopping all services..."
	$(DOCKER_COMPOSE) down
	@echo "Services stopped!"

# Restart all services
.PHONY: restart
restart:
	@echo "Restarting all services..."
	$(DOCKER_COMPOSE) down
	$(DOCKER_COMPOSE) up -d
	@echo "Services restarted!"

# Start backend services only
.PHONY: backend
backend:
	@echo "Starting backend services..."
	$(DOCKER_COMPOSE) up -d mysql postgresql redis mongodb backend
	@echo "Backend services started!"

# Start frontend services only
.PHONY: frontend
frontend:
	@echo "Starting frontend services..."
	$(DOCKER_COMPOSE) up -d frontend
	@echo "Frontend services started!"

# Run all tests
.PHONY: test
test:
	@echo "Running all tests..."
	$(DOCKER_COMPOSE) exec $(PHP_SERVICE) composer test
	$(DOCKER_COMPOSE) exec $(NODE_SERVICE) npm test

# Run PHP tests
.PHONY: test-php
test-php:
	@echo "Running PHP tests..."
	$(DOCKER_COMPOSE) exec $(PHP_SERVICE) composer test

# Run JavaScript tests
.PHONY: test-js
test-js:
	@echo "Running JavaScript tests..."
	$(DOCKER_COMPOSE) exec $(NODE_SERVICE) npm test

# Build Docker images
.PHONY: build
build:
	@echo "Building Docker images..."
	$(DOCKER_COMPOSE) build
	@echo "Docker images built!"

# Clean Docker containers and volumes
.PHONY: clean
clean:
	@echo "Cleaning Docker containers and volumes..."
	$(DOCKER_COMPOSE) down -v --remove-orphans
	@echo "Cleanup complete!"

# View service logs
.PHONY: logs
logs:
	@echo "Viewing service logs..."
	$(DOCKER_COMPOSE) logs -f

# Open shell in PHP container
.PHONY: shell-php
shell-php:
	@echo "Opening shell in PHP container..."
	$(DOCKER_COMPOSE) exec $(PHP_SERVICE) bash

# Open shell in Node container
.PHONY: shell-node
shell-node:
	@echo "Opening shell in Node container..."
	$(DOCKER_COMPOSE) exec $(NODE_SERVICE) bash