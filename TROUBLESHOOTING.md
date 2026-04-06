# 🔧 Guía de Troubleshooting - Inventory Pro

## Errores Comunes y Soluciones

---

### ❌ Error: "Authorization failed" / "Unauthenticated"

**Causa:** Problema con tokens de Sanctum

**Solución:**
```bash
# 1. Limpiar caché
cd docker
docker-compose exec api php artisan config:clear
docker-compose exec api php artisan cache:clear

# 2. Verificar tablas de Sanctum
docker-compose exec api php artisan migrate:status

# 3. Si falta, ejecutar migración
docker-compose exec api php artisan migrate
```

---

### ❌ Error: "Connection refused" al conectar a la API

**Causa:** El contenedor API no está corriendo o hay problema de red

**Solución:**
```bash
# Verificar estado
cd docker
docker-compose ps

# Reiniciar servicios
docker-compose restart api

# Ver logs
docker-compose logs api
```

---

### ❌ Error: "SQLSTATE[08006] [7] could not connect to server"

**Causa:** PostgreSQL no está listo o configuración incorrecta

**Solución:**
```bash
# Verificar que PostgreSQL esté corriendo
cd docker
docker-compose ps postgres

# Ver logs
docker-compose logs postgres

# Esperar a que esté listo
docker-compose exec postgres pg_isready -U inventory_user

# Si no responde, reiniciar
docker-compose restart postgres
```

---

### ❌ Error: CORS - "No 'Access-Control-Allow-Origin' header"

**Causa:** Configuración CORS incorrecta

**Solución:**
```bash
# Verificar configuración
cd docker
docker-compose exec api cat config/cors.php

# Limpiar caché
docker-compose exec api php artisan config:clear
```

Verificar que `config/cors.php` tenga:
```php
'allowed_origins' => [
    'http://localhost:5173',
    'http://localhost:3000',
],
```

---

### ❌ Error: "Class 'App\Models\User' not found"

**Causa:** Autoload no actualizado

**Solución:**
```bash
cd docker
docker-compose exec api composer dump-autoload
```

---

### ❌ Error: "Route [login] not defined"

**Causa:** Rutas web no configuradas o middleware auth redirect

**Solución:**
```bash
# Verificar rutas
docker-compose exec api php artisan route:list

# Limpiar caché de rutas
docker-compose exec api php artisan route:clear
```

---

### ❌ Error: "The POST method is not supported for route login"

**Causa:** La ruta espera web pero el frontend usa API

**Verificación:**
Asegúrate de que el frontend use la URL correcta:
```javascript
// Correcto
const API_URL = 'http://localhost:8000/api'

// Incorrecto
const API_URL = 'http://localhost:8000'  // Sin /api
```

---

### ❌ Error: "SQLSTATE[42P01]: Undefined table"

**Causa:** Tablas no creadas

**Solución:**
```bash
cd docker
docker-compose exec api php artisan migrate:fresh --seed
```

---

### ❌ Error: "Unable to create lockable file"

**Causa:** Problemas de permisos en storage

**Solución:**
```bash
cd docker
docker-compose exec api chmod -R 777 storage bootstrap/cache
```

---

### ❌ Error: "Redis connection refused"

**Causa:** Redis no está corriendo

**Solución:**
```bash
cd docker
docker-compose up -d redis
docker-compose logs redis
```

O cambiar a file driver temporalmente en `.env`:
```env
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

---

## 🔍 Comandos de Diagnóstico

### Verificar estado de todos los servicios
```bash
cd docker
docker-compose ps
```

### Ver logs en tiempo real
```bash
# Todos los servicios
docker-compose logs -f

# Solo API
docker-compose logs -f api

# Solo PostgreSQL
docker-compose logs -f postgres
```

### Probar API desde consola
```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# Ver usuario actual (requiere token)
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer TU_TOKEN_AQUI"
```

### Entrar a contenedores
```bash
# Contenedor API (Laravel)
docker-compose exec api bash

# Contenedor PostgreSQL
docker-compose exec postgres psql -U inventory_user -d inventory_pro

# Contenedor Redis
docker-compose exec redis redis-cli
```

---

## 🔄 Reset Completo

Si todo falla, hacer reset completo:

```bash
cd docker

# 1. Detener todo
docker-compose down -v

# 2. Eliminar volúmenes (⚠️ Borra datos)
docker volume prune -f

# 3. Reconstruir
docker-compose up -d --build

# 4. Reinstalar dependencias
docker-compose exec api composer install

# 5. Configurar
docker-compose exec api cp .env.example .env
docker-compose exec api php artisan key:generate

# 6. Migrar
docker-compose exec api php artisan migrate:fresh --seed

# 7. Frontend
cd ../inventory-pro-web
npm install
npm run dev
```

---

## 📞 Soporte

Si el problema persiste:

1. Guarda los logs: `docker-compose logs > logs.txt`
2. Verifica versión de Docker: `docker version`
3. Verifica versión de Docker Compose: `docker compose version`
4. Comparte el archivo `logs.txt` con el equipo de soporte

---

*Última actualización: 2026-04-06*