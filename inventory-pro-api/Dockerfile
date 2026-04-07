FROM php:8.3-apache

# Install system dependencies including GD dependencies
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    # Dependencies for GD
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions including GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_sqlite zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (for better caching)
COPY composer.json ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy the rest of the application
COPY . .

# Create necessary directories
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache database

# Set permissions
RUN chmod -R 777 storage bootstrap/cache database

# Create SQLite database if it doesn't exist
RUN touch database/database.sqlite && chmod 777 database/database.sqlite

# Configure Apache DocumentRoot
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!<Directory /var/www/>!<Directory /var/www/html/public>!g' /etc/apache2/apache2.conf \
    && sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

# Run post-autoload scripts
RUN composer run-script post-autoload-dump || true

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
