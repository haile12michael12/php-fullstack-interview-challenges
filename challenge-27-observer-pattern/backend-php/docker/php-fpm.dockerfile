FROM php:8.1-fpm

# Install SQLite extension
RUN docker-php-ext-install pdo pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install dependencies
RUN composer install

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]