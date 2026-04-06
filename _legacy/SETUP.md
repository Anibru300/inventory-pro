# 🚀 Guía de Instalación - Inventory Pro

## Requisitos Previos

- **Docker Desktop** 4.0+ ([Descargar](https://www.docker.com/products/docker-desktop))
- **Git** (opcional, para clonar)
- **PowerShell** (Windows) o **Terminal** (Mac/Linux)
- Puertos libres: `80`, `5173`, `8000`, `5432`, `6379`

---

## ⚡ Instalación Rápida (Automática)

### Windows (PowerShell)

```powershell
# Ir al directorio del proyecto
cd "10_CODIGO_FUENTE"

# Ejecutar script de instalación
.\setup-complete.ps1
```

### Mac/Linux (Bash)

```bash
cd 10_CODIGO_FUENTE
chmod +x setup-complete.sh
./setup-complete.sh
```

---

## 🔧 Instalación Manual (Paso a Paso)

Si el script automático falla, sigue estos pasos:

### Paso 1: Iniciar Infraestructura

```bash
cd docker
docker-compose up -d
```

### Paso 2: Instalar Dependencias Backend

```bash
# Esperar 10 segundos a que PostgreSQL inicie
sleep 10

# Instalar Composer
docker-compose exec api composer install --no-interaction

# Configurar entorno
docker-compose exec api cp .env.example .env
docker-compose exec api php artisan key:generate
```

### Paso 3: Configurar Base de Datos

```bash
# Ejecutar migraciones y seeders
docker-compose exec api php artisan migrate:fresh --seed

# Optimizar
docker-compose exec api php artisan config:cache
docker-compose exec api php artisan route:cache
```

### Paso 4: Instalar Frontend

```bash
cd ../inventory-pro-web
npm install
npm run dev
```

---

## ✅ Verificación

### 1. Verificar Health Check

Abre navegador y visita:
```
http://localhost:8000/api/health
```

Deberías ver:
```json
{
  "status": "ok",
  "timestamp": "2026-04-06T12:00:00.000000Z",
  "version": "1.0.0"
}
```

### 2. Probar Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

Respuesta esperada:
```json
{
  "user": { ... },
  "token": "1|xxxxxxxxxxxx"
}
```

### 3. Acceder al Frontend

Abre: http://localhost:5173

Credenciales:
- **Email:** `admin@example.com`
- **Password:** `password`

---

## 📂 Estructura de URLs

| Servicio | URL | Descripción |
|----------|-----|-------------|
| Frontend App | http://localhost:5173 | Aplicación Vue.js |
| Landing Page | http://localhost:3000 | Marketing site (Next.js) |
| API | http://localhost:8000/api | Backend Laravel |
| API Health | http://localhost:8000/api/health | Verificación de estado |
| PostgreSQL | localhost:5432 | Base de datos |
| Redis | localhost:6379 | Cache/Sesiones |

### Credenciales Base de Datos

```
Host:     localhost
Port:     5432
Database: inventory_pro
Username: inventory_user
Password: inventory_pass_2024
```

---

## 🛠️ Comandos Útiles

### Docker Compose

```bash
# Ver estado de servicios
docker-compose ps

# Ver logs
docker-compose logs -f api        # Logs del API
docker-compose logs -f web        # Logs del frontend
docker-compose logs -f postgres   # Logs de PostgreSQL

# Reiniciar servicio
docker-compose restart api

# Detener todo
docker-compose down

# Detener y eliminar volúmenes (⚠️ borra datos)
docker-compose down -v
```

### Laravel (dentro del contenedor)

```bash
# Entrar al contenedor
docker-compose exec api bash

# Comandos Artisan
php artisan migrate:fresh --seed  # Reset DB con datos
php artisan config:clear          # Limpiar caché config
php artisan cache:clear           # Limpiar caché
php artisan route:list            # Ver rutas
php artisan tinker                # Consola interactiva

# Composer
composer install                  # Instalar dependencias
composer dump-autoload            # Recargar autoload

# Tests
php artisan test                  # Ejecutar tests
```

### Frontend

```bash
cd inventory-pro-web

npm install       # Instalar dependencias
npm run dev       # Modo desarrollo
npm run build     # Build para producción
npm run preview   # Previsualizar build
```

---

## 🔥 Solución de Problemas

### "Authorization failed"

```bash
# Limpiar caché
docker-compose exec api php artisan config:clear
docker-compose exec api php artisan cache:clear
```

### "Connection refused"

```bash
# Verificar que servicios estén corriendo
docker-compose ps

# Reiniciar
docker-compose restart
```

### Error de migraciones

```bash
# Reset completo
docker-compose exec api php artisan migrate:fresh --seed
```

Para más problemas, ver [TROUBLESHOOTING.md](TROUBLESHOOTING.md)

---

## 📊 Datos de Prueba

Después de ejecutar `migrate:fresh --seed`, se crean automáticamente:

### Usuarios
| Email | Password | Rol |
|-------|----------|-----|
| admin@example.com | password | Admin |
| user@example.com | password | Usuario |

### Tenant
- **Nombre:** Empresa Demo S.A.
- **Plan:** Pro
- **Estado:** Activo

### Almacén
- **Nombre:** Almacén Principal
- **Código:** ALM-01

### Categorías
- Electrónicos
- Ropa
- Alimentos
- Hogar
- Deportes

---

## 🔄 Actualizar Proyecto

```bash
# Backend
docker-compose exec api composer update

# Frontend
cd inventory-pro-web
npm update
```

---

## 🛑 Detener Proyecto

```bash
cd docker

# Detener (mantiene datos)
docker-compose down

# Detener y eliminar todo (⚠️)
docker-compose down -v
```

---

## 📞 Soporte

Si encuentras problemas:

1. Revisa [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
2. Verifica logs: `docker-compose logs -f`
3. Contacta al equipo de desarrollo

---

¡Listo! 🎉 Tu sistema Inventory Pro debería estar funcionando.