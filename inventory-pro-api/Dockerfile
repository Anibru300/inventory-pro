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

# Create directories and set permissions PRIMERO
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache database \
    && chmod -R 777 storage bootstrap/cache database

# Create SQLite database
RUN touch database/database.sqlite && chmod 777 database/database.sqlite

# Set environment variables
ENV APP_ENV=production
ENV APP_KEY=base64:aca0d9abb108edcbe32e819ec2192e33
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/var/www/html/database/database.sqlite

# Configure Apache DocumentRoot to point to /var/www/html/public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/public>|g' /etc/apache2/sites-available/000-default.conf

# Add .htaccess support
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions again after composer
RUN chmod -R 777 storage bootstrap/cache database

# Run migrations
RUN php artisan migrate --force || true

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
