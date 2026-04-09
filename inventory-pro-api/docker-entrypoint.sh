#!/bin/bash
set -e

echo "🚀 Starting Inventory Pro API..."

# Configurar Apache para usar el puerto de Render
PORT=${PORT:-10000}
echo "🔧 Configurando Apache para puerto $PORT..."
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -ri -e "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/*.conf

# CRITICAL: Configurar Apache para pasar el header Authorization a PHP
# CGIPassAuth debe ir en contexto <Directory>, no <VirtualHost>
echo "🔧 Configurando Apache para Authorization headers..."
APACHE_CONFIG="/etc/apache2/sites-enabled/000-default.conf"

if [ -f "$APACHE_CONFIG" ]; then
    # Verificar si SetEnvIf ya está configurado
    if ! grep -q "SetEnvIf Authorization" "$APACHE_CONFIG"; then
        echo "   Aplicando SetEnvIf en VirtualHost..."
        # Insertar SetEnvIf antes del cierre de VirtualHost
        sed -i '/<\/VirtualHost>/i\    SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1' "$APACHE_CONFIG"
        echo "   ✓ SetEnvIf configurado"
    else
        echo "   ✓ SetEnvIf ya estaba configurado"
    fi
    
    # CGIPassAuth debe ir dentro de <Directory>, no <VirtualHost>
    if ! grep -q "CGIPassAuth" "$APACHE_CONFIG"; then
        echo "   Aplicando CGIPassAuth en Directory..."
        # Buscar el bloque <Directory> y agregar CGIPassAuth dentro
        # Si no hay bloque Directory, lo agregamos
        if grep -q "<Directory" "$APACHE_CONFIG"; then
            # Insertar CGIPassAuth On dentro del primer bloque Directory
            sed -i '/<Directory/,/<\/Directory>/{
                /<\/Directory>/i\        CGIPassAuth On
            }' "$APACHE_CONFIG"
        else
            # Agregar un bloque Directory para /var/www/html
            sed -i '/<\/VirtualHost>/i\    <Directory /var/www/html>\n        Options Indexes FollowSymLinks\n        AllowOverride All\n        Require all granted\n        CGIPassAuth On\n    </Directory>' "$APACHE_CONFIG"
        fi
        echo "   ✓ CGIPassAuth configurado"
    else
        echo "   ✓ CGIPassAuth ya estaba configurado"
    fi
fi

# Verificar sintaxis de Apache
echo "🔍 Verificando sintaxis de Apache..."
if apache2ctl configtest 2>&1 | grep -q "Syntax OK"; then
    echo "   ✓ Sintaxis correcta"
else
    echo "   ⚠ Error en sintaxis:"
    apache2ctl configtest 2>&1 || true
fi

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

echo "✅ Setup completo! Iniciando Apache en puerto $PORT..."
exec apache2-foreground
