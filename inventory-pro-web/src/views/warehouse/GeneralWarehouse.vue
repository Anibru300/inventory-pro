<template>
  <div class="p-6">
    <!-- Page Header -->
    <PageHeader title="Almacén General" subtitle="Vista consolidada de inventario en todos los almacenes">
      <template #actions>
        <button @click="exportToExcel" 
          class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium transition-colors flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Exportar
        </button>
      </template>
    </PageHeader>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
        <p class="text-sm text-slate-500 dark:text-slate-400">Total Productos</p>
        <p class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ stats.total_products }}</p>
      </div>
      <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
        <p class="text-sm text-slate-500 dark:text-slate-400">Valor Total</p>
        <p class="text-2xl font-bold text-emerald-600 mt-1">${{ formatNumber(stats.total_value) }}</p>
      </div>
      <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
        <p class="text-sm text-slate-500 dark:text-slate-400">Stock Bajo</p>
        <p class="text-2xl font-bold text-amber-600 mt-1">{{ stats.low_stock_count }}</p>
      </div>
      <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
        <p class="text-sm text-slate-500 dark:text-slate-400">Almacenes</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">{{ stats.warehouse_count }}</p>
      </div>
    </div>

    <!-- Warehouse Filter -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-200 dark:border-slate-700 mb-6">
      <div class="flex flex-wrap gap-2">
        <button 
          @click="selectedWarehouse = ''"
          :class="['px-4 py-2 rounded-lg font-medium transition-colors', 
            selectedWarehouse === '' 
              ? 'bg-blue-600 text-white' 
              : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300']">
          Todos
        </button>
        <button 
          v-for="wh in warehouses" 
          :key="wh.id"
          @click="selectedWarehouse = wh.id"
          :class="['px-4 py-2 rounded-lg font-medium transition-colors', 
            selectedWarehouse === wh.id 
              ? 'bg-blue-600 text-white' 
              : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300']">
          {{ wh.name }}
        </button>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-200 dark:border-slate-700 mb-6">
      <div class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[300px]">
          <div class="relative">
            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input 
              v-model="searchQuery"
              type="text"
              placeholder="Buscar producto..."
              class="w-full pl-12 pr-4 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl focus:outline-none focus:border-blue-500 text-slate-800 dark:text-white"
            />
          </div>
        </div>
        <select v-model="stockFilter" class="px-4 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl">
          <option value="">Todos los stocks</option>
          <option value="low">Stock bajo</option>
          <option value="normal">Stock normal</option>
          <option value="excess">Exceso de stock</option>
        </select>
        <select v-model="categoryFilter" class="px-4 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl">
          <option value="">Todas las categorías</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
        </select>
      </div>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-slate-50 dark:bg-slate-700">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600 dark:text-slate-300">Producto</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600 dark:text-slate-300">Categoría</th>
              <th class="px-4 py-3 text-right text-sm font-semibold text-slate-600 dark:text-slate-300">Stock Total</th>
              <th v-for="wh in visibleWarehouses" :key="wh.id" class="px-4 py-3 text-right text-sm font-semibold text-slate-600 dark:text-slate-300">
                {{ wh.name }}
              </th>
              <th class="px-4 py-3 text-right text-sm font-semibold text-slate-600 dark:text-slate-300">Valor</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-600 dark:text-slate-300">Estado</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            <tr v-for="item in filteredInventory" :key="item.product_id" class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
              <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                  <div v-if="item.image" class="w-10 h-10 rounded-lg bg-cover bg-center" :style="{ backgroundImage: `url(${item.image})` }"></div>
                  <div v-else class="w-10 h-10 rounded-lg bg-slate-200 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div>
                    <p class="font-medium text-slate-800 dark:text-white">{{ item.product_name }}</p>
                    <p class="text-xs text-slate-500">{{ item.sku }}</p>
                  </div>
                </div>
              </td>
              <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">{{ item.category }}</td>
              <td class="px-4 py-3 text-right font-medium text-slate-800 dark:text-white">{{ item.total_stock }}</td>
              <td v-for="wh in visibleWarehouses" :key="wh.id" class="px-4 py-3 text-right text-sm text-slate-600 dark:text-slate-400">
                {{ item.warehouse_stock[wh.id] || 0 }}
              </td>
              <td class="px-4 py-3 text-right text-sm text-slate-600 dark:text-slate-400">${{ formatNumber(item.total_value) }}</td>
              <td class="px-4 py-3 text-center">
                <span :class="['px-2 py-1 rounded-full text-xs font-medium', getStockStatusClass(item)]">
                  {{ getStockStatus(item) }}
                </span>
              </td>
            </tr>
            <tr v-if="filteredInventory.length === 0">
              <td :colspan="5 + visibleWarehouses.length" class="px-4 py-8 text-center text-slate-500">
                No se encontraron productos
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between">
        <p class="text-sm text-slate-500">
          Mostrando {{ filteredInventory.length }} de {{ inventory.length }} productos
        </p>
        <div class="flex gap-2">
          <button 
            @click="currentPage--"
            :disabled="currentPage === 1"
            class="px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-600 disabled:opacity-50">
            Anterior
          </button>
          <button 
            @click="currentPage++"
            :disabled="currentPage >= totalPages"
            class="px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-600 disabled:opacity-50">
            Siguiente
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useDarkMode } from '../../composables/useDarkMode'
import PageHeader from '../../components/PageHeader.vue'
import apiClient from '../../services/api'

