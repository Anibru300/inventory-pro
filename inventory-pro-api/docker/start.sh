#!/bin/bash
set -e

echo "🚀 Starting Inventory Pro API with NGINX + PHP-FPM..."

# FORZAR APP_KEY correcta
export APP_KEY="base64:GKeBjry+gf8vc+uhMm73SC6wqtRNdQcFE8Oy+okKXb8="
echo "🔑 APP_KEY configurada"

# Actualizar el archivo .env con la clave correcta
sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" /var/www/html/.env

# Actualizar APP_URL si hay un puerto asignado
if [ -n "$RENDER_EXTERNAL_HOSTNAME" ]; then
    export APP_URL="https://$RENDER_EXTERNAL_HOSTNAME"
    sed -i "s|^APP_URL=.*|APP_URL=$APP_URL|" /var/www/html/.env
    echo "🌐 APP_URL configurado: $APP_URL"
fi

# Configurar PHP-FPM para escuchar en TCP en lugar de socket
sed -i 's/listen = .*/listen = 127.0.0.1:9000/' /usr/local/etc/php-fpm.d/www.conf

# Esperar a que PostgreSQL esté disponible
echo "⏳ Conectando a PostgreSQL..."
for i in {1..30}; do
    if php -r "try { new PDO('pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); echo 'OK'; } catch (Exception \$e) { echo 'FAIL'; }" 2>/dev/null | grep -q "OK"; then
        echo "✅ PostgreSQL conectado"
        break
    fi
    echo "  Intentando conectar... ($i/30)"
    sleep 2
done

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
php artisan migrate --force

# Iniciar PHP-FPM en background
echo "🐘 Iniciando PHP-FPM..."
php-fpm -D

# Iniciar NGINX en foreground
echo "🌐 Iniciando NGINX en puerto 10000..."
exec nginx -g 'daemon off;'
