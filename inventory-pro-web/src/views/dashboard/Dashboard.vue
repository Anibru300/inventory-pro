<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
    <!-- Header Principal -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-slate-800 mb-1">Panel de Control</h1>
          <p class="text-slate-500">Gestión integral de tu inventario</p>
        </div>
        <div class="flex items-center gap-3">
          <span class="text-sm text-slate-500 bg-white px-3 py-1.5 rounded-lg shadow-sm border border-slate-200">
            {{ currentDate }}
          </span>
          <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/20">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <!-- Total Productos -->
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-500 text-sm font-medium uppercase tracking-wide">Total Productos</p>
            <p class="text-3xl font-bold mt-2 text-slate-800">{{ formatNumber(stats.totalProducts) }}</p>
            <div class="flex items-center gap-1 mt-2">
              <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
              <span class="text-xs text-emerald-600 font-medium">Activos</span>
            </div>
          </div>
          <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Valor Inventario -->
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-500 text-sm font-medium uppercase tracking-wide">Valor Inventario</p>
            <p class="text-3xl font-bold mt-2 text-slate-800">${{ formatNumber(stats.totalValue) }}</p>
            <p class="text-xs text-slate-400 mt-2">Valor total en stock</p>
          </div>
          <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Stock Bajo -->
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-500 text-sm font-medium uppercase tracking-wide">Stock Bajo</p>
            <p class="text-3xl font-bold mt-2 text-amber-600">{{ stats.lowStock }}</p>
            <p class="text-xs text-amber-500 mt-2">Requieren atención</p>
          </div>
          <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Sin Stock -->
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-500 text-sm font-medium uppercase tracking-wide">Sin Stock</p>
            <p class="text-3xl font-bold mt-2 text-rose-600">{{ stats.outOfStock }}</p>
            <p class="text-xs text-rose-500 mt-2">Agotados</p>
          </div>
          <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
            <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Panel de Configuración Rápida -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 mb-8">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <span class="w-1 h-5 bg-blue-600 rounded-full"></span>
            Configuración Inicial
          </h3>
          <p class="text-sm text-slate-500 mt-1">Configure los elementos básicos para comenzar</p>
        </div>
        <span class="text-xs text-slate-400 bg-slate-100 px-3 py-1 rounded-full">Paso 1 de 4</span>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Categorías -->
        <button @click="$router.push('/categories')" class="group p-5 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 rounded-xl text-left transition-all">
          <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:scale-110 transition-all">
            <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
          </div>
          <h4 class="font-semibold text-slate-800 mb-1">Categorías</h4>
          <p class="text-xs text-slate-500">Organice sus productos por categorías</p>
        </button>

        <!-- Almacenes -->
        <button @click="$router.push('/warehouses')" class="group p-5 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 rounded-xl text-left transition-all">
          <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-3 group-hover:bg-indigo-600 group-hover:scale-110 transition-all">
            <svg class="w-6 h-6 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <h4 class="font-semibold text-slate-800 mb-1">Almacenes</h4>
          <p class="text-xs text-slate-500">Gestione ubicaciones de almacenamiento</p>
        </button>

        <!-- Proveedores -->
        <button @click="$router.push('/suppliers')" class="group p-5 bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 rounded-xl text-left transition-all">
          <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-3 group-hover:bg-emerald-600 group-hover:scale-110 transition-all">
            <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <h4 class="font-semibold text-slate-800 mb-1">Proveedores</h4>
          <p class="text-xs text-slate-500">Administre sus proveedores</p>
        </button>

        <!-- Usuarios -->
        <button @click="$router.push('/users')" class="group p-5 bg-slate-50 hover:bg-violet-50 border border-slate-200 hover:border-violet-300 rounded-xl text-left transition-all">
          <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center mb-3 group-hover:bg-violet-600 group-hover:scale-110 transition-all">
            <svg class="w-6 h-6 text-violet-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <h4 class="font-semibold text-slate-800 mb-1">Usuarios</h4>
          <p class="text-xs text-slate-500">Gestione permisos del equipo</p>
        </button>
      </div>
    </div>

    <!-- Acciones Rápidas y Alertas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Acciones Principales -->
      <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <h3 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2">
          <span class="w-1 h-5 bg-blue-600 rounded-full"></span>
          Operaciones Rápidas
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <button @click="$router.push('/movements/new')" class="group p-4 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-xl transition-all flex flex-col items-center gap-3">
            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg shadow-blue-600/20">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
              </svg>
            </div>
            <span class="text-sm font-medium text-slate-700">Movimiento</span>
          </button>

          <button @click="$router.push('/products/new')" class="group p-4 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition-all flex flex-col items-center gap-3">
            <div class="w-12 h-12 bg-slate-700 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
            </div>
            <span class="text-sm font-medium text-slate-700">Producto</span>
          </button>

          <button @click="$router.push('/transfers/new')" class="group p-4 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl transition-all flex flex-col items-center gap-3">
            <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg shadow-indigo-600/20">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
              </svg>
            </div>
            <span class="text-sm font-medium text-slate-700">Transferir</span>
          </button>

          <button @click="$router.push('/reports')" class="group p-4 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition-all flex flex-col items-center gap-3">
            <div class="w-12 h-12 bg-emerald-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg shadow-emerald-600/20">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <span class="text-sm font-medium text-slate-700">Reporte</span>
          </button>
        </div>
      </div>

      <!-- Alertas de Stock -->
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-5">
          <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <span class="w-1 h-5 bg-amber-500 rounded-full"></span>
            Alertas
          </h3>
          <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ lowStockProducts.length }}</span>
        </div>
        <div class="space-y-3 max-h-64 overflow-auto pr-2">
          <div v-for="product in lowStockProducts.slice(0, 5)" :key="product.id" 
               class="flex items-center gap-3 p-3 bg-amber-50 border border-amber-100 rounded-xl hover:bg-amber-100 transition-colors cursor-pointer"
               @click="$router.push(`/products/${product.id}`)">
            <div class="w-10 h-10 bg-amber-200 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-medium text-sm truncate text-slate-800">{{ product.name }}</p>
              <p class="text-xs text-amber-700">Stock: {{ product.stock_quantity }} / Mín: {{ product.min_stock }}</p>
            </div>
          </div>
          <div v-if="lowStockProducts.length === 0" class="text-center py-8">
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
              <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <p class="text-slate-500 text-sm">No hay alertas de stock</p>
          </div>
        </div>
        <router-link v-if="lowStockProducts.length > 0" to="/products/low-stock" 
                     class="mt-4 block text-center text-sm text-blue-600 hover:text-blue-700 font-medium">
          Ver todas las alertas →
        </router-link>
      </div>
    </div>

    <!-- Movimientos Recientes -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
          <span class="w-1 h-5 bg-blue-600 rounded-full"></span>
          Movimientos Recientes
        </h3>
        <router-link to="/movements" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
          Ver todos →
        </router-link>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-slate-100">
              <th class="text-left py-3 px-4 text-sm font-semibold text-slate-600">Producto</th>
              <th class="text-left py-3 px-4 text-sm font-semibold text-slate-600">Tipo</th>
              <th class="text-left py-3 px-4 text-sm font-semibold text-slate-600">Cantidad</th>
              <th class="text-left py-3 px-4 text-sm font-semibold text-slate-600">Almacén</th>
              <th class="text-left py-3 px-4 text-sm font-semibold text-slate-600">Fecha</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="movement in recentMovements" :key="movement.id" class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
              <td class="py-4 px-4 font-medium text-slate-800">{{ movement.product?.name }}</td>
              <td class="py-4 px-4">
                <span :class="['px-3 py-1 rounded-full text-xs font-medium', 
                  movement.type === 'entry' ? 'bg-emerald-100 text-emerald-700' : 
                  movement.type === 'exit' ? 'bg-rose-100 text-rose-700' :
                  'bg-blue-100 text-blue-700']">
                  {{ movement.type === 'entry' ? 'Entrada' : movement.type === 'exit' ? 'Salida' : 'Transferencia' }}
                </span>
              </td>
              <td class="py-4 px-4 font-semibold text-slate-800">{{ movement.quantity }}</td>
              <td class="py-4 px-4 text-slate-500">{{ movement.warehouse?.name || 'N/A' }}</td>
              <td class="py-4 px-4 text-slate-500 text-sm">{{ formatDate(movement.created_at) }}</td>
            </tr>
            <tr v-if="recentMovements.length === 0">
              <td colspan="5" class="text-center py-12">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                  <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                </div>
                <p class="text-slate-500">No hay movimientos recientes</p>
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
