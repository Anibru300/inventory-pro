# 📋 Inventory Pro - Contexto del Proyecto

> **Documento de referencia para continuación del desarrollo**
> **Última actualización:** 8 de abril de 2026
> **Estado:** Landing page lista, backend funcionando, pagos por transferencia activos

---

## 🌐 URLs de Producción

| Servicio | URL | Estado |
|----------|-----|--------|
| **Landing Page (Frontend)** | https://inventory-pro-z81e.onrender.com/ | 🟢 Live |
| **Backend API** | https://inventory-pro-api-v3.onrender.com/ | 🟢 Live |
| **Health Check** | https://inventory-pro-api-v3.onrender.com/api/health | 🟢 OK |

---

## 🎨 Branding CJ Consultoría

### Colores Oficiales
```css
--azul-marino: #0B1F3A    (Fondo principal)
--plata: #C0C0C0          (Acentos/bordes)
--azul-electrico: #2E7DE8 (Botones/links)
--emerald: #25D366        (WhatsApp)
```

### Tipografía
- **Títulos:** Montserrat (sans-serif)
- **Subtítulos/Énfasis:** Playfair Display (serif, italic)
- **Cuerpo:** Inter

### Datos de Contacto (Reales)
- **Teléfono:** +52 477 694 0272
- **Email:** ing.carlosurbina@gmail.com
- **Ubicación:** León, Guanajuato, México
- **Consultor:** Carlos Urbina

### Datos Bancarios (Transferencias)
- **Institución:** Mercado Pago W
- **CLABE:** 722969020205392763
- **Beneficiario:** Maria Jimena Mena Prado
- **Concepto:** INVPRO

---

## 💳 Planes y Precios (Configurados)

| Plan | Precio | Almacenes | Productos | Usuarios | Características |
|------|--------|-----------|-----------|----------|-----------------|
| **🟢 Gratis** | $0/mes | 1 | 100 | 1 | Reportes básicos, soporte email |
| **🔵 Profesional** | $299/mes | 10 | 500 | 5 | Reportes avanzados, DYMO, **1 mes GRATIS** |
| **🟣 Ilimitado** | $799/mes | Ilimitados | Ilimitados | Ilimitados | API, consultoría dedicada, 24/7 |

### Sistema de Pagos
- ✅ **Transferencia bancaria** - Activo y funcionando
- ⏳ **Stripe (tarjetas)** - Configurado backend pero desactivado frontend (para futuro)

---

## 🏗️ Arquitectura del Proyecto

### Frontend (Vue 3 + Vite)
```
inventory-pro-web/
├── src/
│   ├── views/
│   │   ├── landing/
│   │   │   ├── LandingPage.vue      (Página principal)
│   │   │   └── components/
│   │   │       ├── FeatureCard.vue
│   │   │       ├── PricingCard.vue
│   │   │       └── StepCard.vue
│   │   └── SubscriptionView.vue     (Página de pago)
│   ├── router/index.js
│   └── composables/useDarkMode.js
└── public/
    ├── logo-cj.png                  (Logo principal)
    ├── logo-cj-sin-fondo.png        (Logo hero)
    ├── lobo-cj.png                  (Lobo decorativo)
    ├── banner-cj.png                (Banner)
    └── hero-cj.png                  (Imagen hero)
```

### Backend (Laravel 11)
```
inventory-pro-api/
├── app/
│   ├── Http/Controllers/Api/
│   │   └── StripeController.php     (Pagos - configurado)
│   └── Models/
│       ├── Tenant.php               (Actualizado con planes)
│       └── Payment.php              (Nuevo)
├── config/
│   └── services.php                 (Variables banco/Stripe)
└── database/migrations/
    ├── 2026_04_08_000004_update_tenants_add_stripe_fields.php
    └── 2026_04_08_000005_create_payments_table.php
```

---

## 🔧 Configuración Render (Web Service)

### Variables de Entorno Configuradas
```bash
# Banco (Transferencias) ✅
BANK_INSTITUTION=Mercado Pago W
BANK_HOLDER=Maria Jimena Mena Prado
BANK_CLABE=722969020205392763
BANK_CONCEPT=INVPRO

# Stripe (Para futuro) ⏳
STRIPE_KEY=                  # Vacío por ahora
STRIPE_SECRET=               # Vacío por ahora
STRIPE_WEBHOOK_SECRET=       # Vacío por ahora
```

### Servicios en Render
1. **Static Site:** `inventory-pro` (Frontend)
   - Build Command: `cd inventory-pro-web && npm install && npm run build`
   - Publish Directory: `inventory-pro-web/dist`

2. **Web Service:** `inventory-pro-api-v3` (Backend)
   - Runtime: Docker
   - Plan: Free ($0)

---

## 🎯 Funcionalidades Implementadas

