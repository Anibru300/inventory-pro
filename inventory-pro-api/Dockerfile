FROM php:8.3-apache

# Install extensions
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY . .

# Create SQLite database directory
RUN mkdir -p database && touch database/database.sqlite && chmod 777 database/database.sqlite

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions
RUN chmod -R 777 storage bootstrap/cache database

# Run migrations
RUN php artisan migrate --force || true

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]