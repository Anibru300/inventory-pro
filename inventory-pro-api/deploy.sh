#!/bin/bash

# StockWolf Backend Deployment Script
# Usage: ./deploy.sh

echo "🐺 StockWolf Backend Deployment"
echo "================================"

# Check PHP version
echo "🔍 Checking PHP version..."
php -v | head -1

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Clear caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force

# Optimize
echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link
echo "🔗 Creating storage link..."
php artisan storage:link

echo ""
echo "✅ Deployment complete!"
echo "🚀 Your API is ready at: https://inventory-pro-api-v3.onrender.com"
