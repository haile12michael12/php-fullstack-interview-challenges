FROM php:7.3-cli

RUN apt-get update && apt-get install -y git unzip libprotobuf-dev protobuf-compiler supervisor     && rm -rf /var/lib/apt/lists/*

# Install grpc & protobuf extensions
RUN pecl install grpc && docker-php-ext-enable grpc  && pecl install protobuf && docker-php-ext-enable protobuf

WORKDIR /var/www
COPY . .

# Composer (optional)
RUN curl -sS https://getcomposer.org/installer | php && php composer.phar install || true

CMD ["php", "-S", "0.0.0.0:50051", "server.php"]
