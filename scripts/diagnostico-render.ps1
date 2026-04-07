# =============================================================================
# DIAGNÓSTICO COMPLETO - INVENTORY PRO EN RENDER
# =============================================================================

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$rootDir = Split-Path -Parent $scriptDir

# Cargar configuración
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

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "DIAGNÓSTICO DE INVENTORY PRO - RENDER" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# 1. Verificar tokens
Write-Host "`n🔐 TOKENS CONFIGURADOS:" -ForegroundColor Yellow
if ($env:RENDER_API_KEY -and $env:RENDER_API_KEY -ne "TU_RENDER_API_KEY_AQUI") {
    Write-Host "   ✓ RENDER_API_KEY: $($env:RENDER_API_KEY.Substring(0,15))..." -ForegroundColor Green
} else {
    Write-Host "   ✗ RENDER_API_KEY no configurado" -ForegroundColor Red
}

if ($env:GITHUB_TOKEN -and $env:GITHUB_TOKEN -ne "TU_GITHUB_TOKEN_AQUI") {
    Write-Host "   ✓ GITHUB_TOKEN: $($env:GITHUB_TOKEN.Substring(0,15))..." -ForegroundColor Green
} else {
    Write-Host "   ✗ GITHUB_TOKEN no configurado" -ForegroundColor Red
}

# 2. Verificar servicios en Render
Write-Host "`n🚀 SERVICIOS EN RENDER:" -ForegroundColor Yellow
$headers = @{ "Authorization" = "Bearer $($env:RENDER_API_KEY)"; "Accept" = "application/json" }

try {
    $response = Invoke-WebRequest -Uri "https://api.render.com/v1/services?limit=20" -Headers $headers -UseBasicParsing
    $services = ($response.Content | ConvertFrom-Json).service
    
    foreach ($svc in $services) {
        $status = if ($svc.suspended -eq "not_suspended") { "✓ ACTIVO" } else { "✗ SUSPENDIDO" }
        $color = if ($svc.suspended -eq "not_suspended") { "Green" } else { "Red" }
        Write-Host "   $status - $($svc.name) ($($svc.type))" -ForegroundColor $color
        Write-Host "     URL: $($svc.serviceDetails.url)" -ForegroundColor Gray
    }
} catch {
    Write-Host "   ✗ Error conectando a Render: $_" -ForegroundColor Red
}

# 3. Verificar repositorio GitHub
Write-Host "`n📁 REPOSITORIO GITHUB:" -ForegroundColor Yellow
$ghHeaders = @{ "Authorization" = "token $($env:GITHUB_TOKEN)"; "Accept" = "application/vnd.github.v3+json" }

try {
    $response = Invoke-WebRequest -Uri "https://api.github.com/repos/$($env:GITHUB_USERNAME)/inventory-pro" -Headers $ghHeaders -UseBasicParsing
    $repo = $response.Content | ConvertFrom-Json
    Write-Host "   ✓ Repositorio: $($repo.full_name)" -ForegroundColor Green
    Write-Host "     Default branch: $($repo.default_branch)" -ForegroundColor Gray
    Write-Host "     Último push: $($repo.pushed_at)" -ForegroundColor Gray
} catch {
    Write-Host "   ✗ Error conectando a GitHub: $_" -ForegroundColor Red
}

# 4. Verificar estructura local
Write-Host "`n📂 ESTRUCTURA LOCAL:" -ForegroundColor Yellow
$folders = @("inventory-pro-api", "inventory-pro-web", "inventory-pro-landing")
foreach ($folder in $folders) {
    $path = Join-Path $rootDir $folder
    if (Test-Path $path) {
        Write-Host "   ✓ $folder existe" -ForegroundColor Green
    } else {
        Write-Host "   ✗ $folder NO existe" -ForegroundColor Red
    }
}

# 5. Verificar archivos críticos
Write-Host "`n📄 ARCHIVOS CRÍTICOS:" -ForegroundColor Yellow
$files = @(
    @("inventory-pro-api/Dockerfile.render", "Dockerfile.render del API"),
    @("inventory-pro-api/composer.json", "composer.json"),
    @("render.yaml", "Configuración de Render"),
    @(".env.local", "Variables de entorno (local)")
)
foreach ($file in $files) {
    $path = Join-Path $rootDir $file[0]
    if (Test-Path $path) {
        Write-Host "   ✓ $($file[1]) existe" -ForegroundColor Green
    } else {
        Write-Host "   ✗ $($file[1]) NO existe" -ForegroundColor Red
    }
}

Write-Host "`n==========================================" -ForegroundColor Cyan
Write-Host "DIAGNÓSTICO COMPLETADO" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
