#!/bin/bash
set -e

echo "🚀 Starting Inventory Pro API..."

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force --seed || php artisan migrate --force

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Setup complete! Starting Apache..."

# Start Apache in foreground
exec apache2-foreground
