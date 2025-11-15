FROM php:8.1-cli

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Install dependencies
RUN composer install

# Expose port
EXPOSE 8000

# Start PHP development server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]