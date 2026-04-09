#!/bin/bash
set -e

echo "🚀 Starting Inventory Pro API..."

# Configurar Apache para usar el puerto de Render
PORT=${PORT:-10000}
echo "🔧 Configurando Apache para puerto $PORT..."
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/*.conf

# Instalar dependencias (por si falta Socialite)
echo "📦 Instalando dependencias..."
composer install --no-dev --optimize-autoloader --no-interaction

# Esperar a que PostgreSQL esté disponible
echo "⏳ Conectando a PostgreSQL..."
for i in {1..30}; do
    if php -r "try { \$pdo = new PDO(getenv('DATABASE_URL')); echo 'OK'; } catch (Exception \$e) { echo 'FAIL'; }" 2>/dev/null | grep -q "OK"; then
        echo "✅ PostgreSQL conectado"
        break
    fi
    echo "  Intentando conectar... ($i/30)"
    sleep 2
done

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
php artisan migrate --force

echo "✅ Setup completo! Iniciando Apache en puerto $PORT..."
exec apache2-foreground
