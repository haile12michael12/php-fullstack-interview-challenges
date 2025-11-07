# Makefile for Docker Microservices Challenge

# Build all services
build:
	docker-compose build

# Start all services
up:
	docker-compose up -d

# Stop all services
down:
	docker-compose down

# View logs for all services
logs:
	docker-compose logs -f

# View logs for a specific service
logs-%:
	docker-compose logs -f $*

# Run tests for all services
test:
	docker-compose exec user-service composer test
	docker-compose exec product-service composer test
	docker-compose exec order-service composer test

# Run PHPStan for all services
phpstan:
	docker-compose exec user-service composer phpstan
	docker-compose exec product-service composer phpstan
	docker-compose exec order-service composer phpstan

# Scale services
scale-user:
	docker-compose up -d --scale user-service=3

scale-product:
	docker-compose up -d --scale product-service=3

scale-order:
	docker-compose up -d --scale order-service=3

# Clean up
clean:
	docker-compose down -v --remove-orphans

# Install dependencies for all services
install:
	docker-compose exec user-service composer install
	docker-compose exec product-service composer install
	docker-compose exec order-service composer install

# Show service status
status:
	docker-compose ps

.PHONY: build up down logs test phpstan scale-user scale-product scale-order clean install status