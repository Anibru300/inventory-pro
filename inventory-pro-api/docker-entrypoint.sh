#!/bin/bash
set -e

echo "🚀 Starting Inventory Pro API..."

# Configurar Apache para usar el puerto de Render
PORT=${PORT:-10000}
echo "🔧 Configurando Apache para puerto $PORT..."
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -ri -e "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/*.conf

# Configurar Apache para pasar el header Authorization a PHP
echo "🔧 Configurando Apache para Authorization headers..."
cat > /etc/apache2/conf-available/authorization.conf << 'EOF'
# Pass Authorization header to PHP
SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTP:Authorization} ^(.*)
    RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
</IfModule>
EOF
a2enconf authorization

# Habilitar mod_rewrite y mod_headers si no están habilitados
a2enmod rewrite
a2enmod headers

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
