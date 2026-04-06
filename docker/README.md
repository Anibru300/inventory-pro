# Docker Infrastructure

Configuración Docker para el stack completo de Inventory Pro.

## Servicios

| Servicio | Imagen | Puerto | Descripción |
|----------|--------|--------|-------------|
| app | PHP 8.3-FPM | - | Backend Laravel |
| postgres | PostgreSQL 15 | 5432 | Base de datos con RLS |
| redis | Redis 7 | 6379 | Cache y sesiones |
| nginx | Nginx Alpine | 80 | Reverse proxy |

## Comandos Útiles

```bash
# Iniciar todos los servicios
docker-compose up -d

# Ver logs
docker-compose logs -f

# Reconstruir imágenes
docker-compose up -d --build

# Ejecutar comandos en el contenedor app
docker-compose exec app bash
docker-compose exec app php artisan migrate
docker-compose exec app composer install

# Backup de base de datos
docker-compose exec postgres pg_dump -U postgres inventory_pro > backup.sql

# Restaurar base de datos
docker-compose exec -T postgres psql -U postgres inventory_pro < backup.sql
```

## Volumenes

- `postgres_data`: Datos persistentes de PostgreSQL
- `redis_data`: Datos de Redis
- `./inventory-pro-api:/var/www`: Código fuente (desarrollo)

## Red

Todos los servicios están conectados a la red `inventory-pro-network`.