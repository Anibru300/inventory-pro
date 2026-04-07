#!/bin/bash
set -e

echo "🚀 Starting Inventory Pro API..."

# Run migrations only (no key generate, no cache clear to keep .env values)
echo "📊 Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "⚠️ Migrations may have issues"

echo "✅ Setup complete! Starting Apache..."
exec apache2-foreground
