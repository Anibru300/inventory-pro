# Script para arreglar todo - Inventory Pro
Write-Host "ARREGLANDO CONFIGURACION..." -ForegroundColor Green

cd docker

# 1. Detener todo
docker-compose down

# 2. Crear archivos necesarios
New-Item -ItemType Directory -Force -Path "..\inventory-pro-api\public" | Out-Null
New-Item -ItemType Directory -Force -Path "..\inventory-pro-api\bootstrap\cache" | Out-Null

# 3. Crear index.php
@'
<?php
use Illuminate\Http\Request;
define('LARAVEL_START', microtime(true));
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}
require __DIR__.'/../vendor/autoload.php';
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
'@ | Set-Content "..\inventory-pro-api\public\index.php" -Force

# 4. Crear .htaccess
@'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>
    RewriteEngine On
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
'@ | Set-Content "..\inventory-pro-api\public\.htaccess" -Force

# 5. Iniciar servicios
docker-compose up -d

# 6. Esperar
timeout /t 10 /nobreak | Out-Null

# 7. Instalar dependencias
docker-compose exec -T api composer install --no-interaction 2>$null

# 8. Configurar entorno
if (-not (Test-Path "..\inventory-pro-api\.env")) {
    Copy-Item "..\inventory-pro-api\.env.example" "..\inventory-pro-api\.env"
    docker-compose exec -T api php artisan key:generate 2>$null
}

# 9. Migraciones
docker-compose exec -T api php artisan migrate:fresh --seed --force 2>$null

# 10. Limpiar cache
docker-compose exec -T api php artisan config:clear 2>$null
docker-compose exec -T api php artisan cache:clear 2>$null

Write-Host "LISTO! Prueba en tu navegador:" -ForegroundColor Green
Write-Host "  http://localhost/api/health" -ForegroundColor Yellow
Write-Host "  http://localhost/api/login (POST)" -ForegroundColor Yellow
Write-Host "  http://localhost (Frontend)" -ForegroundColor Yellow