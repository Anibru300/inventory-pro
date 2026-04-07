# Script para ver logs de Render fácilmente
param(
    [Parameter()]
    [ValidateSet("api", "web", "landing", "all")]
    [string]$Servicio = "api",
    
    [int]$Lineas = 50,
    
    [switch]$Seguir = $false
)

$ErrorActionPreference = "Continue"

# Mapear nombres amigables a nombres de servicio
$serviceMap = @{
    "api" = "inventory-pro-api-v2"
    "web" = "inventory-pro-web"
    "landing" = "inventory-pro-landing"
}

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "VISOR DE LOGS - RENDER" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# Verificar que render CLI esté instalado
$renderExe = "$env:LOCALAPPDATA\render\render.exe"
if (-not (Test-Path $renderExe)) {
    Write-Host "❌ Render CLI no está instalado" -ForegroundColor Red
    Write-Host "Ejecuta primero: .\instalar-render-cli.ps1" -ForegroundColor Yellow
    exit 1
}

# Determinar servicio
if ($Servicio -eq "all") {
    Write-Host "Mostrando logs de todos los servicios..." -ForegroundColor Yellow
    $services = $serviceMap.Values
} else {
    $serviceName = $serviceMap[$Servicio]
    Write-Host "Servicio: $serviceName" -ForegroundColor Green
    $services = @($serviceName)
}

# Mostrar logs
foreach ($svc in $services) {
    Write-Host "`n----------------------------------------" -ForegroundColor Gray
    Write-Host "Logs de: $svc" -ForegroundColor Cyan
    Write-Host "----------------------------------------" -ForegroundColor Gray
    
    try {
        if ($Seguir) {
            # Modo seguir (tail -f)
            & $renderExe logs --service $svc --tail
        } else {
            # Solo mostrar últimas líneas
            $logs = & $renderExe logs --service $svc --limit $Lineas 2>&1
            $logs | ForEach-Object { Write-Host $_ }
        }
    } catch {
        Write-Host "Error obteniendo logs: $_" -ForegroundColor Red
    }
}

Write-Host "`n==========================================" -ForegroundColor Cyan
Write-Host "Presiona Ctrl+C para salir" -ForegroundColor Yellow