const { isDark } = useDarkMode()

const inventory = ref([])
const warehouses = ref([])
const categories = ref([])
const stats = ref({
  total_products: 0,
  total_value: 0,
  low_stock_count: 0,
  warehouse_count: 0
})

const searchQuery = ref('')
const stockFilter = ref('')
const categoryFilter = ref('')
const selectedWarehouse = ref('')
const currentPage = ref(1)
const itemsPerPage = 50

const visibleWarehouses = computed(() => {
  if (!selectedWarehouse.value) return warehouses.value
  return warehouses.value.filter(w => w.id === selectedWarehouse.value)
})

const filteredInventory = computed(() => {
  let result = inventory.value

  // Search filter
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    result = result.filter(item => 
      item.product_name.toLowerCase().includes(q) ||
      item.sku.toLowerCase().includes(q)
    )
  }

  // Stock filter
  if (stockFilter.value) {
    result = result.filter(item => {
      const ratio = item.total_stock / (item.min_stock || 1)
      if (stockFilter.value === 'low') return ratio < 1
      if (stockFilter.value === 'normal') return ratio >= 1 && ratio < 3
      if (stockFilter.value === 'excess') return ratio >= 3
      return true
    })
  }

  // Category filter
  if (categoryFilter.value) {
    result = result.filter(item => item.category_id === categoryFilter.value)
  }

  // Pagination
  const start = (currentPage.value - 1) * itemsPerPage
  return result.slice(start, start + itemsPerPage)
})

const totalPages = computed(() => Math.ceil(filteredInventory.value.length / itemsPerPage))

function getStockStatus(item) {
  const ratio = item.total_stock / (item.min_stock || 1)
  if (ratio < 1) return 'Crítico'
  if (ratio < 1.5) return 'Bajo'
  if (ratio > 5) return 'Exceso'
  return 'Normal'
}

function getStockStatusClass(item) {
  const ratio = item.total_stock / (item.min_stock || 1)
  if (ratio < 1) return 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300'
  if (ratio < 1.5) return 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300'
  if (ratio > 5) return 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300'
  return 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300'
}

function formatNumber(num) {
  return new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num)
}

async function loadData() {
  try {
    const [invResponse, whResponse, catResponse] = await Promise.all([
      apiClient.get('/inventory/general'),
      apiClient.get('/warehouses'),
      apiClient.get('/categories')
    ])
    
    inventory.value = invResponse.data.inventory || []
    warehouses.value = invResponse.data.warehouses || []
    categories.value = catResponse.data || []
    
    stats.value = {
      total_products: invResponse.data.total_products || 0,
      total_value: invResponse.data.total_value || 0,
      low_stock_count: invResponse.data.low_stock_count || 0,
      warehouse_count: warehouses.value.length
    }
  } catch (err) {
    console.error('Error loading general warehouse data:', err)
  }
}

function exportToExcel() {
  // Simple CSV export for now
  const headers = ['Producto', 'SKU', 'Categoría', 'Stock Total', ...warehouses.value.map(w => w.name), 'Valor Total']
  const rows = filteredInventory.value.map(item => [
    item.product_name,
    item.sku,
    item.category,
    item.total_stock,
    ...warehouses.value.map(w => item.warehouse_stock[w.id] || 0),
    item.total_value
  ])
  
  const csv = [headers, ...rows].map(r => r.join(',')).join('\n')
  const blob = new Blob([csv], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `inventario_general_${new Date().toISOString().split('T')[0]}.csv`
  a.click()
}

onMounted(loadData)
</script>
