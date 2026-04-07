# Guía de Configuración para Render

## OPCIÓN A: Usar Blueprint (Recomendado)

### Paso 1: Preparar el repositorio

1. Sube todos los cambios a GitHub:
```bash
git add .
git commit -m "Config: Add Render deployment files"
git push
```

### Paso 2: Crear Blueprint en Render

1. Ve a https://dashboard.render.com/blueprints
2. Click en **"New Blueprint Instance"**
3. Conecta tu repositorio de GitHub
4. Render detectará automáticamente el archivo `render.yaml`
5. Revisa la configuración y click en **"Apply"**

---

## OPCIÓN B: Configuración Manual (Si Blueprint falla)

### Servicio 1: inventory-pro-api-v2

1. Ve a https://dashboard.render.com
2. Encuentra el servicio `inventory-pro-api-v2`
3. Ve a **Settings** (pestaña superior)
4. Configura estos valores:

#### Build & Deploy
- **Runtime**: Docker
- **Dockerfile Path**: `./Dockerfile.render`
- **Docker Build Context Directory**: `10_CODIGO_FUENTE/inventory-pro-api`

#### Environment Variables
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:aca0d9abb108edcbe32e819ec2192e33
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
LOG_CHANNEL=stderr
```

#### Disk (para SQLite)
- **Mount Path**: `/var/www/html/database`
- **Size**: 1 GB

5. Click en **Save Changes**

### Servicio 2: inventory-pro-web

1. Ve al servicio `inventory-pro-web`
2. Ve a **Settings**

#### Build & Deploy
- **Runtime**: Static Site
- **Build Command**: `npm install && npm run build`
- **Publish Directory**: `dist`

#### Environment Variables
```
VITE_API_URL=https://inventory-pro-api-v2.onrender.com/api
```

3. Click en **Save Changes**

### Servicio 3: inventory-pro-landing

1. Ve al servicio `inventory-pro-landing`
2. Ve a **Settings**

#### Build & Deploy
- **Runtime**: Static Site
- **Build Command**: `npm install && npm run build`
- **Publish Directory**: `dist`

#### Environment Variables
```
NODE_VERSION=20
```

3. Click en **Save Changes**

---

## Paso 3: Ejecutar Migraciones

Como no tienes acceso a Shell en el plan gratuito, usa el endpoint temporal:

1. Espera a que el API esté desplegado
2. Visita en tu navegador:
   ```
   https://inventory-pro-api-v2.onrender.com/api/run-migrations
   ```

3. Deberías ver:
   ```json
   {
     "success": true,
     "message": "Migraciones ejecutadas"
   }
   ```

---

## Paso 4: Verificar URLs

Después del despliegue, verifica que todo funcione:

| Servicio | URL a verificar |
|----------|-----------------|
| API Health | https://inventory-pro-api-v2.onrender.com/api/health |
| Web App | https://inventory-pro-9ef8.onrender.com |
| Landing | https://inventory-pro-landing.onrender.com |

---

## Solución de Problemas

### Error: "composer.lock corrupt"
**Solución**: Eliminar `composer.lock` del repositorio
```bash
git rm 10_CODIGO_FUENTE/inventory-pro-api/composer.lock
git commit -m "Remove composer.lock"
git push
```

### Error: "Cannot find module"
**Solución**: Verificar que `node_modules` esté en `.gitignore`

### Error: "Database is locked"
**Solución**: Verificar que el Disk esté configurado en el servicio

### Build tarda mucho
**Solución**: Es normal en el primer build. Toma 5-10 minutos.

---

## Comandos Útiles para Diagnóstico

Desde tu computadora, puedes verificar el estado:

```bash
# Verificar API
curl https://inventory-pro-api-v2.onrender.com/api/health

# Verificar migraciones
curl https://inventory-pro-api-v2.onrender.com/api/migration-status

# Ejecutar migraciones
curl https://inventory-pro-api-v2.onrender.com/api/run-migrations
```

---

## Contacto

Si sigues teniendo problemas, revisa los logs en:
https://dashboard.render.com → [Tu Servicio] → Logs
