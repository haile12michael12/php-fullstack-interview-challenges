FROM php:8.1-cli

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install zip

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Install Memcached extension
RUN apt-get install -y libmemcached-dev \
    && pecl install memcached \
    && docker-php-ext-enable memcached

# Install APCu extension
RUN pecl install apcu \
    && docker-php-ext-enable apcu

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer.json and install dependencies
COPY composer.json ./
RUN composer install --no-dev --no-scripts

# Copy application files
COPY . .

# Expose port
EXPOSE 8080

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]