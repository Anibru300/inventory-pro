# =============================================================================
# SISTEMA DE OPERACIONES RENDER - INVENTORY PRO
# Operaciones automatizadas conectadas a Render API
# =============================================================================

param(
    [Parameter(Mandatory=$false)]
    [ValidateSet("logs", "deploy", "restart", "status", "env", "services")]
    [string]$Action = "status",
    
    [string]$Service = "inventory-pro-api-v2",
    [int]$Lines = 50
)

# Cargar configuración
$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$rootDir = Split-Path -Parent $scriptDir
$envFile = Join-Path $rootDir ".env.local"

if (Test-Path $envFile) {
    Get-Content $envFile | ForEach-Object {
        if ($_ -match "^([^#][^=]+)=(.+)$") {
            $name = $matches[1].Trim()
            $value = $matches[2].Trim()
            [Environment]::SetEnvironmentVariable($name, $value, "Process")
        }
    }
}

$RENDER_API_KEY = $env:RENDER_API_KEY
$headers = @{
    "Authorization" = "Bearer $RENDER_API_KEY"
    "Accept" = "application/json"
}

function Write-Header($text) {
    Write-Host "`n==========================================" -ForegroundColor Cyan
    Write-Host $text -ForegroundColor Cyan
    Write-Host "==========================================" -ForegroundColor Cyan
}

function Get-RenderServices {
    try {
        $response = Invoke-WebRequest -Uri "https://api.render.com/v1/services?limit=20" -Headers $headers -UseBasicParsing
        $services = ($response.Content | ConvertFrom-Json).service
        return $services
    } catch {
        Write-Error "Error obteniendo servicios: $_"
        return $null
    }
}

function Get-ServiceLogs($serviceId, $limit = 50) {
    try {
        $response = Invoke-WebRequest -Uri "https://api.render.com/v1/services/$serviceId/logs?limit=$limit" -Headers $headers -UseBasicParsing
        return $response.Content | ConvertFrom-Json
    } catch {
        Write-Error "Error obteniendo logs: $_"
        return $null
    }
}

function Deploy-Service($serviceId) {
    try {
        $body = @{ "clearCache" = "do_not_clear" } | ConvertTo-Json
        $response = Invoke-WebRequest -Uri "https://api.render.com/v1/services/$serviceId/deploys" -Method POST -Headers $headers -Body $body -ContentType "application/json" -UseBasicParsing
        return $response.Content | ConvertFrom-Json
    } catch {
        Write-Error "Error iniciando deploy: $_"
        return $null
    }
}

# Ejecutar acción
switch ($Action) {
    "services" {
        Write-Header "SERVICIOS EN RENDER"
        $services = Get-RenderServices
        if ($services) {
            $services | Select-Object name, type, @{N="URL";E={$_.serviceDetails.url}}, @{N="Estado";E={$_.suspended}} | Format-Table -AutoSize
        }
    }
    
    "status" {
        Write-Header "ESTADO DE SERVICIOS"
        $services = Get-RenderServices
        if ($services) {
            foreach ($svc in $services) {
                Write-Host "`n🚀 $($svc.name)" -ForegroundColor Green
                Write-Host "   Tipo: $($svc.type)" -ForegroundColor Gray
                Write-Host "   URL: $($svc.serviceDetails.url)" -ForegroundColor Cyan
                Write-Host "   Estado: $($svc.suspended)" -ForegroundColor $(if($svc.suspended -eq "not_suspended"){"Green"}else{"Red"})
                Write-Host "   Última actualización: $($svc.updatedAt)" -ForegroundColor Gray
            }
        }
    }
    
    "logs" {
        Write-Header "LOGS DE $Service"
        $services = Get-RenderServices
        $target = $services | Where-Object { $_.name -eq $Service } | Select-Object -First 1
        if ($target) {
            Write-Host "Obteniendo logs de $($target.id)..." -ForegroundColor Yellow
            $logs = Get-ServiceLogs $target.id $Lines
            if ($logs) {
                $logs | ForEach-Object { Write-Host $_ }
            }
        } else {
            Write-Error "Servicio '$Service' no encontrado"
        }
    }
    
    "deploy" {
        Write-Header "DEPLOY DE $Service"
        $services = Get-RenderServices
        $target = $services | Where-Object { $_.name -eq $Service } | Select-Object -First 1
        if ($target) {
            Write-Host "Iniciando deploy de $($target.name)..." -ForegroundColor Yellow
            $deploy = Deploy-Service $target.id
            if ($deploy) {
                Write-Host "✓ Deploy iniciado" -ForegroundColor Green
            }
        } else {
            Write-Error "Servicio '$Service' no encontrado"
        }
    }
    
    default {
        Write-Host "Uso: .\scripts\render-operations.ps1 -Action [logs|deploy|status|services]" -ForegroundColor Yellow
        Write-Host "  -Service [nombre]  # Para logs, deploy" -ForegroundColor Gray
        Write-Host "  -Lines [número]    # Para logs (default: 50)" -ForegroundColor Gray
    }
}
