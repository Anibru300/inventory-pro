<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
      <button
        @click="$router.back()"
        class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-white hover:shadow-sm transition-all"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
      </button>
      <div>
        <h1 class="text-3xl font-bold text-slate-800">Análisis ABC</h1>
        <p class="text-slate-500">Clasificación de productos por valoración</p>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <p class="text-slate-500 text-sm font-medium uppercase">Total Productos</p>
        <p class="text-3xl font-bold mt-2 text-slate-800">{{ summary.total_products }}</p>
      </div>
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 border-l-4 border-l-emerald-500">
        <p class="text-slate-500 text-sm font-medium uppercase">Clase A</p>
        <p class="text-3xl font-bold mt-2 text-emerald-600">{{ summary.class_a }}</p>
        <p class="text-xs text-slate-400">80% del valor</p>
      </div>
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 border-l-4 border-l-blue-500">
        <p class="text-slate-500 text-sm font-medium uppercase">Clase B</p>
        <p class="text-3xl font-bold mt-2 text-blue-600">{{ summary.class_b }}</p>
        <p class="text-xs text-slate-400">15% del valor</p>
      </div>
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 border-l-4 border-l-slate-400">
        <p class="text-slate-500 text-sm font-medium uppercase">Clase C</p>
        <p class="text-3xl font-bold mt-2 text-slate-600">{{ summary.class_c }}</p>
        <p class="text-xs text-slate-400">5% del valor</p>
      </div>
    </div>

    <!-- Legend -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
      <h3 class="font-semibold text-slate-800 mb-4">Leyenda</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-700 font-bold">A</div>
          <div>
            <p class="font-medium text-slate-800">Alta Prioridad</p>
            <p class="text-sm text-slate-500">Productos que representan ~80% del valor total del inventario. Requieren control estricto.</p>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-700 font-bold">B</div>
          <div>
            <p class="font-medium text-slate-800">Media Prioridad</p>
            <p class="text-sm text-slate-500">Productos que representan ~15% del valor. Control moderado.</p>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-700 font-bold">C</div>
          <div>
            <p class="font-medium text-slate-800">Baja Prioridad</p>
            <p class="text-sm text-slate-500">Productos que representan ~5% del valor. Control simple.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
      <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h3 class="font-semibold text-slate-800">Detalle de Productos</h3>
        <div class="flex gap-2">
          <button 
            v-for="filter in ['Todos', 'A', 'B', 'C']" 
            :key="filter"
            @click="activeFilter = filter"
            :class="['px-4 py-2 rounded-lg text-sm font-medium transition-colors',
              activeFilter === filter 
                ? 'bg-blue-600 text-white' 
                : 'bg-slate-100 text-slate-600 hover:bg-slate-200']"
          >
            {{ filter }}
          </button>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/50">
              <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Clase</th>
              <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Producto</th>
              <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">SKU</th>
              <th class="text-right py-4 px-6 text-sm font-semibold text-slate-700">Stock</th>
              <th class="text-right py-4 px-6 text-sm font-semibold text-slate-700">Valor</th>
              <th class="text-right py-4 px-6 text-sm font-semibold text-slate-700">% Acum.</th>
              <th class="text-center py-4 px-6 text-sm font-semibold text-slate-700">Movimientos</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in filteredProducts" :key="product.id" 
              :class="['border-b border-slate-50 hover:bg-slate-50/80 transition-colors',
                product.abc_class === 'A' ? 'bg-emerald-50/30' :
                product.abc_class === 'B' ? 'bg-blue-50/30' : '']"
            >
              <td class="py-4 px-6">
                <span :class="['w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm',
                  product.abc_class === 'A' ? 'bg-emerald-100 text-emerald-700' :
                  product.abc_class === 'B' ? 'bg-blue-100 text-blue-700' :
                  'bg-slate-100 text-slate-700']">
                  {{ product.abc_class }}
                </span>
              </td>
              <td class="py-4 px-6">
                <p class="font-medium text-slate-800">{{ product.name }}</p>
                <p class="text-xs text-slate-500">{{ product.category || 'Sin categoría' }}</p>
              </td>
              <td class="py-4 px-6 font-mono text-sm text-slate-600">{{ product.sku }}</td>
              <td class="py-4 px-6 text-right">
                <p class="font-medium text-slate-800">{{ product.stock_quantity }}</p>
                <p class="text-xs text-slate-400">und</p>
              </td>
              <td class="py-4 px-6 text-right">
                <p class="font-medium text-slate-800">${{ formatNumber(product.stock_value) }}</p>
                <p class="text-xs text-slate-400">{{ product.percentage_value }}%</p>
              </td>
              <td class="py-4 px-6 text-right">
                <div class="flex items-center gap-2">
                  <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div :class="['h-full rounded-full',
                      product.abc_class === 'A' ? 'bg-emerald-500' :
                      product.abc_class === 'B' ? 'bg-blue-500' : 'bg-slate-400']"
                      :style="{ width: Math.min(product.accumulated_percentage, 100) + '%' }">
                    </div>
                  </div>
                  <span class="text-sm text-slate-600 w-12">{{ product.accumulated_percentage.toFixed(1) }}%</span>
                </div>
              </td>
              <td class="py-4 px-6 text-center">
                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs">
                  {{ product.movement_count }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <div v-if="filteredProducts.length === 0" class="p-12 text-center">
        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </div>
        <p class="text-slate-500">No hay productos para mostrar</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import apiClient from '../../services/api'

const loading = ref(false)
const products = ref([])
const summary = ref({
  total_products: 0,
  total_value: 0,
  class_a: 0,
  class_b: 0,
  class_c: 0,
})
const activeFilter = ref('Todos')

const filteredProducts = computed(() => {
  if (activeFilter.value === 'Todos') {
    return products.value
  }
  return products.value.filter(p => p.abc_class === activeFilter.value)
})

function formatNumber(num) {
  if (!num) return '0.00'
  return num.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

async function fetchABCAnalysis() {
  loading.value = true
  try {
    const response = await apiClient.get('/products-analysis/abc')
    products.value = response.data.products
    summary.value = response.data.summary
  } catch (err) {
    console.error('Error fetching ABC analysis:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchABCAnalysis()
})
</script>