### Landing Page
- ✅ Navegación con scroll suave
- ✅ Modo oscuro/claro
- ✅ Menú móvil hamburguesa
- ✅ Fondo animado profesional (orbes, partículas, líneas)
- ✅ Hero con logo CJ sin fondo
- ✅ Sección de estadísticas
- ✅ Características con iconos
- ✅ Proceso de 4 pasos
- ✅ Tarjetas de precios con badges
- ✅ Sección de consultoría CJ
- ✅ Footer completo
- ✅ Botones WhatsApp funcionales

### Sistema de Pagos
- ✅ Página `/subscribe` con selección de plan
- ✅ Solo transferencia bancaria (Stripe oculto)
- ✅ Datos bancarios mostrados con copiar CLABE
- ✅ Formulario de referencia
- ✅ Modal de confirmación

### Backend API
- ✅ Endpoints de pagos configurados
- ✅ Modelo Payment creado
- ✅ Migraciones ejecutadas
- ✅ Variables de entorno configuradas

---

## 📝 Commits Recientes (Git)

```
5e21052 feat: fondo profesional animado con elementos de consultoría, solo pagos por transferencia
7b16ba5 feat: nuevos planes de precios - Gratis, Profesional $299, Ilimitado $799
908dbb0 fix: botones WhatsApp y Email funcionando correctamente, menu movil agregado
5f7184f feat: Landing page mejorada con CJ branding - logos, precios visuales, scroll navigation
dacacae fix: corregir error de sintaxis duplicado en LandingPage.vue
cd0dacf fix: eliminar codigo residual en LandingPage.vue
d31e10c fix: migraciones verifican existencia de columnas/tablas antes de crearlas
d7fba71 fix: agregar import DB faltante en migracion de stripe fields
cbe4adc config: datos bancarios reales de CJ Consultoría configurados - Mercado Pago
```

---

## 🚀 Próximos Pasos (To-Do)

### Prioridad Alta
1. **Crear cuenta Stripe** - Para activar pagos con tarjeta
2. **Configurar webhook Stripe** - Endpoint en Render
3. **Agregar Stripe.js al frontend** - Formulario de tarjeta

### Prioridad Media
4. **Email de confirmación** - Al registrar transferencia
5. **Panel de administración** - Para activar pagos manualmente
6. **Historial de pagos** - Vista para usuarios

### Prioridad Baja
7. **Blog/Recursos** - Contenido para SEO
8. **Testimonios** - Sección de clientes
9. **Integraciones** - Shopify, WooCommerce, etc.

---

## 🔌 Endpoints API Importantes

```
GET  /api/health                          # Status check
GET  /api/stripe/config                   # Config pública Stripe
POST /api/payments/transfer               # Registrar transferencia
POST /api/payments/subscribe              # Crear suscripción Stripe
POST /api/payments/cancel                 # Cancelar suscripción
GET  /api/payments/history                # Historial de pagos
POST /api/stripe/webhook                  # Webhook Stripe (POST only)
```

---

## 🐛 Issues Conocidos / Resueltos

| Issue | Estado | Solución |
|-------|--------|----------|
| Landing page error de sintaxis | ✅ Resuelto | Código duplicado eliminado |
| Migraciones fallaban | ✅ Resuelto | Verificación `Schema::hasColumn()` agregada |
| Import DB faltante | ✅ Resuelto | `use Illuminate\Support\Facades\DB;` agregado |
| Botones WhatsApp no funcionaban | ✅ Resuelto | Funciones `openWhatsApp()` implementadas |
| Dark mode inconsistente | ✅ Resuelto | `useDarkMode()` composable actualizado |

---

## 📁 Archivos Clave a Modificar (Si se agrega Stripe)

1. `inventory-pro-web/src/views/SubscriptionView.vue`
   - Descomentar sección de tarjeta
   - Agregar script de Stripe.js

2. `inventory-pro-web/.env`
   - Agregar `VITE_STRIPE_KEY=pk_live_...`

3. `inventory-pro-api/config/services.php`
   - Variables Stripe ya configuradas

4. `inventory-pro-api/routes/api.php`
   - Rutas de Stripe ya configuradas

---

## 💡 Notas para Desarrollo Futuro

### Agregar Stripe (Cuando se tenga cuenta)
1. Ir a https://dashboard.stripe.com
2. Obtener API keys (test primero, luego live)
3. Configurar webhook endpoint: `https://inventory-pro-api-v3.onrender.com/api/stripe/webhook`
4. Seleccionar eventos:
   - `invoice.payment_succeeded`
   - `invoice.payment_failed`
   - `customer.subscription.deleted`
   - `customer.subscription.updated`
5. Copiar signing secret a `STRIPE_WEBHOOK_SECRET`

### Modificar Planes
Editar en `LandingPage.vue` y `Tenant.php`:
```javascript
const pricingPlans = [ ... ]
```

```php
const PRICES = [
    self::PLAN_FREE => 0,
    self::PLAN_PROFESSIONAL => 29900,
    self::PLAN_UNLIMITED => 79900,
];
```

---

## 📞 Contacto Soporte

Para cualquier duda o problema:
- **WhatsApp:** https://wa.me/524776940272
- **Email:** ing.carlosurbina@gmail.com

---

**Fin del documento de contexto**
