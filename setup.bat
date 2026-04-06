@echo off
chcp 65001 >nul
echo.
echo ╔══════════════════════════════════════════════════════════════╗
echo ║           INVENTORY PRO - Setup Script                       ║
echo ╚══════════════════════════════════════════════════════════════╝
echo.

:: Check Docker
echo [1/6] Verificando Docker...
docker --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker no esta instalado
    echo Por favor instala Docker Desktop desde: https://www.docker.com/products/docker-desktop
    pause
    exit /b 1
)
echo [OK] Docker encontrado

:: Change to docker directory
echo.
echo [2/6] Iniciando servicios Docker...
cd docker
docker-compose down --remove-orphans 2>nul
docker-compose up -d --build
if errorlevel 1 (
    echo [ERROR] No se pudieron iniciar los servicios
    pause
    exit /b 1
)
echo [OK] Servicios iniciados

:: Wait for PostgreSQL
echo.
echo [3/6] Esperando PostgreSQL...
timeout /t 10 /nobreak >nul
echo [OK] PostgreSQL listo

:: Install backend dependencies
echo.
echo [4/6] Instalando dependencias del backend...
docker-compose exec -T api composer install --no-interaction
if errorlevel 1 (
    echo [ADVERTENCIA] No se pudieron instalar dependencias automaticamente
    echo Ejecuta manualmente: docker-compose exec api composer install
)
echo [OK] Dependencias instaladas

:: Setup environment
echo.
echo [5/6] Configurando entorno...
if not exist "..\inventory-pro-api\.env" (
    copy "..\inventory-pro-api\.env.example" "..\inventory-pro-api\.env"
    docker-compose exec -T api php artisan key:generate
    echo [OK] Archivo .env creado
) else (
    echo [OK] Archivo .env ya existe
)

:: Run migrations
echo.
echo [6/6] Ejecutando migraciones...
docker-compose exec -T api php artisan migrate:fresh --seed --force
if errorlevel 1 (
    echo [ERROR] Error en migraciones
    pause
    exit /b 1
)
echo [OK] Migraciones completadas

:: Install frontend
echo.
echo [BONUS] Instalando frontend...
cd ..\inventory-pro-web
call npm install

:: Done
cd ..
echo.
echo ╔══════════════════════════════════════════════════════════════╗
echo ║                   ¡SETUP COMPLETADO!                         ║
echo ╠══════════════════════════════════════════════════════════════╣
echo ║  Frontend:   http://localhost:5173                           ║
echo ║  API:        http://localhost:8000/api                       ║
echo ║  Health:     http://localhost:8000/api/health                ║
echo ╠══════════════════════════════════════════════════════════════╣
echo ║  Login:      admin@example.com                               ║
echo ║  Password:   password                                        ║
echo ╚══════════════════════════════════════════════════════════════╝
echo.
echo Para iniciar el frontend manualmente:
echo   cd inventory-pro-web ^&^& npm run dev
echo.
pause