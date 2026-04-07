<template>
  <div class="min-h-screen bg-cj-black">
    <!-- Header Principal -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold gradient-text font-heading mb-2">Panel de Control</h1>
          <p class="text-cj-silver-dark font-tagline italic">"Gestión integral de tu inventario"</p>
        </div>
        <div class="flex items-center gap-3">
          <span class="text-sm text-cj-silver-dark">{{ currentDate }}</span>
          <div class="w-10 h-10 bg-cj-gold/10 rounded-full flex items-center justify-center border border-cj-gold/20">
            <svg class="w-5 h-5 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <div class="stat-card group hover:border-cj-gold/30 transition-all">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-cj-silver-dark text-sm font-heading uppercase tracking-wider">Total Productos</p>
            <p class="text-3xl font-bold mt-1 gradient-text">{{ formatNumber(stats.totalProducts) }}</p>
            <p class="text-xs text-success mt-1 flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
              </svg>
              Activos
            </p>
          </div>
          <div class="w-14 h-14 bg-cj-gold/10 rounded-2xl flex items-center justify-center border border-cj-gold/20 group-hover:scale-110 transition-transform">
            <svg class="w-7 h-7 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card group hover:border-success/30 transition-all">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-cj-silver-dark text-sm font-heading uppercase tracking-wider">Valor Inventario</p>
            <p class="text-3xl font-bold mt-1 text-cj-silver">${{ formatNumber(stats.totalValue) }}</p>
            <p class="text-xs text-cj-silver-dark mt-1">Valor total en stock</p>
          </div>
          <div class="w-14 h-14 bg-success/10 rounded-2xl flex items-center justify-center border border-success/20 group-hover:scale-110 transition-transform">
            <svg class="w-7 h-7 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card group hover:border-warning/30 transition-all">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-cj-silver-dark text-sm font-heading uppercase tracking-wider">Stock Bajo</p>
            <p class="text-3xl font-bold mt-1 text-warning">{{ stats.lowStock }}</p>
            <p class="text-xs text-warning mt-1">Requieren atención</p>
          </div>
          <div class="w-14 h-14 bg-warning/10 rounded-2xl flex items-center justify-center border border-warning/20 group-hover:scale-110 transition-transform">
            <svg class="w-7 h-7 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card group hover:border-danger/30 transition-all">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-cj-silver-dark text-sm font-heading uppercase tracking-wider">Sin Stock</p>
            <p class="text-3xl font-bold mt-1 text-danger">{{ stats.outOfStock }}</p>
            <p class="text-xs text-danger mt-1">Agotados</p>
          </div>
          <div class="w-14 h-14 bg-danger/10 rounded-2xl flex items-center justify-center border border-danger/20 group-hover:scale-110 transition-transform">
            <svg class="w-7 h-7 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Panel de Configuración Rápida -->
    <div class="card-premium p-6 mb-8">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold font-heading flex items-center gap-2">
          <span class="w-1 h-5 bg-cj-gold rounded-full"></span>
          Configuración Rápida
        </h3>
        <p class="text-sm text-cj-silver-dark">Configure los elementos básicos de su sistema</p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Categorías -->
        <button @click="$router.push('/categories')" class="config-card group">
          <div class="w-12 h-12 bg-cj-gold/10 rounded-xl flex items-center justify-center mb-3 group-hover:bg-cj-gold/20 transition-colors">
            <svg class="w-6 h-6 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
          </div>
          <h4 class="font-semibold text-cj-silver mb-1">Categorías</h4>
          <p class="text-xs text-cj-silver-dark">Organice sus productos por categorías</p>
          <span class="mt-3 text-xs text-cj-gold flex items-center gap-1">
            Configurar 
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </span>
        </button>

        <!-- Almacenes -->
        <button @click="$router.push('/warehouses')" class="config-card group">
          <div class="w-12 h-12 bg-cj-blue/10 rounded-xl flex items-center justify-center mb-3 group-hover:bg-cj-blue/20 transition-colors">
            <svg class="w-6 h-6 text-cj-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <h4 class="font-semibold text-cj-silver mb-1">Almacenes</h4>
          <p class="text-xs text-cj-silver-dark">Gestione sus ubicaciones de almacenamiento</p>
          <span class="mt-3 text-xs text-cj-blue flex items-center gap-1">
            Configurar 
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </span>
        </button>

        <!-- Proveedores -->
        <button @click="$router.push('/suppliers')" class="config-card group">
          <div class="w-12 h-12 bg-success/10 rounded-xl flex items-center justify-center mb-3 group-hover:bg-success/20 transition-colors">
            <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <h4 class="font-semibold text-cj-silver mb-1">Proveedores</h4>
          <p class="text-xs text-cj-silver-dark">Administre sus proveedores y contactos</p>
          <span class="mt-3 text-xs text-success flex items-center gap-1">
            Configurar 
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </span>
        </button>

        <!-- Usuarios -->
        <button @click="$router.push('/users')" class="config-card group">
          <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center mb-3 group-hover:bg-purple-500/20 transition-colors">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <h4 class="font-semibold text-cj-silver mb-1">Usuarios</h4>
          <p class="text-xs text-cj-silver-dark">Gestione permisos y accesos del equipo</p>
          <span class="mt-3 text-xs text-purple-400 flex items-center gap-1">
            Configurar 
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </span>
        </button>
      </div>
    </div>

    <!-- Acciones Rápidas y Alertas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Acciones Principales -->
      <div class="lg:col-span-2">
        <div class="card-premium p-6">
          <h3 class="text-lg font-semibold mb-4 font-heading flex items-center gap-2">
            <span class="w-1 h-5 bg-cj-gold rounded-full"></span>
            Operaciones Rápidas
          </h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button @click="$router.push('/movements/new')" class="action-btn bg-cj-gold/10 border-cj-gold/20 hover:bg-cj-gold/20">
              <div class="w-12 h-12 bg-cj-gold/20 rounded-full flex items-center justify-center mb-2">
                <svg class="w-6 h-6 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                </svg>
              </div>
              <p class="text-sm font-medium text-cj-silver">Movimiento</p>
            </button>

            <button @click="$router.push('/products/new')" class="action-btn bg-white/5 border-white/10 hover:bg-white/10">
              <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center mb-2">
                <svg class="w-6 h-6 text-cj-silver" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
              </div>
              <p class="text-sm font-medium text-cj-silver">Producto</p>
            </button>

            <button @click="$router.push('/transfers/new')" class="action-btn bg-cj-blue/10 border-cj-blue/20 hover:bg-cj-blue/20">
              <div class="w-12 h-12 bg-cj-blue/20 rounded-full flex items-center justify-center mb-2">
                <svg class="w-6 h-6 text-cj-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
              </div>
              <p class="text-sm font-medium text-cj-silver">Transferir</p>
            </button>

            <button @click="$router.push('/reports')" class="action-btn bg-white/5 border-white/10 hover:bg-white/10">
              <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center mb-2">
                <svg class="w-6 h-6 text-cj-silver" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <p class="text-sm font-medium text-cj-silver">Reporte</p>
            </button>
          </div>
        </div>
      </div>

      <!-- Alertas de Stock -->
      <div class="card-premium p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold font-heading flex items-center gap-2">
            <span class="w-1 h-5 bg-warning rounded-full"></span>
            Alertas
          </h3>
          <span class="badge-gold">{{ lowStockProducts.length }}</span>
        </div>
        <div class="space-y-3 max-h-64 overflow-auto pr-2">
          <div v-for="product in lowStockProducts.slice(0, 6)" :key="product.id" 
               class="flex items-center gap-3 p-3 bg-warning/5 border border-warning/10 rounded-lg hover:bg-warning/10 transition-colors cursor-pointer"
               @click="$router.push(`/products/${product.id}`)">
            <div class="w-10 h-10 bg-warning/10 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-medium text-sm truncate text-cj-silver">{{ product.name }}</p>
              <p class="text-xs text-cj-silver-dark">Stock: {{ product.stock_quantity }} / Mín: {{ product.min_stock }}</p>
            </div>
          </div>
          <div v-if="lowStockProducts.length === 0" class="text-center py-8 text-cj-silver-dark">
            <svg class="w-12 h-12 mx-auto mb-2 text-cj-silver-dark/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm">No hay alertas de stock</p>
          </div>
        </div>
        <router-link v-if="lowStockProducts.length > 0" to="/products/low-stock" 
                     class="mt-4 block text-center text-sm text-cj-gold hover:text-cj-gold-light">
          Ver todas las alertas →
        </router-link>
      </div>
    </div>

    <!-- Movimientos Recientes -->
    <div class="card-premium p-6">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold font-heading flex items-center gap-2">
          <span class="w-1 h-5 bg-cj-gold rounded-full"></span>
          Movimientos Recientes
        </h3>
        <router-link to="/movements" class="text-cj-gold hover:text-cj-gold-light text-sm font-heading">
          Ver todos →
        </router-link>
      </div>
      <div class="overflow-x-auto">
        <table class="data-table">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Tipo</th>
              <th>Cantidad</th>
              <th>Almacén</th>
              <th>Fecha</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="movement in recentMovements" :key="movement.id" class="hover:bg-white/5 transition-colors">
              <td class="font-medium text-cj-silver">{{ movement.product?.name }}</td>
              <td>
                <span :class="['px-3 py-1 rounded-full text-xs font-medium border', 
                  movement.type === 'entry' ? 'bg-success/10 text-success border-success/20' : 
                  movement.type === 'exit' ? 'bg-danger/10 text-danger border-danger/20' :
                  'bg-cj-blue/10 text-cj-blue border-cj-blue/20']">
                  {{ movement.type === 'entry' ? 'Entrada' : movement.type === 'exit' ? 'Salida' : 'Transferencia' }}
                </span>
              </td>
              <td class="font-bold">{{ movement.quantity }}</td>
              <td class="text-cj-silver-dark">{{ movement.warehouse?.name || 'N/A' }}</td>
              <td class="text-cj-silver-dark text-sm">{{ formatDate(movement.created_at) }}</td>
            </tr>
            <tr v-if="recentMovements.length === 0">
              <td colspan="5" class="text-center py-8 text-cj-silver-dark">
                <svg class="w-12 h-12 mx-auto mb-2 text-cj-silver-dark/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p>No hay movimientos recientes</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useProductsStore } from '../../stores/products'
import apiClient from '../../services/api'

const productsStore = useProductsStore()

const stats = ref({ totalProducts: 0, totalValue: 0, lowStock: 0, outOfStock: 0 })
const recentMovements = ref([])
const lowStockProducts = ref([])

const currentDate = computed(() => {
  return new Date().toLocaleDateString('es-ES', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
})

function formatNumber(num) {
  return new Intl.NumberFormat('es-ES').format(num || 0)
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('es-ES', { 
    day: '2-digit', 
    month: 'short', 
    hour: '2-digit', 
    minute: '2-digit' 
  })
}

onMounted(async () => {
  try {
    const response = await apiClient.get('/dashboard')
    stats.value = response.data.stats
    recentMovements.value = response.data.recent_movements || []
    lowStockProducts.value = response.data.low_stock_products || []
  } catch (err) {
    console.error('Error fetching dashboard:', err)
  }
})
</script>

<style scoped>
.config-card {
  @apply p-4 bg-white/5 border border-white/10 rounded-xl text-left transition-all hover:border-cj-gold/30 hover:bg-white/10;
}

.action-btn {
  @apply p-4 border rounded-xl transition-all flex flex-col items-center justify-center gap-2 hover:scale-105;
}
</style>
