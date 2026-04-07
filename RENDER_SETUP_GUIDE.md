# 🚀 GUÍA COMPLETA DE CONFIGURACIÓN EN RENDER

## Servicios Necesarios

Necesitas crear/configurar 3 servicios en Render:

1. **inventory-pro-api** (Web Service)
2. **inventory-pro-web** (Static Site)
3. **inventory-pro-landing** (Static Site)

---

## 1. INVENTORY-PRO-API (Backend)

### Tipo de Servicio
- **Type**: Web Service
- **Runtime**: Docker
- **Plan**: Free (o Starter si necesitas Shell)

### Configuración

#### Build Command
```bash
# Dejar en blanco (usa Dockerfile)
```

#### Start Command
```bash
# Dejar en blanco (usa Dockerfile)
```

#### Environment Variables
| Key | Value | Descripción |
|-----|-------|-------------|
| `APP_ENV` | `production` | Entorno de producción |
| `APP_KEY` | `base64:aca0d9abb108edcbe32e819ec2192e33` | Clave de la app |
| `APP_URL` | `https://inventory-pro-api-v2.onrender.com` | URL de la API |
| `FRONTEND_URL` | `https://inventory-pro-9ef8.onrender.com` | URL del frontend |
| `DB_CONNECTION` | `sqlite` | Base de datos SQLite |
| `DB_DATABASE` | `/var/www/html/database/database.sqlite` | Ruta de la BD |
| `STRIPE_KEY` | `sk_test_...` | Tu clave de Stripe (si usas pagos) |
| `STRIPE_WEBHOOK_SECRET` | `whsec_...` | Secreto de webhook de Stripe |

#### Dockerfile (ya configurado)
El Dockerfile debe estar en la raíz del proyecto `inventory-pro-api/`

---

## 2. INVENTORY-PRO-WEB (Frontend App)

### Tipo de Servicio
- **Type**: Static Site
- **Runtime**: Static

### Build Settings

#### Root Directory
```
10_CODIGO_FUENTE/inventory-pro-web
```

#### Build Command
```bash
npm install && npm run build
```

#### Publish Directory
```
dist
```

#### Environment Variables
| Key | Value |
|-----|-------|
| `VITE_API_URL` | `https://inventory-pro-api-v2.onrender.com/api` |
| `NODE_VERSION` | `20` |

---

## 3. INVENTORY-PRO-LANDING (Landing Page)

### Tipo de Servicio
- **Type**: Static Site
- **Runtime**: Static

### Build Settings

#### Root Directory
```
10_CODIGO_FUENTE/inventory-pro-landing
```

#### Build Command
```bash
npm install && npm run build
```

#### Publish Directory
```
dist
```

#### Environment Variables
| Key | Value |
|-----|-------|
| `NODE_VERSION` | `20` |

---

## 🔧 PASOS DE CONFIGURACIÓN DETALLADOS

### Para el API (inventory-pro-api):

1. Ve a https://dashboard.render.com
2. Haz clic en **"New +"** → **"Web Service"**
3. Conecta tu repositorio de GitHub
4. Configura:
   - **Name**: `inventory-pro-api`
   - **Runtime**: `Docker`
   - **Branch**: `main` (o la que uses)
   - **Root Directory**: `10_CODIGO_FUENTE/inventory-pro-api`
5. En **Advanced** → Add Environment Variables (los de arriba)
6. Haz clic en **"Create Web Service"**

### Para el Frontend (inventory-pro-web):

1. Haz clic en **"New +"** → **"Static Site"**
2. Conecta tu repositorio
3. Configura:
   - **Name**: `inventory-pro-web`
   - **Branch**: `main`
   - **Root Directory**: `10_CODIGO_FUENTE/inventory-pro-web`
   - **Build Command**: `npm install && npm run build`
   - **Publish Directory**: `dist`
4. Agrega la variable `VITE_API_URL`
5. Haz clic en **"Create Static Site"**

### Para la Landing (inventory-pro-landing):

1. Haz clic en **"New +"** → **"Static Site"**
2. Configura:
   - **Name**: `inventory-pro-landing`
   - **Root Directory**: `10_CODIGO_FUENTE/inventory-pro-landing`
   - **Build Command**: `npm install && npm run build`
   - **Publish Directory**: `dist`
3. Haz clic en **"Create Static Site"**

---

## ✅ VERIFICACIÓN POST-DEPLOY

### Verificar API
```bash
curl https://inventory-pro-api-v2.onrender.com/api/health
```
Debe retornar:
```json
{"status":"ok","timestamp":"...","version":"1.0.0"}
```

### Verificar Migraciones
```bash
curl https://inventory-pro-api-v2.onrender.com/api/migration-status
```

### Ejecutar Migraciones
```bash
curl https://inventory-pro-api-v2.onrender.com/api/run-migrations
```

---

## 🐛 SOLUCIÓN DE PROBLEMAS COMUNES

### Error: "composer.lock corrupt"
**Solución**: Eliminar `composer.lock` del repositorio y dejar que se regenere.

### Error: "No such file or directory"
**Solución**: Verificar que el **Root Directory** esté correcto.

### Error: "Module not found" en frontend
**Solución**: Verificar que `NODE_VERSION=20` esté configurado.

### Error: "CORS policy"
**Solución**: Verificar que `FRONTEND_URL` en el API coincida con la URL real del frontend.

---

## 📋 CHECKLIST FINAL

- [ ] Servicio `inventory-pro-api` creado (Docker)
- [ ] Servicio `inventory-pro-web` creado (Static)
- [ ] Servicio `inventory-pro-landing` creado (Static)
- [ ] Variables de entorno configuradas en API
- [ ] Variables de entorno configuradas en Web
- [ ] Deploy del API exitoso
- [ ] Health check responde OK
- [ ] Migraciones ejecutadas
- [ ] Deploy del Web exitoso
- [ ] Deploy de la Landing exitoso
