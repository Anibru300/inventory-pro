# Script de Instalación Automática - Render CLI
# Ejecutar como Administrador en PowerShell

param(
    [switch]$SkipLogin = $false
)

$ErrorActionPreference = "Stop"

function Write-Header($text) {
    Write-Host "`n==========================================" -ForegroundColor Cyan
    Write-Host $text -ForegroundColor Cyan
    Write-Host "==========================================" -ForegroundColor Cyan
}

function Write-Success($text) {
    Write-Host "✓ $text" -ForegroundColor Green
}

function Write-Error($text) {
    Write-Host "✗ $text" -ForegroundColor Red
}

function Write-Info($text) {
    Write-Host "→ $text" -ForegroundColor Yellow
}

Write-Header "INSTALADOR AUTOMÁTICO - RENDER CLI"

# Paso 1: Verificar si ya está instalado
Write-Header "PASO 1: Verificando instalación actual..."

try {
    $currentVersion = render --version 2>$null
    if ($currentVersion) {
        Write-Success "Render CLI ya está instalado: $currentVersion"
        $reinstall = Read-Host "¿Quieres reinstalar? (s/n)"
        if ($reinstall -ne 's') {
            Write-Info "Saltando instalación..."
            $SkipInstall = $true
        }
    }
} catch {
    Write-Info "Render CLI no está instalado. Procediendo con la instalación..."
    $SkipInstall = $false
}

# Paso 2: Descargar e instalar
if (-not $SkipInstall) {
    Write-Header "PASO 2: Descargando Render CLI..."
    
    $renderDir = "$env:LOCALAPPDATA\render"
    $zipPath = "$env:TEMP\render-cli.zip"
    $downloadUrl = "https://github.com/render-oss/cli/releases/latest/download/render_Windows_x86_64.zip"
    
    try {
        # Crear directorio
        if (-not (Test-Path $renderDir)) {
            New-Item -ItemType Directory -Path $renderDir -Force | Out-Null
        }
        
        # Descargar
        Write-Info "Descargando desde GitHub..."
        Invoke-WebRequest -Uri $downloadUrl -OutFile $zipPath -UseBasicParsing
        Write-Success "Descarga completada"
        
        # Extraer
        Write-Info "Extrayendo archivos..."
        Expand-Archive -Path $zipPath -DestinationPath $renderDir -Force
        Write-Success "Extracción completada"
        
        # Limpiar zip
        Remove-Item $zipPath -ErrorAction SilentlyContinue
        
        # Agregar al PATH
        Write-Info "Configurando PATH..."
        $currentPath = [Environment]::GetEnvironmentVariable("Path", "User")
        if ($currentPath -notlike "*$renderDir*") {
            [Environment]::SetEnvironmentVariable("Path", "$currentPath;$renderDir", "User")
            Write-Success "PATH actualizado"
        } else {
            Write-Success "PATH ya estaba configurado"
        }
        
        # Actualizar PATH en sesión actual
        $env:Path = [Environment]::GetEnvironmentVariable("Path", "User") + ";" + [Environment]::GetEnvironmentVariable("Path", "Machine")
        
        Write-Success "Instalación completada"
        
    } catch {
        Write-Error "Error durante la instalación: $_"
        Write-Error "Detalles: $($_.Exception.Message)"
        exit 1
    }
}

# Paso 3: Verificar instalación
Write-Header "PASO 3: Verificando instalación..."

try {
    # Refrescar PATH
    $env:Path = [Environment]::GetEnvironmentVariable("Path", "User") + ";" + [Environment]::GetEnvironmentVariable("Path", "Machine")
    
    $version = & "$env:LOCALAPPDATA\render\render.exe" --version 2>$null
    if ($version) {
        Write-Success "Render CLI instalado correctamente: $version"
    } else {
        throw "No se pudo verificar la versión"
    }
} catch {
    Write-Error "No se pudo verificar la instalación"
    Write-Info "Intentando verificación alternativa..."
    
    # Verificar si el archivo existe
    if (Test-Path "$env:LOCALAPPDATA\render\render.exe") {
        Write-Success "Archivo render.exe encontrado"
        Write-Info "Es posible que necesites reiniciar PowerShell para usar el comando 'render'"
    } else {
        Write-Error "No se encontró el archivo render.exe"
        exit 1
    }
}

