#!/bin/bash
set -e

echo "🚀 Starting Inventory Pro API..."

# Generate fresh APP_KEY
echo "🔑 Generating application key..."
php artisan key:generate --force

# Clear all caches first
echo "🧹 Clearing all caches..."
php artisan cache:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force

# Skip seeding if it fails (due to TenantSeeder issue)
php artisan db:seed --force 2>/dev/null || echo "⚠️ Seeding skipped"

echo "✅ Setup complete! Starting Apache..."
exec apache2-foreground
