#!/usr/bin/env pwsh
# Script de Verificación de Instalación - Inventory Pro

function Test-Endpoint($url, $name) {
    Write-Host "Probando $name... " -NoNewline -ForegroundColor Yellow
    try {
        $response = Invoke-RestMethod -Uri $url -TimeoutSec 5 -ErrorAction Stop
        Write-Host "✅ OK" -ForegroundColor Green
        return $true
    } catch {
        Write-Host "❌ FAIL" -ForegroundColor Red
        Write-Host "   Error: $($_.Exception.Message)" -ForegroundColor Gray
        return $false
    }
}

function Test-Port($port, $name) {
    Write-Host "Verificando puerto $port ($name)... " -NoNewline -ForegroundColor Yellow
    try {
        $connection = Test-NetConnection -ComputerName localhost -Port $port -WarningAction SilentlyContinue
        if ($connection.TcpTestSucceeded) {
            Write-Host "✅ OK" -ForegroundColor Green
            return $true
        } else {
            Write-Host "❌ CERRADO" -ForegroundColor Red
            return $false
        }
    } catch {
        Write-Host "❌ ERROR" -ForegroundColor Red
        return $false
    }
}

Clear-Host
Write-Host "╔══════════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║           VERIFICACIÓN DE INSTALACIÓN - INVENTORY PRO            ║" -ForegroundColor Cyan
Write-Host "╚══════════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# 1. Verificar Docker
Write-Host "📦 PASO 1: Verificando Docker..." -ForegroundColor Cyan
$dockerRunning = docker info 2>$null
if ($LASTEXITCODE -eq 0) {
    Write-Host "   ✅ Docker está corriendo" -ForegroundColor Green
} else {
    Write-Host "   ❌ Docker NO está corriendo. Inicia Docker Desktop." -ForegroundColor Red
    exit 1
}

# 2. Verificar contenedores
Write-Host ""
Write-Host "🐳 PASO 2: Verificando contenedores..." -ForegroundColor Cyan
cd $PSScriptRoot\docker
$containers = docker-compose ps --format json 2>$null | ConvertFrom-Json
if ($containers) {
    Write-Host "   Contenedores activos:" -ForegroundColor Green
    $containers | ForEach-Object {
        $status = if ($_.State -eq "running") { "🟢" } else { "🔴" }
        Write-Host "   $status $($_.Name) - $($_.State)" -ForegroundColor Gray
    }
} else {
    Write-Host "   ❌ No hay contenedores corriendo. Ejecuta: docker-compose up -d" -ForegroundColor Red
}

# 3. Verificar puertos
Write-Host ""
Write-Host "🔌 PASO 3: Verificando puertos..." -ForegroundColor Cyan
$ports = @(80, 5173, 8000, 5432, 6379)
$names = @("Nginx", "Frontend", "API", "PostgreSQL", "Redis")
for ($i = 0; $i -lt $ports.Count; $i++) {
    Test-Port $ports[$i] $names[$i] | Out-Null
}

# 4. Probar endpoints
Write-Host ""
Write-Host "🌐 PASO 4: Probando endpoints..." -ForegroundColor Cyan
$health = Test-Endpoint "http://localhost:8000/api/health" "API Health"
$login = Test-Endpoint "http://localhost:8000/api/login" "API Login"

# 5. Probar autenticación
Write-Host ""
Write-Host "🔐 PASO 5: Probando autenticación..." -ForegroundColor Cyan
try {
    $body = @{email = "admin@example.com"; password = "password"} | ConvertTo-Json
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/login" -Method POST -Body $body -ContentType "application/json" -TimeoutSec 5
    Write-Host "   ✅ Login exitoso!" -ForegroundColor Green
    Write-Host "   Token recibido: $($response.token.Substring(0, 20))..." -ForegroundColor Gray
    
    # Probar /me con token
    $meResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/me" -Headers @{"Authorization" = "Bearer $($response.token)"} -TimeoutSec 5
    Write-Host "   ✅ Endpoint /me funciona!" -ForegroundColor Green
    Write-Host "   Usuario: $($meResponse.user.first_name) $($meResponse.user.last_name)" -ForegroundColor Gray
} catch {
    Write-Host "   ❌ Error de autenticación: $($_.Exception.Message)" -ForegroundColor Red
}

# 6. Verificar frontend
Write-Host ""
Write-Host "💻 PASO 6: Verificando frontend..." -ForegroundColor Cyan
try {
    $response = Invoke-WebRequest -Uri "http://localhost:5173" -TimeoutSec 5 -UseBasicParsing
    if ($response.StatusCode -eq 200) {
        Write-Host "   ✅ Frontend responde correctamente" -ForegroundColor Green
    } else {
        Write-Host "   ⚠️ Frontend responde con código: $($response.StatusCode)" -ForegroundColor Yellow
    }
} catch {
    Write-Host "   ❌ Frontend no responde. Verifica que 'npm run dev' esté corriendo" -ForegroundColor Red
}

# Resumen
Write-Host ""
Write-Host "╔══════════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║                        RESUMEN FINAL                             ║" -ForegroundColor Cyan
Write-Host "╠══════════════════════════════════════════════════════════════════╣" -ForegroundColor Cyan
Write-Host "║  URLs de acceso:                                                 ║" -ForegroundColor White
Write-Host "║  🔗 Frontend:  http://localhost:5173                             ║" -ForegroundColor Yellow
Write-Host "║  🔗 API:       http://localhost:8000/api                         ║" -ForegroundColor Yellow
Write-Host "║  🔗 Health:    http://localhost:8000/api/health                  ║" -ForegroundColor Yellow
Write-Host "╠══════════════════════════════════════════════════════════════════╣" -ForegroundColor Cyan
Write-Host "║  Credenciales:                                                   ║" -ForegroundColor White
Write-Host "║  📧 Email:    admin@example.com                                  ║" -ForegroundColor Yellow
Write-Host "║  🔑 Password: password                                           ║" -ForegroundColor Yellow
Write-Host "╚══════════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""