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
        <h1 class="text-3xl font-bold text-slate-800">Kardex</h1>
        <p class="text-slate-500">Movimientos detallados del producto</p>
      </div>
    </div>

    <!-- Product Selector -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
          <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input 
            v-model="searchQuery" 
            @input="searchProducts"
            type="text" 
            placeholder="Buscar producto..."
            class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
          />
          <!-- Search Results Dropdown -->
          <div v-if="searchResults.length > 0" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-lg border border-slate-100 z-10 max-h-64 overflow-auto">
            <button v-for="product in searchResults" :key="product.id"
              @click="selectProduct(product)"
              class="w-full text-left px-4 py-3 hover:bg-slate-50 border-b border-slate-100 last:border-0">
              <p class="font-medium text-slate-800">{{ product.name }}</p>
              <p class="text-sm text-slate-500">SKU: {{ product.sku }}</p>
            </button>
          </div>
        </div>
        
        <select v-model="filters.warehouse_id" @change="fetchKardex"
          class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
          <option value="">Todos los almacenes</option>
          <option v-for="wh in warehouses" :key="wh.id" :value="wh.id">{{ wh.name }}</option>
        </select>
      </div>

      <!-- Selected Product Info -->
      <div v-if="selectedProduct" class="mt-6 p-4 bg-slate-50 rounded-xl">
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 bg-slate-200 rounded-lg overflow-hidden">
            <img v-if="selectedProduct.primary_image" :src="selectedProduct.primary_image" class="w-full h-full object-cover" />
            <div v-else class="w-full h-full flex items-center justify-center">
              <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
          </div>
          <div>
            <h3 class="text-lg font-bold text-slate-800">{{ selectedProduct.name }}</h3>
            <p class="text-slate-500">SKU: {{ selectedProduct.sku }} | Stock: {{ selectedProduct.total_stock }} und</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Date Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6">
      <div class="flex flex-wrap gap-4 items-end">
        <div>
          <label class="block text-sm text-slate-600 mb-1">Desde</label>
          <input v-model="filters.start_date" type="date" @change="fetchKardex"
            class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500" />
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Hasta</label>
          <input v-model="filters.end_date" type="date" @change="fetchKardex"
            class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500" />
        </div>
        <button @click="exportKardex" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
          </svg>
          Exportar
        </button>
      </div>
    </div>

    <!-- Kardex Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
      <div class="p-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-semibold text-slate-800">Movimientos</h3>
        <div class="flex gap-2">
          <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs">
            Saldo Inicial: {{ kardexData.initial_balance || 0 }}
          </span>
          <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">
            Saldo Final: {{ kardexData.final_balance || 0 }}
          </span>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/50">
              <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Fecha</th>
              <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Tipo</th>
              <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Concepto</th>
              <th class="text-right py-3 px-4 text-sm font-semibold text-slate-700">Entrada</th>
              <th class="text-right py-3 px-4 text-sm font-semibold text-slate-700">Salida</th>
              <th class="text-right py-3 px-4 text-sm font-semibold text-slate-700">Saldo</th>
              <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Almacén</th>
              <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Usuario</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="movement in movements" :key="movement.id" class="border-b border-slate-50 hover:bg-slate-50/50">
              <td class="py-3 px-4 text-sm text-slate-600">{{ formatDate(movement.created_at) }}</td>
              <td class="py-3 px-4">
                <span :class="['px-2 py-1 rounded-full text-xs font-medium',
                  isEntry(movement.movement_type) ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700']">
                  {{ isEntry(movement.movement_type) ? 'Entrada' : 'Salida' }}
                </span>
              </td>
              <td class="py-3 px-4 text-sm text-slate-700">{{ formatMovementType(movement.movement_type) }}</td>
              <td class="py-3 px-4 text-right text-sm font-medium text-emerald-600">
                {{ isEntry(movement.movement_type) ? movement.quantity : '-' }}
              </td>
              <td class="py-3 px-4 text-right text-sm font-medium text-rose-600">
                {{ !isEntry(movement.movement_type) ? Math.abs(movement.quantity) : '-' }}
              </td>
              <td class="py-3 px-4 text-right text-sm font-bold text-slate-800">{{ movement.running_balance }}</td>
              <td class="py-3 px-4 text-sm text-slate-600">{{ movement.warehouse_name || 'N/A' }}</td>
              <td class="py-3 px-4 text-sm text-slate-600">{{ movement.created_by_name || 'Sistema' }}</td>
            </tr>
            <tr v-if="movements.length === 0">
              <td colspan="8" class="text-center py-12 text-slate-500">
                Selecciona un producto para ver su kardex
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.last_page > 1" class="flex items-center justify-between px-4 py-3 border-t border-slate-100">
        <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1"
          class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 disabled:opacity-50 text-sm">
          Anterior
        </button>
        <span class="text-sm text-slate-500">Página {{ pagination.current_page }} de {{ pagination.last_page }}</span>
        <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page"
          class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 disabled:opacity-50 text-sm">
          Siguiente
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import apiClient from '../../services/api'

