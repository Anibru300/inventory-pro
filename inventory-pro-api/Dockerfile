FROM php:8.3-fpm-alpine

# Install dependencies
RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    linux-headers \
    autoconf \
    gcc \
    g++ \
    make

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql zip bcmath gd mbstring xml
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Create necessary directories
RUN mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs
RUN chmod -R 777 bootstrap/cache storage

# Generate keys and optimize
RUN php artisan config:cache || true
RUN php artisan route:cache || true

# Expose port
EXPOSE 8000

# Start server
CMD php artisan serve --host 0.0.0.0 --port 8000