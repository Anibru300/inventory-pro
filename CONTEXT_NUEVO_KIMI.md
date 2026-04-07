# 🚀 CONTEXTO PARA NUEVO ASISTENTE

> **LEER PRIMERO:** Este archivo es para que el nuevo Kimi entienda el contexto rápidamente.

## 📍 ESTADO ACTUAL (Resumen ejecutivo)

**Proyecto:** Inventory Pro - Sistema de gestión de inventarios para CJ Consultoría  
**Estado:** Funcional pero con errores 422 en productos  
**Deploy:** Activo en Render (GitHub Actions auto-deploy)  

---

## 🎯 PROBLEMAS ACTUALES (Arreglar primero mañana)

### 1. Error 422 al crear productos
```javascript
// Error en consola:
POST https://inventory-pro-api-v2.onrender.com/api/products 422
```
**Archivos a revisar:**
- `inventory-pro-api/app/Http/Controllers/Api/ProductController.php` (método store)
- `inventory-pro-web/src/views/products/ProductForm.vue` (campos del form)
- `inventory-pro-web/src/stores/products.js` (createProduct)

**Posible causa:** Falta `tenant_id` o campos requeridos no se envían correctamente.

### 2. Dashboard 404
```javascript
GET /api/dashboard 404
```
**Archivos:**
- `inventory-pro-api/routes/api.php` (verificar ruta existe)
- `inventory-pro-api/app/Http/Controllers/Api/DashboardController.php` (métodos index/stats)

---

## 📁 ESTRUCTURA DE ARCHIVOS CLAVE

### Backend (PHP Laravel):
```
inventory-pro-api/
├── app/Http/Controllers/Api/
│   ├── AuthController.php          ← Login/Register/Logout
│   ├── ProductController.php       ← CRUD productos (revisar store)
│   ├── DashboardController.php     ← Stats (revisar rutas)
│   └── ...
├── database/seeders/TenantSeeder.php  ← Se ejecuta al registrar usuario
└── routes/api.php                  ← Todas las rutas API
```

### Frontend (Vue 3):
```
inventory-pro-web/src/
├── services/api.js                 ← ✅ Axios configurado con auth
├── stores/
│   ├── auth.js                     ← Login/register con token
│   └── products.js                 ← CRUD productos
├── views/
│   ├── products/ProductForm.vue    ← Form de producto (revisar campos)
│   └── dashboard/Dashboard.vue     ← Llama a /api/dashboard
└── router/index.js                 ← Rutas completas
```

---

## 🔧 CONFIGURACIÓN TÉCNICA

### Backend:
- **Framework:** Laravel 11
- **Auth:** Sanctum tokens
- **DB:** SQLite (Render free tier)
- **Multi-tenant:** Por `tenant_id` en cada modelo
- **Seeders:** `TenantSeeder` crea almacén + categorías al registrar

### Frontend:
- **Framework:** Vue 3 + Composition API
- **Build:** Vite
- **CSS:** Tailwind con colores personalizados CJ
- **HTTP:** Axios (instancia en `services/api.js`)
- **State:** Pinia stores

### Deploy:
- **Frontend:** Render Static Site (Auto-deploy desde GitHub)
- **Backend:** Render Docker (Dockerfile con PHP 8.3 + Apache)
- **Repo:** https://github.com/Anibru300/inventory-pro
- **Branch:** master

---

## 🎨 DISEÑO CJ CONSULTORÍA (NO CAMBIAR)

### Colores principales:
```javascript
// tailwind.config.js
colors: {
  'cj-navy': '#0a1628',      // Fondo
  'cj-gold': '#c9a962',      // Botones primarios, acentos
  'cj-silver': '#e8e8e8',    // Texto secundario
  'cj-electric': '#2563eb',  // Links, focus
}
```

### Tipografías:
- Títulos: `Montserrat`
- Cuerpo: `Open Sans`
- Lema/taglines: `Playfair Display Italic`

