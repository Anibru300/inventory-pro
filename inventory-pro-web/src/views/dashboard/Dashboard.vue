<template>
  <div>
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-2xl font-bold">Dashboard</h1>
      <p class="text-cj-silver">Resumen de tu inventario</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div class="card p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-cj-gray text-sm">Productos Totales</p>
            <p class="text-2xl font-bold mt-1">{{ stats.totalProducts }}</p>
          </div>
          <div class="w-12 h-12 bg-cj-electric/10 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-cj-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
        </div>
      </div>

      <div class="card p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-cj-gray text-sm">Valor Inventario</p>
            <p class="text-2xl font-bold mt-1">${{ formatNumber(stats.totalValue) }}</p>
          </div>
          <div class="w-12 h-12 bg-success/10 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="card p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-cj-gray text-sm">Stock Bajo</p>
            <p class="text-2xl font-bold mt-1 text-warning">{{ stats.lowStock }}</p>
          </div>
          <div class="w-12 h-12 bg-warning/10 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="card p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-cj-gray text-sm">Sin Stock</p>
            <p class="text-2xl font-bold mt-1 text-danger">{{ stats.outOfStock }}</p>
          </div>
          <div class="w-12 h-12 bg-danger/10 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Recent Movements -->
      <div class="lg:col-span-2 card">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-semibold">Movimientos Recientes</h2>
          <router-link to="/inventory/movements" class="text-cj-electric hover:text-cj-electric-light text-sm">
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
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="movement in recentMovements" :key="movement.id">
                <td class="font-medium">{{ movement.product?.name }}</td>
                <td>
                  <span :class="[
                    'px-2 py-1 rounded-full text-xs font-medium',
                    movement.type === 'entry' ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger'
                  ]">
                    {{ movement.type === 'entry' ? 'Entrada' : 'Salida' }}
                  </span>
                </td>
                <td>{{ movement.quantity }}</td>
                <td class="text-text-secondary">{{ formatDate(movement.created_at) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Low Stock Alert -->
      <div class="card">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-semibold">Alertas de Stock</h2>
          <span class="px-2 py-1 bg-warning/10 text-warning text-xs rounded-full">
            {{ lowStockProducts.length }} productos
          </span>
        </div>
        <div class="space-y-3">
          <div
            v-for="product in lowStockProducts.slice(0, 5)"
            :key="product.id"
            class="flex items-center gap-3 p-3 bg-cj-navy-light rounded-lg"
          >
            <div class="w-10 h-10 bg-cj-navy rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-cj-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-medium truncate">{{ product.name }}</p>
              <p class="text-sm text-text-secondary">
                Stock: {{ product.stock_quantity }} / Mín: {{ product.min_stock }}
              </p>
            </div>
          </div>
        </div>
        <router-link
          to="/products"
          class="block w-full mt-4 py-2 text-center text-cj-electric hover:text-cj-electric-light text-sm border border-accent-primary/30 rounded-lg hover:bg-accent-primary/5 transition-colors"
        >
          Gestionar inventario
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useProductsStore } from '../../stores/products'
import apiClient from '../../services/api'

const productsStore = useProductsStore()

const stats = ref({
  totalProducts: 0,
  totalValue: 0,
  lowStock: 0,
  outOfStock: 0,
})

const recentMovements = ref([])
const lowStockProducts = ref([])

function formatNumber(num) {
  return new Intl.NumberFormat('es-ES').format(num || 0)
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('es-ES', {
    day: '2-digit',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit',
  })
}

onMounted(async () => {
  // Fetch products for stats
  await productsStore.fetchProducts({ per_page: 1 })
  
  // Fetch dashboard data
  try {
    const response = await apiClient.get('/dashboard')
    stats.value = response.data.stats
    recentMovements.value = response.data.recent_movements
    lowStockProducts.value = response.data.low_stock_products
  } catch (err) {
    console.error('Error fetching dashboard data:', err)
    // Use store data as fallback
    stats.value.totalProducts = productsStore.pagination.total
  }
})
</script>