# Configuración de PostgreSQL Gratuito en Render

## El Problema

En el tier gratuito de Render, SQLite se reinicia cada vez que el contenedor se redeploya (cada cierto tiempo o con cada push a git). Los Discos persistentes también requieren pago.

## La Solución: PostgreSQL Gratuito

Render ofrece **PostgreSQL gratuito** (con algunas limitaciones, pero persistente).

---

## Pasos para Configurar PostgreSQL

### 1. Crear Base de Datos PostgreSQL en Render

1. Ve a tu Dashboard de Render: https://dashboard.render.com
2. Haz click en **"New +"** → **"PostgreSQL"**
3. Configura:
   - **Name**: `inventory-pro-db`
   - **Database**: `inventory_pro`
   - **User**: `inventory_user`
   - **Region**: Selecciona la misma que tu servicio web
4. Haz click en **"Create Database"**
5. **Espera** a que se cree (toma unos minutos)

### 2. Obtener Variables de Conexión

Una vez creada, Render te mostrará la información de conexión:

```
Hostname: dpg-xxxxxxxxxxxxxxxxxxxx-a.oregon-postgres.render.com
Port: 5432
Database: inventory_pro
Username: inventory_user
Password: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
Internal Connection String: postgres://inventory_user:password@host:5432/inventory_pro
External Connection String: postgres://inventory_user:password@host:5432/inventory_pro
```

**IMPORTANTE**: Guarda el "External Connection String"

### 3. Configurar Variables de Entorno en el Servicio Web

1. Ve a tu servicio web en Render: `inventory-pro-api-v3`
2. Haz click en **"Environment"**
3. Agrega estas variables:

```
DB_CONNECTION=pgsql
DB_HOST=dpg-xxxxxxxxxxxxxxxxxxxx-a.oregon-postgres.render.com
DB_PORT=5432
DB_DATABASE=inventory_pro
DB_USERNAME=inventory_user
DB_PASSWORD=tu-password-aqui
```

(Reemplaza con los valores reales de tu base de datos)

### 4. Redeployar el Servicio

1. Haz click en **"Manual Deploy"** → **"Clear build cache & deploy"**
2. Espera a que termine el deploy
3. La base de datos se migrará automáticamente

---

## Ventajas de PostgreSQL vs SQLite

| Característica | SQLite | PostgreSQL |
|----------------|--------|------------|
| Persistencia en Render Gratuito | ❌ No | ✅ Sí |
| Concurrencia | Limitada | Alta |
| Backups automáticos | ❌ No | ✅ Sí (diarios) |
| Escalabilidad | Baja | Alta |
| Precio en Render | Gratis | Gratis (con límites) |

---

## Límites del PostgreSQL Gratuito de Render

- **Storage**: 1 GB
- **Connections**: 97 concurrentes
- **Backups**: Automáticos diarios con 7 días de retención
- **CPU/Memory**: Compartida

Para un sistema de inventario pequeño/mediano, ¡es más que suficiente!

---

## Solución Temporal (Mientras Configuras PostgreSQL)

Si necesitas usar el sistema AHORA y no puedes configurar PostgreSQL todavía:

1. **Usa la cuenta por defecto**:
   - Email: `admin@inventorypro.com`
   - Password: `password`

2. **Entiende que los datos se perderán** en cada redeploy del backend

3. **Guarda tus datos importantes** exportándolos antes de cerrar sesión

---

## ¿Necesitas Ayuda?

Si tienes problemas para configurar PostgreSQL, los errores más comunes son:

1. **Error de conexión**: Verifica que las variables de entorno estén correctas
2. **"database does not exist"**: La base de datos tarda unos minutos en crearse
3. **SSL requerido**: Render PostgreSQL requiere SSL, Laravel lo maneja automáticamente

---

**Recomendación**: Configura PostgreSQL lo antes posible para tener datos persistentes.
