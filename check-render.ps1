# Script de Diagnóstico para Render
# Ejecutar: .\check-render.ps1

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "DIAGNÓSTICO DE CONFIGURACIÓN RENDER" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# Verificar estructura de carpetas
Write-Host "`n📁 Verificando estructura..." -ForegroundColor Yellow
$folders = @(
    "10_CODIGO_FUENTE\inventory-pro-api",
    "10_CODIGO_FUENTE\inventory-pro-web",
    "10_CODIGO_FUENTE\inventory-pro-landing"
)

foreach ($folder in $folders) {
    if (Test-Path $folder) {
        Write-Host "  ✓ $folder existe" -ForegroundColor Green
    } else {
        Write-Host "  ✗ $folder NO existe" -ForegroundColor Red
    }
}

# Verificar archivos críticos del API
Write-Host "`n📋 Verificando archivos del API..." -ForegroundColor Yellow
$apiFiles = @(
    "10_CODIGO_FUENTE\inventory-pro-api\Dockerfile",
    "10_CODIGO_FUENTE\inventory-pro-api\Dockerfile.render",
    "10_CODIGO_FUENTE\inventory-pro-api\composer.json"
)

foreach ($file in $apiFiles) {
    if (Test-Path $file) {
        Write-Host "  ✓ $([System.IO.Path]::GetFileName($file)) existe" -ForegroundColor Green
    } else {
        Write-Host "  ✗ $([System.IO.Path]::GetFileName($file)) NO existe" -ForegroundColor Red
    }
}

# Verificar que composer.lock NO existe (debe regenerarse)
Write-Host "`n🔍 Verificando composer.lock..." -ForegroundColor Yellow
$lockFile = "10_CODIGO_FUENTE\inventory-pro-api\composer.lock"
if (Test-Path $lockFile) {
    Write-Host "  ⚠ composer.lock existe (debería eliminarse)" -ForegroundColor Yellow
    Write-Host "    Recomendación: Eliminar para regenerar" -ForegroundColor Gray
} else {
    Write-Host "  ✓ composer.lock no existe (correcto, se regenerará en build)" -ForegroundColor Green
}

# Verificar render.yaml
Write-Host "`n🔍 Verificando render.yaml..." -ForegroundColor Yellow
if (Test-Path "10_CODIGO_FUENTE\render.yaml") {
    Write-Host "  ✓ render.yaml existe" -ForegroundColor Green
} else {
    Write-Host "  ✗ render.yaml NO existe" -ForegroundColor Red
}

# Verificar configuración de landing
Write-Host "`n📄 Verificando landing page..." -ForegroundColor Yellow
$landingConfig = "10_CODIGO_FUENTE\inventory-pro-landing\next.config.mjs"
if (Test-Path $landingConfig) {
    Write-Host "  ✓ next.config.mjs existe" -ForegroundColor Green
} else {
    Write-Host "  ✗ next.config.mjs NO existe" -ForegroundColor Red
}

Write-Host "`n==========================================" -ForegroundColor Cyan
Write-Host "INSTRUCCIONES PARA RENDER:" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "`n1. Ve a: https://dashboard.render.com" -ForegroundColor White
Write-Host "2. Selecciona el servicio 'inventory-pro-api-v2'" -ForegroundColor White
Write-Host "3. Ve a la pestaña 'Settings'" -ForegroundColor White
Write-Host "4. En 'Dockerfile Path' escribe: ./Dockerfile.render" -ForegroundColor White
Write-Host "5. Guarda cambios" -ForegroundColor White
Write-Host "`nO mejor aún, usa 'Blueprint' para desplegar desde render.yaml:" -ForegroundColor White
Write-Host "  - Ve al Dashboard de Render" -ForegroundColor White
Write-Host "  - Click en 'Blueprints'" -ForegroundColor White
Write-Host "  - Conecta tu repositorio" -ForegroundColor White
Write-Host "==========================================" -ForegroundColor Cyan
