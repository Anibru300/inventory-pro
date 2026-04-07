#!/bin/bash
set -e

echo "🚀 Starting Inventory Pro API..."

# FORZAR APP_KEY correcta - sobrescribe cualquier variable de entorno
export APP_KEY="base64:GKeBjry+gf8vc+uhMm73SC6wqtRNdQcFE8Oy+okKXb8="
echo "🔑 FORCED APP_KEY: $APP_KEY"

# Actualizar el archivo .env con la clave correcta
sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" /var/www/html/.env
echo "📄 .env actualizado con APP_KEY correcta"

# Run migrations only
echo "📊 Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "⚠️ Migrations may have issues"

echo "✅ Setup complete! Starting Apache..."
exec apache2-foreground
