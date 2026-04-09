#!/bin/bash
set -e

echo "🚀 Starting Inventory Pro API..."

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

echo "✅ Setup completo! Iniciando Apache..."
exec apache2-foreground