### Componentes visuales:
- Cards: `card-premium` (glassmorphism + borde sutil)
- Botones primarios: `btn-primary` (fondo dorado)
- Logo: Lobo dorado en `/public/logo-lobo.png`

---

## 📝 TAREAS PENDIENTES (En orden de prioridad)

### 1. CRÍTICO - Arreglar errores:
- [ ] Revisar validación en `ProductController@store`
- [ ] Asegurar que `tenant_id` se asigne automáticamente
- [ ] Verificar ruta `/api/dashboard` existe

### 2. IMPORTANTE - Funcionalidad:
- [ ] Probar creación de movimientos (entrada/salida)
- [ ] Verificar que stock se actualice correctamente
- [ ] Crear página de importación masiva (Excel)

### 3. MEJORAS:
- [ ] Implementar reportes backend
- [ ] Generar PDF de vales de salida
- [ ] Agregar filtros avanzados

---

## 🔍 DEBUG RÁPIDO

### Si el frontend no conecta al backend:
1. Revisar `inventory-pro-web/src/services/api.js`:
   ```javascript
   const API_URL = 'https://inventory-pro-api-v2.onrender.com/api'
   ```

2. Revisar CORS en `inventory-pro-api/config/cors.php`:
   ```php
   'allowed_origins' => [
       'https://inventory-pro-9ef8.onrender.com',
       'https://*.onrender.com',
   ],
   ```

### Si hay error 401 (No autorizado):
1. Revisar que `api.js` envíe header:
   ```javascript
   headers.Authorization = `Bearer ${token}`
   ```

2. Verificar token en localStorage del navegador

### Si hay error 422 (Validación):
1. Revisar campos requeridos en el formulario
2. Revisar reglas de validación en el controller
3. Verificar que `tenant_id` se esté enviando/asignando

---

## 💾 BASE DE DATOS

### Tablas principales:
- `tenants` - Empresas/tenant
- `users` - Usuarios (con `tenant_id`)
- `products` - Productos (con `tenant_id`, `category_id`, `warehouse_id`)
- `categories` - Categorías (con `tenant_id`)
- `warehouses` - Almacenes (con `tenant_id`)
- `stock_movements` - Movimientos (con `tenant_id`, `product_id`, `warehouse_id`)

### Seeders automáticos (al registrar usuario):
```php
// TenantSeeder.php
crea: Almacén Principal + 10 categorías por defecto
```

---

## 🆘 COMANDOS ÚTILES

### Subir cambios:
```bash
cd "C:\Users\Carlos\OneDrive\Escritorio\CJ CONSULTORIA\PROYECTO ONTROL DE INVENTARIOS\10_CODIGO_FUENTE"
git add .
git commit -m "Descripción del cambio"
git push
```

### Ver estado:
```bash
git status
git log --oneline -5
```

---

## 📞 DATOS DE CONTACTO/PROYECTO

- **Cliente:** CJ Consultoría
- **Eslogan:** "Transformamos procesos en resultados sostenibles"
- **Logo:** Lobo estilizado dorado (archivo: `logo-lobo.png`)
- **Colores corporativos:** Azul marino + Dorado + Plata

---

## ✅ CHECKLIST INICIO DE SESIÓN

Copiar y pegar esto en la conversación del nuevo Kimi:

```
CONTEXT: Estamos trabajando en Inventory Pro para CJ Consultoría.
URLs: Frontend https://inventory-pro-9ef8.onrender.com, Backend https://inventory-pro-api-v2.onrender.com
Estado: Sistema funcional con errores 422 al crear productos y 404 en dashboard.
Prioridad: Arreglar errores de validación primero.
Diseño: NO CAMBIAR - Colores dorado/plata/azul marino de CJ Consultoría.
```

---

**Archivo creado:** 07 de Abril, 2026 - 1:00 AM