# Paso 4: Login (opcional)
if (-not $SkipLogin) {
    Write-Header "PASO 4: Autenticación con Render"
    Write-Info "Se abrirá tu navegador para generar un token"
    Write-Info "Sigue estos pasos:"
    Write-Host "  1. Inicia sesión en Render si te lo pide" -ForegroundColor White
    Write-Host "  2. Click en 'Generate Token'" -ForegroundColor White
    Write-Host "  3. Copia el token y vuelve aquí" -ForegroundColor White
    Write-Host ""
    
    $doLogin = Read-Host "¿Quieres iniciar sesión ahora? (s/n)"
    
    if ($doLogin -eq 's') {
        try {
            & "$env:LOCALAPPDATA\render\render.exe" login
            Write-Success "Login completado"
        } catch {
            Write-Error "Error durante el login: $_"
            Write-Info "Puedes intentar manualmente más tarde con: render login"
        }
    } else {
        Write-Info "Saltando login. Puedes hacerlo después con: render login"
    }
}

# Paso 5: Crear archivo de configuración para MCP
Write-Header "PASO 5: Configurando MCP Server..."

$configDir = "$env:APPDATA\Claude"
$configFile = "$configDir\claude_desktop_config.json"

# Crear directorio si no existe
if (-not (Test-Path $configDir)) {
    New-Item -ItemType Directory -Path $configDir -Force | Out-Null
}

# Crear configuración inicial
$baseConfig = @{
    mcpServers = @{}
}

# Leer configuración existente si hay
if (Test-Path $configFile) {
    try {
        $existingContent = Get-Content $configFile -Raw | ConvertFrom-Json
        if ($existingContent.mcpServers) {
            $baseConfig.mcpServers = $existingContent.mcpServers
        }
        Write-Success "Configuración existente leída"
    } catch {
        Write-Info "Creando nueva configuración..."
    }
}

# Agregar Render MCP Server
$renderPath = "$env:LOCALAPPDATA\render\render.exe"
$baseConfig.mcpServers["render"] = @{
    command = $renderPath
    args = @("mcp", "serve")
}

# Guardar configuración
$baseConfig | ConvertTo-Json -Depth 10 | Set-Content $configFile
Write-Success "Configuración MCP guardada en: $configFile"

# Paso 6: Instrucciones finales
Write-Header "✅ INSTALACIÓN COMPLETADA"

Write-Host "`n📋 RESUMEN:" -ForegroundColor Green
Write-Host "  • Render CLI instalado en: $env:LOCALAPPDATA\render" -ForegroundColor White
Write-Host "  • Configuración MCP creada" -ForegroundColor White
Write-Host "  • PATH actualizado" -ForegroundColor White

Write-Host "`n🚀 PRÓXIMOS PASOS:" -ForegroundColor Yellow
Write-Host "  1. Cierra y vuelve a abrir PowerShell" -ForegroundColor White
Write-Host "  2. Reinicia Claude Desktop (si está abierto)" -ForegroundColor White
Write-Host "  3. Verifica la instalación ejecutando: render --version" -ForegroundColor White

Write-Host "`n📖 COMANDOS ÚTILES:" -ForegroundColor Cyan
Write-Host "  render --version              # Ver versión" -ForegroundColor Gray
Write-Host "  render login                  # Iniciar sesión" -ForegroundColor Gray
Write-Host "  render services list          # Ver tus servicios" -ForegroundColor Gray
Write-Host "  render logs --service inventory-pro-api-v2 --tail  # Ver logs" -ForegroundColor Gray

Write-Host "`n💡 IMPORTANTE:" -ForegroundColor Red
Write-Host "   Para que Kimi pueda ver los logs, necesitas:" -ForegroundColor White
Write-Host "   1. Generar un API Key en: https://dashboard.render.com/settings/api-keys" -ForegroundColor White
Write-Host "   2. Agregarlo a la configuración en: $configFile" -ForegroundColor White

Write-Host "`n"
Read-Host "Presiona ENTER para salir"
