# Makefile for Markdown Previewer

# Variables
COMPOSE_FILE = docker-compose.yml
BACKEND_DIR = backend-php
FRONTEND_DIR = frontend-nextjs

# Help
help:
	@echo "Markdown Previewer - Makefile Commands"
	@echo ""
	@echo "Usage:"
	@echo "  make setup          Install dependencies for both frontend and backend"
	@echo "  make start          Start all services"
	@echo "  make stop           Stop all services"
	@echo "  make restart        Restart all services"
	@echo "  make backend        Start backend service only"
	@echo "  make frontend       Start frontend service only"
	@echo "  make test           Run tests for both frontend and backend"
	@echo "  make test-backend   Run backend tests"
	@echo "  make test-frontend  Run frontend tests"
	@echo "  make build          Build Docker images"
	@echo "  make clean          Remove Docker containers and volumes"
	@echo "  make logs           View logs for all services"
	@echo "  make logs-backend   View backend logs"
	@echo "  make logs-frontend  View frontend logs"

# Setup
setup:
	@echo "Setting up backend dependencies..."
	cd $(BACKEND_DIR) && composer install
	@echo "Setting up frontend dependencies..."
	cd $(FRONTEND_DIR) && npm install

# Start services
start:
	@echo "Starting all services..."
	docker-compose -f $(COMPOSE_FILE) up -d

# Stop services
stop:
	@echo "Stopping all services..."
	docker-compose -f $(COMPOSE_FILE) down

# Restart services
restart:
	@echo "Restarting all services..."
	docker-compose -f $(COMPOSE_FILE) restart

# Start backend only
backend:
	@echo "Starting backend service..."
	docker-compose -f $(COMPOSE_FILE) up -d backend

# Start frontend only
frontend:
	@echo "Starting frontend service..."
	docker-compose -f $(COMPOSE_FILE) up -d frontend

# Run all tests
test:
	@echo "Running backend tests..."
	cd $(BACKEND_DIR) && ./vendor/bin/phpunit
	@echo "Running frontend tests..."
	cd $(FRONTEND_DIR) && npm test

# Run backend tests
test-backend:
	@echo "Running backend tests..."
	cd $(BACKEND_DIR) && ./vendor/bin/phpunit

# Run frontend tests
test-frontend:
	@echo "Running frontend tests..."
	cd $(FRONTEND_DIR) && npm test

# Build Docker images
build:
	@echo "Building Docker images..."
	docker-compose -f $(COMPOSE_FILE) build

# Clean up
clean:
	@echo "Cleaning up containers and volumes..."
	docker-compose -f $(COMPOSE_FILE) down -v

# View logs
logs:
	@echo "Viewing logs for all services..."
	docker-compose -f $(COMPOSE_FILE) logs -f

# View backend logs
logs-backend:
	@echo "Viewing backend logs..."
	docker-compose -f $(COMPOSE_FILE) logs -f backend

# View frontend logs
logs-frontend:
	@echo "Viewing frontend logs..."
	docker-compose -f $(COMPOSE_FILE) logs -f frontend

.PHONY: help setup start stop restart backend frontend test test-backend test-frontend build clean logs logs-backend logs-frontend