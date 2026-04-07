#!/bin/bash
set -e

echo "🚀 Starting Inventory Pro API..."

# Generate a fresh APP_KEY on startup (ensures valid key)
echo "🔑 Generating application key..."
php artisan key:generate --force

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force --seed || php artisan migrate --force

# Clear and rebuild caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "✅ Setup complete! Starting Apache..."

# Start Apache in foreground
exec apache2-foreground
