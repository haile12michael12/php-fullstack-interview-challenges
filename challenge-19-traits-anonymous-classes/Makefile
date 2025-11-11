# Makefile for Challenge 19: Traits and Anonymous Classes

# Variables
DOCKER_COMPOSE = docker-compose
BACKEND_DIR = backend-php
FRONTEND_DIR = frontend-react

# Default target
.PHONY: help
help:
	@echo "Available targets:"
	@echo "  setup     - Set up the development environment"
	@echo "  start     - Start all services"
	@echo "  stop      - Stop all services"
	@echo "  restart   - Restart all services"
	@echo "  backend   - Start only backend services"
	@echo "  frontend  - Start only frontend services"
	@echo "  test      - Run backend tests"
	@echo "  logs      - View service logs"
	@echo "  clean     - Stop and remove containers"
	@echo "  help      - Show this help message"

# Setup development environment
.PHONY: setup
setup:
	@echo "Setting up development environment..."
	cd $(BACKEND_DIR) && composer install
	cd $(FRONTEND_DIR) && npm install
	@echo "Setup complete!"

# Start all services
.PHONY: start
start:
	@echo "Starting all services..."
	$(DOCKER_COMPOSE) up -d
	@echo "Services started! Backend: http://localhost:8080, Frontend: http://localhost:3000"

# Stop all services
.PHONY: stop
stop:
	@echo "Stopping all services..."
	$(DOCKER_COMPOSE) down
	@echo "Services stopped."

# Restart all services
.PHONY: restart
restart:
	@echo "Restarting all services..."
	$(DOCKER_COMPOSE) down
	$(DOCKER_COMPOSE) up -d
	@echo "Services restarted!"

# Start only backend services
.PHONY: backend
backend:
	@echo "Starting backend services..."
	$(DOCKER_COMPOSE) up -d backend mysql
	@echo "Backend services started! http://localhost:8080"

# Start only frontend services
.PHONY: frontend
frontend:
	@echo "Starting frontend services..."
	$(DOCKER_COMPOSE) up -d frontend
	@echo "Frontend services started! http://localhost:3000"

# Run backend tests
.PHONY: test
test:
	@echo "Running backend tests..."
	cd $(BACKEND_DIR) && ./vendor/bin/phpunit
	@echo "Tests completed."

# View service logs
.PHONY: logs
logs:
	@echo "Viewing service logs..."
	$(DOCKER_COMPOSE) logs -f

# Clean up containers
.PHONY: clean
clean:
	@echo "Cleaning up containers..."
	$(DOCKER_COMPOSE) down -v
	@echo "Containers cleaned up."