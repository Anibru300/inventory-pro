# Script para configurar API Key de Render
$ErrorActionPreference = "Stop"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "CONFIGURACIÓN DE API KEY - RENDER" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

Write-Host "`n📋 INSTRUCCIONES:" -ForegroundColor Yellow
Write-Host "  1. Ve a: https://dashboard.render.com/settings/api-keys" -ForegroundColor White
Write-Host "  2. Click en 'Create API Key'" -ForegroundColor White
Write-Host "  3. Copia la key (empieza con 'rnd_')" -ForegroundColor White
Write-Host "  4. Pégala aquí cuando te la pida" -ForegroundColor White
Write-Host ""

# Pedir API Key (oculta)
$apiKey = Read-Host -Prompt "Pega tu API Key de Render" -AsSecureString
$plainApiKey = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($apiKey))

# Validar formato
if (-not ($plainApiKey -match "^rnd_")) {
    Write-Host "⚠️  Advertencia: La API Key debería empezar con 'rnd_'" -ForegroundColor Yellow
    $continuar = Read-Host "¿Continuar de todos modos? (s/n)"
    if ($continuar -ne 's') {
        exit
    }
}

# Leer configuración existente
$configFile = "$env:APPDATA\Claude\claude_desktop_config.json"

if (-not (Test-Path $configFile)) {
    Write-Host "❌ No se encontró configuración de Claude" -ForegroundColor Red
    Write-Host "Ejecuta primero: .\instalar-render-cli.ps1" -ForegroundColor Yellow
    exit 1
}

$config = Get-Content $configFile -Raw | ConvertFrom-Json

# Agregar API Key al servidor de Render
if (-not $config.mcpServers) {
    $config | Add-Member -NotePropertyName mcpServers -NotePropertyValue @{} -Force
}

if (-not $config.mcpServers.render) {
    Write-Host "❌ No se encontró configuración de Render MCP" -ForegroundColor Red
    exit 1
}

if (-not $config.mcpServers.render.env) {
    $config.mcpServers.render | Add-Member -NotePropertyName env -NotePropertyValue @{} -Force
}

$config.mcpServers.render.env["RENDER_API_KEY"] = $plainApiKey

# Guardar
$config | ConvertTo-Json -Depth 10 | Set-Content $configFile

Write-Host "`n✅ API Key configurada correctamente" -ForegroundColor Green
Write-Host "`n🔄 Reinicia Claude Desktop para aplicar los cambios" -ForegroundColor Yellow

# Limpiar variable
$plainApiKey = $null