const route = useRoute()

const searchQuery = ref('')
const searchResults = ref([])
const selectedProduct = ref(null)
const warehouses = ref([])
const movements = ref([])
const kardexData = ref({})
const pagination = ref({ current_page: 1, last_page: 1 })

const filters = ref({
  product_id: route.query.product_id || '',
  warehouse_id: '',
  start_date: '',
  end_date: '',
})

let searchTimeout = null

function searchProducts() {
  clearTimeout(searchTimeout)
  if (searchQuery.value.length < 2) {
    searchResults.value = []
    return
  }
  searchTimeout = setTimeout(async () => {
    try {
      const response = await apiClient.get('/products', {
        params: { search: searchQuery.value, per_page: 5 }
      })
      searchResults.value = response.data.data
    } catch (err) {
      console.error('Search error:', err)
    }
  }, 300)
}

function selectProduct(product) {
  selectedProduct.value = product
  filters.value.product_id = product.id
  searchQuery.value = product.name
  searchResults.value = []
  fetchKardex()
}

async function fetchKardex() {
  if (!filters.value.product_id) return
  
  try {
    const response = await apiClient.get('/kardex', {
      params: {
        ...filters.value,
        page: pagination.value.current_page,
      }
    })
    kardexData.value = response.data
    movements.value = response.data.movements?.data || []
    pagination.value = {
      current_page: response.data.movements?.current_page || 1,
      last_page: response.data.movements?.last_page || 1,
    }
    if (!selectedProduct.value) {
      selectedProduct.value = response.data.product
    }
  } catch (err) {
    console.error('Kardex error:', err)
  }
}

function changePage(page) {
  pagination.value.current_page = page
  fetchKardex()
}

function isEntry(type) {
  return ['entrada_compra', 'entrada_devolucion_cliente', 'entrada_ajuste', 'entrada_transferencia'].includes(type)
}

function formatMovementType(type) {
  const types = {
    'entrada_compra': 'Compra',
    'entrada_devolucion_cliente': 'Devolución Cliente',
    'entrada_ajuste': 'Ajuste +',
    'entrada_transferencia': 'Transferencia In',
    'salida_venta': 'Venta',
    'salida_devolucion_proveedor': 'Devolución Proveedor',
    'salida_ajuste': 'Ajuste -',
    'salida_transferencia': 'Transferencia Out',
    'salida_merma': 'Merma',
  }
  return types[type] || type
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('es-MX', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

function exportKardex() {
  // Generate CSV
  const headers = ['Fecha', 'Tipo', 'Concepto', 'Entrada', 'Salida', 'Saldo', 'Almacén', 'Usuario']
  const rows = movements.value.map(m => [
    formatDate(m.created_at),
    isEntry(m.movement_type) ? 'Entrada' : 'Salida',
    formatMovementType(m.movement_type),
    isEntry(m.movement_type) ? m.quantity : '',
    !isEntry(m.movement_type) ? Math.abs(m.quantity) : '',
    m.running_balance,
    m.warehouse_name || '',
    m.created_by_name || ''
  ])
  
  const csv = [headers.join(','), ...rows.map(r => r.join(','))].join('\n')
  const blob = new Blob([csv], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `kardex_${selectedProduct.value?.sku || 'producto'}.csv`
  a.click()
  window.URL.revokeObjectURL(url)
}

onMounted(async () => {
  // Load warehouses
  try {
    const whResponse = await apiClient.get('/warehouses')
    warehouses.value = whResponse.data
  } catch (err) {
    console.error('Error loading warehouses:', err)
  }

  // If product_id in URL, load that product
  if (filters.value.product_id) {
    try {
      const prodResponse = await apiClient.get(`/products/${filters.value.product_id}`)
      selectedProduct.value = prodResponse.data
      searchQuery.value = prodResponse.data.name
      fetchKardex()
    } catch (err) {
      console.error('Error loading product:', err)
    }
  }
})
</script>
