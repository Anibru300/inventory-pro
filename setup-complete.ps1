# Inventory Pro - Script de Inicialización Completo
# Ejecutar: .\setup-complete.ps1

$ErrorActionPreference = "Stop"

function Write-Header($text) {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "  $text" -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
}

function Write-Success($text) {
    Write-Host "OK: $text" -ForegroundColor Green
}

function Write-Error($text) {
    Write-Host "ERROR: $text" -ForegroundColor Red
}

function Write-Info($text) {
    Write-Host "INFO: $text" -ForegroundColor Yellow
}

# Verificar Docker
Write-Header "Verificando Docker"
try {
    $dockerVersion = docker --version
    Write-Success "Docker encontrado: $dockerVersion"
} catch {
    Write-Error "Docker no esta instalado o no esta en PATH"
    Write-Info "Por favor instala Docker Desktop desde: https://www.docker.com/products/docker-desktop"
    pause
    exit 1
}

# Verificar Docker Compose
try {
    $composeVersion = docker compose version
    Write-Success "Docker Compose encontrado"
} catch {
    Write-Error "Docker Compose no esta disponible"
    pause
    exit 1
}

# Ir al directorio docker
$scriptPath = Split-Path -Parent $MyInvocation.MyCommand.Path
$dockerPath = Join-Path $scriptPath "docker"
Set-Location $dockerPath

Write-Header "Paso 1: Deteniendo contenedores existentes"
docker-compose down --remove-orphans 2>$null
Write-Success "Contenedores detenidos"

Write-Header "Paso 2: Construyendo imagenes Docker"
docker-compose build --no-cache
Write-Success "Imagenes construidas"

Write-Header "Paso 3: Iniciando servicios"
docker-compose up -d
Write-Success "Servicios iniciados"

Write-Header "Paso 4: Esperando a que PostgreSQL este listo"
$retries = 0
$maxRetries = 30
while ($retries -lt $maxRetries) {
    try {
        $result = docker-compose exec -T postgres pg_isready -U inventory_user 2>$null
        if ($result -match "accepting connections") {
            Write-Success "PostgreSQL esta listo"
            break
        }
    } catch {
        # Continuar intentando
    }
    
    $retries++
    Write-Info "Esperando... ($retries/$maxRetries)"
    Start-Sleep -Seconds 2
}

if ($retries -eq $maxRetries) {
    Write-Error "PostgreSQL no respondio a tiempo"
    pause
    exit 1
}

Write-Header "Paso 5: Instalando dependencias de Composer"
docker-compose exec -T api composer install --no-interaction --optimize-autoloader
Write-Success "Dependencias de Composer instaladas"

Write-Header "Paso 6: Configurando entorno"
$envFile = "..\inventory-pro-api\.env"
if (-not (Test-Path $envFile)) {
    Copy-Item "..\inventory-pro-api\.env.example" $envFile
    Write-Success "Archivo .env creado"
    
    # Generar APP_KEY
    docker-compose exec -T api php artisan key:generate
    Write-Success "APP_KEY generado"
} else {
    Write-Info "Archivo .env ya existe, omitiendo"
}

Write-Header "Paso 7: Ejecutando migraciones"
docker-compose exec -T api php artisan migrate:fresh --seed --force
Write-Success "Migraciones ejecutadas"

Write-Header "Paso 8: Optimizando Laravel"
docker-compose exec -T api php artisan config:cache
docker-compose exec -T api php artisan route:cache
Write-Success "Laravel optimizado"

Write-Header "PASO 9: Verificacion Final"

# Verificar API
try {
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/health" -Method GET -TimeoutSec 5
    Write-Success "API esta respondiendo correctamente"
} catch {
    Write-Error "API no responde correctamente"
}

# Resumen
Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "        SETUP COMPLETADO!               " -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Accesos:" -ForegroundColor White
Write-Host "  Frontend: http://localhost:5173" -ForegroundColor Yellow
Write-Host "  API:      http://localhost:8000/api" -ForegroundColor Yellow
Write-Host "  Health:   http://localhost:8000/api/health" -ForegroundColor Yellow
Write-Host ""
Write-Host "Credenciales:" -ForegroundColor White
Write-Host "  Email:    admin@example.com" -ForegroundColor Yellow
Write-Host "  Password: password" -ForegroundColor Yellow
Write-Host ""
Write-Host "Comandos utiles:" -ForegroundColor Gray
Write-Host "  cd docker && docker-compose logs -f api" -ForegroundColor Gray
Write-Host "  cd docker && docker-compose logs -f web" -ForegroundColor Gray
Write-Host "  cd docker && docker-compose down" -ForegroundColor Gray
Write-Host ""
pause