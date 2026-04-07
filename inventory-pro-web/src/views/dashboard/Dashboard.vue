<template>
  <div>
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold gradient-text font-heading mb-2">Panel de Control</h1>
      <p class="text-cj-silver-dark font-tagline italic">"Gestión integral de tu inventario"</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <div class="stat-card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-cj-silver-dark text-sm font-heading uppercase tracking-wider">Productos</p>
            <p class="text-3xl font-bold mt-1 gradient-text">{{ stats.totalProducts }}</p>
          </div>
          <div class="w-14 h-14 bg-cj-gold/10 rounded-2xl flex items-center justify-center border border-cj-gold/20">
            <svg class="w-7 h-7 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-cj-silver-dark text-sm font-heading uppercase tracking-wider">Valor Total</p>
            <p class="text-3xl font-bold mt-1 text-cj-silver">${{ formatNumber(stats.totalValue) }}</p>
          </div>
          <div class="w-14 h-14 bg-success/10 rounded-2xl flex items-center justify-center border border-success/20">
            <svg class="w-7 h-7 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-cj-silver-dark text-sm font-heading uppercase tracking-wider">Stock Bajo</p>
            <p class="text-3xl font-bold mt-1 text-warning">{{ stats.lowStock }}</p>
          </div>
          <div class="w-14 h-14 bg-warning/10 rounded-2xl flex items-center justify-center border border-warning/20">
            <svg class="w-7 h-7 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-cj-silver-dark text-sm font-heading uppercase tracking-wider">Sin Stock</p>
            <p class="text-3xl font-bold mt-1 text-danger">{{ stats.outOfStock }}</p>
          </div>
          <div class="w-14 h-14 bg-danger/10 rounded-2xl flex items-center justify-center border border-danger/20">
            <svg class="w-7 h-7 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Main Actions -->
      <div class="lg:col-span-2">
        <div class="card-premium p-6">
          <h3 class="text-lg font-semibold mb-4 font-heading flex items-center gap-2">
            <span class="w-1 h-5 bg-cj-gold rounded-full"></span>
            Acciones Rápidas
          </h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button @click="$router.push('/movements/new')" class="group p-4 bg-cj-gold/5 hover:bg-cj-gold/10 border border-cj-gold/20 rounded-xl transition-all text-center">
              <div class="w-12 h-12 bg-cj-gold/10 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                </svg>
              </div>
              <p class="text-sm font-medium text-cj-silver">Movimiento</p>
            </button>

            <button @click="$router.push('/products/new')" class="group p-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-all text-center">
              <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-cj-silver" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
              </div>
              <p class="text-sm font-medium text-cj-silver">Producto</p>
            </button>

            <button @click="$router.push('/reports')" class="group p-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-all text-center">
              <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-cj-silver" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <p class="text-sm font-medium text-cj-silver">Reporte</p>
            </button>

            <button @click="$router.push('/warehouses')" class="group p-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-all text-center">
              <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-cj-silver" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
              <p class="text-sm font-medium text-cj-silver">Almacén</p>
            </button>
          </div>
        </div>
      </div>

      <!-- Alerts -->
      <div class="card-premium p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold font-heading flex items-center gap-2">
            <span class="w-1 h-5 bg-warning rounded-full"></span>
            Alertas
          </h3>
          <span class="badge-gold">{{ lowStockProducts.length }}</span>
        </div>
        <div class="space-y-3 max-h-48 overflow-auto">
          <div v-for="product in lowStockProducts.slice(0, 5)" :key="product.id" class="flex items-center gap-3 p-3 bg-warning/5 border border-warning/10 rounded-lg">
            <div class="w-10 h-10 bg-warning/10 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-medium text-sm truncate">{{ product.name }}</p>
              <p class="text-xs text-cj-silver-dark">Stock: {{ product.stock_quantity }} / Mín: {{ product.min_stock }}</p>
            </div>
          </div>
          <div v-if="lowStockProducts.length === 0" class="text-center py-4 text-cj-silver-dark text-sm">
            No hay alertas de stock
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="card-premium p-6">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold font-heading flex items-center gap-2">
          <span class="w-1 h-5 bg-cj-gold rounded-full"></span>
          Movimientos Recientes
        </h3>
        <router-link to="/movements" class="text-cj-gold hover:text-cj-gold-light text-sm font-heading">Ver todos →</router-link>
      </div>
      <div class="overflow-x-auto">
        <table class="data-table">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Tipo</th>
              <th>Cantidad</th>
              <th>Fecha</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="movement in recentMovements" :key="movement.id">
              <td class="font-medium">{{ movement.product?.name }}</td>
              <td>
                <span :class="['px-2 py-1 rounded-full text-xs font-medium', movement.type === 'entry' ? 'bg-success/10 text-success border border-success/20' : 'bg-danger/10 text-danger border border-danger/20']">
                  {{ movement.type === 'entry' ? 'Entrada' : 'Salida' }}
                </span>
              </td>
              <td class="font-bold">{{ movement.quantity }}</td>
              <td class="text-cj-silver-dark">{{ formatDate(movement.created_at) }}</td>
            </tr>
            <tr v-if="recentMovements.length === 0">
              <td colspan="4" class="text-center py-4 text-cj-silver-dark">No hay movimientos recientes</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useProductsStore } from '../../stores/products'
import apiClient from '../../services/api'

const productsStore = useProductsStore()

const stats = ref({ totalProducts: 0, totalValue: 0, lowStock: 0, outOfStock: 0 })
const recentMovements = ref([])
const lowStockProducts = ref([])

function formatNumber(num) {
  return new Intl.NumberFormat('es-ES').format(num || 0)
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' })
}

onMounted(async () => {
  try {
    const response = await apiClient.get('/dashboard')
    stats.value = response.data.stats
    recentMovements.value = response.data.recent_movements
    lowStockProducts.value = response.data.low_stock_products
  } catch (err) {
    console.error('Error fetching dashboard:', err)
  }
})
</script>
