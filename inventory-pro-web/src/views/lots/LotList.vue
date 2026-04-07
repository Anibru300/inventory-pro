<template>
  <div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-white">Lotes de Productos</h1>
        <p class="text-cj-silver mt-1">Gestiona los lotes y su trazabilidad</p>
      </div>
      <router-link
        to="/lots/new"
        class="bg-cj-gold text-cj-navy px-4 py-2 rounded-lg font-medium hover:bg-cj-gold/90 transition-colors flex items-center gap-2"
      >
        <span class="text-lg">+</span>
        Nuevo Lote
      </router-link>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-cj-navy/50 border border-cj-gold/30 rounded-lg p-4">
        <div class="text-cj-silver text-sm">Total Lotes</div>
        <div class="text-2xl font-bold text-white">{{ stats.total_lots || 0 }}</div>
      </div>
      <div class="bg-cj-navy/50 border border-cj-gold/30 rounded-lg p-4">
        <div class="text-cj-silver text-sm">Activos</div>
        <div class="text-2xl font-bold text-green-400">{{ stats.active_lots || 0 }}</div>
      </div>
      <div class="bg-cj-navy/50 border border-cj-gold/30 rounded-lg p-4">
        <div class="text-cj-silver text-sm">Por Vencer (30 días)</div>
        <div class="text-2xl font-bold text-yellow-400">{{ stats.expiring_soon || 0 }}</div>
      </div>
      <div class="bg-cj-navy/50 border border-cj-gold/30 rounded-lg p-4">
        <div class="text-cj-silver text-sm">Valor Total</div>
        <div class="text-2xl font-bold text-cj-gold">${{ formatNumber(stats.total_value) }}</div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-cj-navy/30 border border-cj-gold/20 rounded-lg p-4 mb-6">
      <div class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
          <input
            v-model="filters.search"
            type="text"
            placeholder="Buscar por número de lote..."
            class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white placeholder-cj-silver/50 focus:border-cj-gold focus:outline-none"
            @input="handleSearch"
          />
        </div>
        <select
          v-model="filters.status"
          class="px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
          @change="fetchLots"
        >
          <option value="">Todos los estados</option>
          <option value="active">Activo</option>
          <option value="depleted">Agotado</option>
          <option value="expired">Vencido</option>
          <option value="quarantine">Cuarentena</option>
        </select>
        <select
          v-model="filters.product_id"
          class="px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
          @change="fetchLots"
        >
          <option value="">Todos los productos</option>
          <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }}</option>
        </select>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-cj-navy/30 border border-cj-gold/20 rounded-lg overflow-hidden">
      <table class="w-full">
        <thead class="bg-cj-navy/50">
          <tr>
            <th class="text-left text-cj-gold font-medium p-4">Lote</th>
            <th class="text-left text-cj-gold font-medium p-4">Producto</th>
            <th class="text-left text-cj-gold font-medium p-4">Almacén</th>
            <th class="text-left text-cj-gold font-medium p-4">Cantidad</th>
            <th class="text-left text-cj-gold font-medium p-4">Costo</th>
            <th class="text-left text-cj-gold font-medium p-4">Vencimiento</th>
            <th class="text-left text-cj-gold font-medium p-4">Estado</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="lot in lots" :key="lot.id" class="border-t border-cj-gold/10 hover:bg-cj-gold/5">
            <td class="p-4 text-white font-medium">{{ lot.lot_number }}</td>
            <td class="p-4 text-cj-silver">{{ lot.product?.name }}</td>
            <td class="p-4 text-cj-silver">{{ lot.warehouse?.name }}</td>
            <td class="p-4 text-cj-silver">
              {{ lot.remaining_quantity }} / {{ lot.initial_quantity }}
              <div class="w-24 h-1 bg-cj-navy rounded-full mt-1">
                <div 
                  class="h-full bg-cj-gold rounded-full"
                  :style="{ width: (lot.remaining_quantity / lot.initial_quantity * 100) + '%' }"
                ></div>
              </div>
            </td>
            <td class="p-4 text-cj-gold">${{ formatNumber(lot.unit_cost) }}</td>
            <td class="p-4">
              <span :class="getExpiryClass(lot)">
                {{ lot.expiry_date ? formatDate(lot.expiry_date) : 'N/A' }}
              </span>
            </td>
            <td class="p-4">
              <span :class="getStatusBadgeClass(lot.status)">
                {{ getStatusLabel(lot.status) }}
              </span>
            </td>
          </tr>
          <tr v-if="lots.length === 0">
            <td colspan="7" class="p-8 text-center text-cj-silver">
              No se encontraron lotes
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="pagination.last_page > 1" class="flex justify-center mt-6">
      <div class="flex gap-2">
        <button
          v-for="page in pagination.last_page"
          :key="page"
          @click="goToPage(page)"
          :class="[
            'px-4 py-2 rounded-lg font-medium transition-colors',
            page === pagination.current_page
              ? 'bg-cj-gold text-cj-navy'
              : 'bg-cj-navy/50 text-cj-silver hover:bg-cj-gold/20'
          ]"
        >
          {{ page }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { lotService } from '@/services/lotService'
import { productService } from '@/services/productService'
import { useDebounce } from '@/composables/useDebounce'

const lots = ref([])
const products = ref([])
const stats = ref({})
const pagination = ref({
  current_page: 1,
  last_page: 1,
})

const filters = ref({
  search: '',
  status: '',
  product_id: '',
})

const { debounce } = useDebounce()

const fetchLots = async () => {
  try {
    const response = await lotService.getAll({
      ...filters.value,
      page: pagination.value.current_page,
    })
    lots.value = response.data.data
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
    }
  } catch (error) {
    console.error('Error fetching lots:', error)
  }
}

const fetchStats = async () => {
  try {
    const response = await lotService.getStats()
    stats.value = response.data
  } catch (error) {
    console.error('Error fetching stats:', error)
  }
}

const fetchProducts = async () => {
  try {
    const response = await productService.getAll()
    products.value = response.data.data || response.data
  } catch (error) {
    console.error('Error fetching products:', error)
  }
}

const handleSearch = debounce(() => {
  pagination.value.current_page = 1
  fetchLots()
}, 300)

const goToPage = (page) => {
  pagination.value.current_page = page
  fetchLots()
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-ES')
}

const formatNumber = (num) => {
  return Number(num || 0).toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

const getStatusLabel = (status) => {
  return lotService.getStatusLabel(status)
}

const getStatusBadgeClass = (status) => {
  const color = lotService.getStatusColor(status)
  const classes = {
    green: 'bg-green-500/20 text-green-400 border border-green-500/30',
    gray: 'bg-gray-500/20 text-gray-400 border border-gray-500/30',
    red: 'bg-red-500/20 text-red-400 border border-red-500/30',
    yellow: 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
  }
  return `px-3 py-1 rounded-full text-sm font-medium ${classes[color] || classes.gray}`
}

const getExpiryClass = (lot) => {
  if (!lot.expiry_date) return 'text-cj-silver'
  const days = lot.days_until_expiry
  if (days < 0) return 'text-red-400 font-medium'
  if (days <= 7) return 'text-orange-400 font-medium'
  if (days <= 30) return 'text-yellow-400'
  return 'text-green-400'
}

onMounted(() => {
  fetchLots()
  fetchStats()
  fetchProducts()
})
</script>
