#!/bin/bash
set -e

echo "🚀 Starting Inventory Pro API..."

# Debug: Show APP_KEY value
echo "🔑 DEBUG - APP_KEY value: $APP_KEY"
php -r "echo 'PHP sees APP_KEY: ' . getenv('APP_KEY') . PHP_EOL;"

# Check .env file
echo "📄 DEBUG - .env APP_KEY line:"
grep APP_KEY .env || echo "APP_KEY not found in .env"

# Run migrations only
echo "📊 Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "⚠️ Migrations may have issues"

echo "✅ Setup complete! Starting Apache..."
exec apache2-foreground
