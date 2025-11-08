# Makefile for Challenge 15 - Advanced Error Handling

# Variables
PHP=php
COMPOSER=composer
DOCKER=docker
DOCKER_COMPOSE=docker-compose

# Default target
.PHONY: help
help:
	@echo "Challenge 15 - Advanced Error Handling"
	@echo ""
	@echo "Usage:"
	@echo "  make install          Install PHP dependencies"
	@echo "  make start            Start development server"
	@echo "  make test             Run unit tests"
	@echo "  make test-coverage    Run tests with coverage"
	@echo "  make lint             Run code style checks"
	@echo "  make fix              Fix code style issues"
	@echo "  make docker-up        Start Docker containers"
	@echo "  make docker-down      Stop Docker containers"
	@echo "  make docker-logs      View Docker logs"
	@echo "  make clean            Clean cache and logs"

# Install dependencies
.PHONY: install
install:
	$(COMPOSER) install

# Start development server
.PHONY: start
start:
	$(PHP) -S localhost:8000 -t public server.php

# Run unit tests
.PHONY: test
test:
	$(PHP) vendor/bin/phpunit

# Run tests with coverage
.PHONY: test-coverage
test-coverage:
	$(PHP) vendor/bin/phpunit --coverage-html coverage

# Run code style checks
.PHONY: lint
lint:
	$(PHP) vendor/bin/phpcs --standard=PSR12 app tests

# Fix code style issues
.PHONY: fix
fix:
	$(PHP) vendor/bin/phpcbf --standard=PSR12 app tests

# Start Docker containers
.PHONY: docker-up
docker-up:
	$(DOCKER_COMPOSE) up -d

# Stop Docker containers
.PHONY: docker-down
docker-down:
	$(DOCKER_COMPOSE) down

# View Docker logs
.PHONY: docker-logs
docker-logs:
	$(DOCKER_COMPOSE) logs -f

# Clean cache and logs
.PHONY: clean
clean:
	rm -rf storage/cache/*
	rm -rf storage/logs/*
	rm -rf coverage/
	rm -rf .phpunit.cache/

# Setup storage directories
.PHONY: setup-storage
setup-storage:
	mkdir -p storage/logs storage/cache
	chmod -R 755 storage/

# Generate application key
.PHONY: key-generate
key-generate:
	$(PHP) -r "echo 'APP_KEY=' . base64_encode(random_bytes(32)) . PHP_EOL;"