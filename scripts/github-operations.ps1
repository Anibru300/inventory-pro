# =============================================================================
# SISTEMA DE OPERACIONES GITHUB - INVENTORY PRO
# =============================================================================

param(
    [Parameter(Mandatory=$false)]
    [ValidateSet("status", "branches", "actions")]
    [string]$Action = "status"
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

$GITHUB_TOKEN = $env:GITHUB_TOKEN
$GITHUB_USERNAME = $env:GITHUB_USERNAME
$headers = @{
    "Authorization" = "token $GITHUB_TOKEN"
    "Accept" = "application/vnd.github.v3+json"
}

function Write-Header($text) {
    Write-Host "`n==========================================" -ForegroundColor Cyan
    Write-Host $text -ForegroundColor Cyan
    Write-Host "==========================================" -ForegroundColor Cyan
}

# Ejecutar acción
switch ($Action) {
    "status" {
        Write-Header "ESTADO DEL REPOSITORIO"
        try {
            $response = Invoke-WebRequest -Uri "https://api.github.com/repos/$GITHUB_USERNAME/inventory-pro" -Headers $headers -UseBasicParsing
            $repo = $response.Content | ConvertFrom-Json
            Write-Host "📁 Repositorio: $($repo.full_name)" -ForegroundColor Green
            Write-Host "📝 Descripción: $($repo.description)" -ForegroundColor Gray
            Write-Host "⭐ Estrellas: $($repo.stargazers_count)" -ForegroundColor Yellow
            Write-Host "🌿 Branch default: $($repo.default_branch)" -ForegroundColor Cyan
            Write-Host "🔄 Último push: $($repo.pushed_at)" -ForegroundColor Gray
            Write-Host "🔗 URL: $($repo.html_url)" -ForegroundColor Blue
        } catch {
            Write-Error "Error: $_"
        }
    }
    
    "branches" {
        Write-Header "BRANCHES"
        try {
            $response = Invoke-WebRequest -Uri "https://api.github.com/repos/$GITHUB_USERNAME/inventory-pro/branches" -Headers $headers -UseBasicParsing
            $branches = $response.Content | ConvertFrom-Json
            $branches | Select-Object name, @{N="protected";E={$_.protected}} | Format-Table -AutoSize
        } catch {
            Write-Error "Error: $_"
        }
    }
    
    "actions" {
        Write-Header "GITHUB ACTIONS"
        try {
            $response = Invoke-WebRequest -Uri "https://api.github.com/repos/$GITHUB_USERNAME/inventory-pro/actions/runs?per_page=10" -Headers $headers -UseBasicParsing
            $runs = ($response.Content | ConvertFrom-Json).workflow_runs
            $runs | Select-Object name, status, conclusion, @{N="created";E={$_.created_at}} | Format-Table -AutoSize
        } catch {
            Write-Error "Error: $_"
        }
    }
    
    default {
        Write-Host "Uso: .\scripts\github-operations.ps1 -Action [status|branches|actions]" -ForegroundColor Yellow
    }
}
