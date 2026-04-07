#!/bin/bash
set -e

echo "🚀 Starting Inventory Pro API..."

# FORZAR APP_KEY correcta - sobrescribe cualquier variable de entorno
export APP_KEY="base64:GKeBjry+gf8vc+uhMm73SC6wqtRNdQcFE8Oy+okKXb8="
echo "🔑 FORCED APP_KEY: $APP_KEY"

# Actualizar el archivo .env con la clave correcta
sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" /var/www/html/.env
echo "📄 .env actualizado con APP_KEY correcta"

# Verificar si la base de datos ya existe y tiene usuarios
DB_FILE="/var/www/html/database/database.sqlite"
if [ -f "$DB_FILE" ]; then
    # Verificar si hay usuarios en la base de datos
    USER_COUNT=$(sqlite3 "$DB_FILE" "SELECT COUNT(*) FROM users;" 2>/dev/null || echo "0")
    if [ "$USER_COUNT" -gt "0" ]; then
        echo "✅ Base de datos existente con $USER_COUNT usuarios - Omitiendo seeders"
        # Solo ejecutar migraciones pendientes sin seeders
        php artisan migrate --force 2>/dev/null || echo "⚠️ No hay migraciones pendientes"
    else
        echo "📊 Base de datos vacía - Ejecutando migraciones y seeders..."
        php artisan migrate --force
        php artisan db:seed --force 2>/dev/null || echo "⚠️ Seeding skipped"
    fi
else
    echo "📊 Creando nueva base de datos..."
    touch "$DB_FILE"
    chmod 777 "$DB_FILE"
    php artisan migrate --force
    php artisan db:seed --force 2>/dev/null || echo "⚠️ Seeding skipped"
fi

echo "✅ Setup complete! Starting Apache..."
exec apache2-foreground
