# Inventory Pro

Sistema profesional de gestión de inventarios multi-tenant con arquitectura SaaS.

## 🏗️ Arquitectura

```
┌─────────────────────────────────────────────────────────────────┐
│                         INVENTORY PRO                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────┐  ┌──────────────────┐  ┌─────────────────┐│
│  │  Landing Page    │  │   Frontend       │  │    Backend      ││
│  │  (Next.js)       │  │   (Vue 3)        │  │   (Laravel)     ││
│  │                  │  │                  │  │                 ││
│  │  - Marketing     │  │  - Dashboard     │  │  - API REST     ││
│  │  - Pricing       │  │  - Productos     │  │  - Auth JWT     ││
│  │  - Demo          │  │  - Inventario    │  │  - Multi-tenant ││
│  │  Port: 3000      │  │  - Reportes      │  │  - RLS Postgres ││
│  └──────────────────┘  └──────────────────┘  └─────────────────┘│
│           │                      │                      │        │
│           └──────────────────────┴──────────────────────┘        │
│                                  │                               │
│                    ┌─────────────┴─────────────┐                 │
│                    │      Docker Compose       │                 │
│                    │                           │                 │
│                    │  - PostgreSQL 15 (RLS)    │                 │
│                    │  - Redis (Cache/Sessions) │                 │
│                    │  - Nginx (Reverse Proxy)  │                 │
│                    └───────────────────────────┘                 │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

## 🚀 Stack Tecnológico

### Backend (inventory-pro-api)
- **Framework:** Laravel 11 + PHP 8.3
- **Base de Datos:** PostgreSQL 15 con Row-Level Security (RLS)
- **Cache:** Redis
- **Autenticación:** JWT + Laravel Sanctum
- **Testing:** PHPUnit

### Frontend (inventory-pro-web)
- **Framework:** Vue.js 3 + Composition API
- **Build Tool:** Vite
- **Estado:** Pinia
- **HTTP Client:** Axios
- **UI:** Tailwind CSS + Custom Components
- **Query:** TanStack Query (Vue Query)

### Landing Page (inventory-pro-landing)
- **Framework:** Next.js 14 (App Router)
- **Estilos:** Tailwind CSS
- **Animaciones:** Framer Motion
- **Deploy:** Static Export

### Infraestructura
- **Containers:** Docker + Docker Compose
- **Web Server:** Nginx
- **DB:** PostgreSQL 15
- **Cache:** Redis 7

## 📁 Estructura de Carpetas

```
10_CODIGO_FUENTE/
├── inventory-pro-api/          # Backend Laravel
│   ├── app/
│   │   ├── Http/Controllers/   # Controladores API
│   │   ├── Models/             # Modelos con BelongsToTenant
│   │   └── Traits/             # BelongsToTenant trait
│   ├── database/
│   │   └── migrations/         # Migraciones con RLS
│   ├── routes/
│   │   └── api.php             # Rutas API
│   └── docker/                 # Config Docker para PHP
│
├── inventory-pro-web/          # Frontend Vue
│   ├── src/
│   │   ├── views/              # Páginas
│   │   ├── stores/             # Pinia stores
│   │   ├── components/         # Componentes reutilizables
│   │   └── router/             # Vue Router
│   └── public/
│
├── inventory-pro-landing/      # Landing Next.js
│   ├── app/                    # App Router
│   ├── components/             # React components
│   └── public/
│
└── docker/                     # Configuración Docker
    ├── docker-compose.yml
    ├── nginx/
    └── php/
```

## 🛠️ Instalación

### Requisitos
- Docker Desktop
- Node.js 18+ (para desarrollo local)
- Git

### 1. Clonar y entrar al proyecto
```bash
cd 10_CODIGO_FUENTE
```

### 2. Iniciar infraestructura Docker
```bash
cd docker
docker-compose up -d
```

### 3. Configurar Backend
```bash
cd ../inventory-pro-api

# Copiar configuración
cp .env.example .env

# Instalar dependencias y ejecutar migraciones
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

### 4. Configurar Frontend
```bash
cd ../inventory-pro-web
npm install
cp .env.example .env
npm run dev
```

### 5. Configurar Landing Page
```bash
cd ../inventory-pro-landing
npm install
npm run dev
```

## 🔑 Accesos de Desarrollo

| Servicio | URL | Credenciales |
|----------|-----|--------------|
| Frontend | http://localhost:5173 | Registro libre |
| Backend API | http://localhost:8000/api | - |
| Landing | http://localhost:3000 | - |
| PostgreSQL | localhost:5432 | postgres/secret |
| Redis | localhost:6379 | - |

## 🧪 Testing

### Backend
```bash
cd inventory-pro-api
php artisan test
```

### Frontend
```bash
cd inventory-pro-web
npm run test
```

## 📚 Documentación API

La documentación de la API está disponible en:
- Swagger/OpenAPI: `/api/documentation`
- Postman Collection: `docs/InventoryPro.postman_collection.json`

## 🔒 Seguridad

- **Multi-tenancy:** Aislamiento de datos mediante PostgreSQL RLS
- **Autenticación:** JWT tokens con refresh
- **Encriptación:** AES-256 para datos sensibles
- **CORS:** Configurado para orígenes específicos
- **Rate Limiting:** Protección contra abuso de API

## 🚀 Deployment

### Producción (Docker)
```bash
cd docker
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```

### Variables de Entorno Importantes
```env
# Backend
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_DATABASE=inventory_pro
DB_USERNAME=postgres
DB_PASSWORD=secret

JWT_SECRET=your-jwt-secret

# Frontend
VITE_API_URL=https://api.inventorypro.com/api
```

## 📄 Licencia

Proyecto privado - Todos los derechos reservados.

## 👥 Equipo

- **Desarrollo:** CJ Consultoría
- **Diseño:** CJ Consultoría UX Team

---

¿Preguntas? Contacta a: soporte@inventorypro.com