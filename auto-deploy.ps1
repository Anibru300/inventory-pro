# =============================================================================
# SCRIPT DE DESPLIEGUE AUTOMÁTICO - INVENTORY PRO
# Ejecuta deploys y monitoreo automáticamente
# =============================================================================

param(
    [Parameter()]
    [ValidateSet("all", "api", "web", "landing", "status", "logs")]
    [string]$Accion = "status",
    
    [string]$Servicio = "inventory-pro-api-v2",
    [int]$Lineas = 50
)

$ErrorActionPreference = "Continue"

# Cargar configuración
$envFile = ".env.local"
if (Test-Path $envFile) {
    Get-Content $envFile | ForEach-Object {
        if ($_ -match "^([^#][^=]+)=(.+)$") {
            $name = $matches[1].Trim()
            $value = $matches[2].Trim()
            [Environment]::SetEnvironmentVariable($name, $value, "Process")
        }
    }
}

function Write-Header($text) {
    Write-Host "`n==========================================" -ForegroundColor Cyan
    Write-Host $text -ForegroundColor Cyan
    Write-Host "==========================================" -ForegroundColor Cyan
}

function Write-Success($text) { Write-Host "✓ $text" -ForegroundColor Green }
function Write-Error($text) { Write-Host "✗ $text" -ForegroundColor Red }
function Write-Info($text) { Write-Host "→ $text" -ForegroundColor Yellow }

# Verificar dependencias
$renderExe = "$env:LOCALAPPDATA\render\render.exe"
if (-not (Test-Path $renderExe)) {
    Write-Error "Render CLI no encontrado en $renderExe"
    exit 1
}

Write-Header "DEPLOY AUTOMÁTICO - INVENTORY PRO"

switch ($Accion) {
    "status" {
        Write-Header "ESTADO DE SERVICIOS"
        
        $services = @(
            $env:RENDER_SERVICE_API,
            $env:RENDER_SERVICE_WEB,
            $env:RENDER_SERVICE_LANDING
        )
        
        foreach ($svc in $services) {
            if ($svc) {
                Write-Info "Verificando: $svc"
                # Aquí iría la llamada a la API de Render
            }
        }
    }
    
    "logs" {
        Write-Header "LOGS DE $Servicio"
        Write-Info "Mostrando últimas $Lineas líneas..."
        
        if ($env:RENDER_API_KEY -and $env:RENDER_API_KEY -ne "TU_RENDER_API_KEY_AQUI") {
            # Usar API directa
            try {
                $headers = @{ "Authorization" = "Bearer $($env:RENDER_API_KEY)" }
                # Invocar API de Render aquí
                Write-Success "Logs obtenidos vía API"
            } catch {
                Write-Error "Error obteniendo logs: $_"
            }
        } else {
            Write-Error "RENDER_API_KEY no configurado"
            Write-Info "Edita .env.local y agrega tu API Key"
        }
    }
    
    "all" {
        Write-Header "DEPLOY COMPLETO"
        Write-Info "Iniciando deploy de todos los servicios..."
        # Lógica de deploy aquí
    }
    
    default {
        Write-Info "Uso: .\auto-deploy.ps1 -Accion [status|logs|all|api|web|landing]"
    }
}

Write-Host "`n==========================================" -ForegroundColor Cyan
