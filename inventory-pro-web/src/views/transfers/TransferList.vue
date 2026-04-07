<template>
  <div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-white">Transferencias</h1>
        <p class="text-cj-silver mt-1">Gestiona las transferencias entre almacenes</p>
      </div>
      <router-link
        to="/transfers/new"
        class="bg-cj-gold text-cj-navy px-4 py-2 rounded-lg font-medium hover:bg-cj-gold/90 transition-colors flex items-center gap-2"
      >
        <span class="text-lg">+</span>
        Nueva Transferencia
      </router-link>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-cj-navy/50 border border-cj-gold/30 rounded-lg p-4">
        <div class="text-cj-silver text-sm">Pendientes</div>
        <div class="text-2xl font-bold text-yellow-400">{{ stats.pending || 0 }}</div>
      </div>
      <div class="bg-cj-navy/50 border border-cj-gold/30 rounded-lg p-4">
        <div class="text-cj-silver text-sm">En Tránsito</div>
        <div class="text-2xl font-bold text-indigo-400">{{ stats.in_transit || 0 }}</div>
      </div>
      <div class="bg-cj-navy/50 border border-cj-gold/30 rounded-lg p-4">
        <div class="text-cj-silver text-sm">Recibidas (Hoy)</div>
        <div class="text-2xl font-bold text-green-400">{{ stats.received || 0 }}</div>
      </div>
      <div class="bg-cj-navy/50 border border-cj-gold/30 rounded-lg p-4">
        <div class="text-cj-silver text-sm">Por Recibir</div>
        <div class="text-2xl font-bold text-cj-gold">{{ stats.to_receive || 0 }}</div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-cj-navy/30 border border-cj-gold/20 rounded-lg p-4 mb-6">
      <div class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
          <input
            v-model="filters.search"
            type="text"
            placeholder="Buscar por número..."
            class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white placeholder-cj-silver/50 focus:border-cj-gold focus:outline-none"
            @input="handleSearch"
          />
        </div>
        <select
          v-model="filters.status"
          class="px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
          @change="fetchTransfers"
        >
          <option value="">Todos los estados</option>
          <option value="pending">Pendiente</option>
          <option value="preparing">En Preparación</option>
          <option value="in_transit">En Tránsito</option>
          <option value="received">Recibida</option>
          <option value="partially_received">Parcialmente Recibida</option>
          <option value="cancelled">Cancelada</option>
        </select>
        <select
          v-model="filters.warehouse_id"
          class="px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
          @change="fetchTransfers"
        >
          <option value="">Todos los almacenes</option>
          <option v-for="w in warehouses" :key="w.id" :value="w.id">{{ w.name }}</option>
        </select>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-cj-navy/30 border border-cj-gold/20 rounded-lg overflow-hidden">
      <table class="w-full">
        <thead class="bg-cj-navy/50">
          <tr>
            <th class="text-left text-cj-gold font-medium p-4">Número</th>
            <th class="text-left text-cj-gold font-medium p-4">Origen</th>
            <th class="text-left text-cj-gold font-medium p-4">Destino</th>
            <th class="text-left text-cj-gold font-medium p-4">Fecha</th>
            <th class="text-left text-cj-gold font-medium p-4">Items</th>
            <th class="text-left text-cj-gold font-medium p-4">Valor</th>
            <th class="text-left text-cj-gold font-medium p-4">Estado</th>
            <th class="text-left text-cj-gold font-medium p-4">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="transfer in transfers" :key="transfer.id" class="border-t border-cj-gold/10 hover:bg-cj-gold/5">
            <td class="p-4 text-white font-medium">{{ transfer.transfer_number }}</td>
            <td class="p-4 text-cj-silver">{{ transfer.source_warehouse?.name }}</td>
            <td class="p-4 text-cj-silver">{{ transfer.destination_warehouse?.name }}</td>
            <td class="p-4 text-cj-silver">{{ formatDate(transfer.transfer_date) }}</td>
            <td class="p-4 text-cj-silver">{{ transfer.total_items }}</td>
            <td class="p-4 text-cj-gold">${{ formatNumber(transfer.total_value) }}</td>
            <td class="p-4">
              <span :class="getStatusBadgeClass(transfer.status)">
                {{ getStatusLabel(transfer.status) }}
              </span>
            </td>
            <td class="p-4">
              <div class="flex gap-2">
                <router-link
                  :to="`/transfers/${transfer.id}`"
                  class="text-cj-gold hover:text-cj-gold/80"
                  title="Ver detalle"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                </router-link>
              </div>
            </td>
          </tr>
          <tr v-if="transfers.length === 0">
            <td colspan="8" class="p-8 text-center text-cj-silver">
              No se encontraron transferencias
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
import { transferService } from '@/services/transferService'
import { warehouseService } from '@/services/warehouseService'
import { useDebounce } from '@/composables/useDebounce'

const transfers = ref([])
const warehouses = ref([])
const stats = ref({})
const pagination = ref({
  current_page: 1,
  last_page: 1,
})

const filters = ref({
  search: '',
  status: '',
  warehouse_id: '',
})

const { debounce } = useDebounce()

const fetchTransfers = async () => {
  try {
    const response = await transferService.getAll({
      ...filters.value,
      page: pagination.value.current_page,
    })
    transfers.value = response.data.data
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
    }
  } catch (error) {
    console.error('Error fetching transfers:', error)
  }
}

const fetchStats = async () => {
  try {
    const response = await transferService.getStats()
    stats.value = {
      pending: response.data.by_status?.pending || 0,
      in_transit: response.data.by_status?.in_transit || 0,
      received: response.data.by_status?.received || 0,
      to_receive: response.data.pending_received || 0,
    }
  } catch (error) {
    console.error('Error fetching stats:', error)
  }
}

const fetchWarehouses = async () => {
  try {
    const response = await warehouseService.getAll()
    warehouses.value = response.data
  } catch (error) {
    console.error('Error fetching warehouses:', error)
  }
}

const handleSearch = debounce(() => {
  pagination.value.current_page = 1
  fetchTransfers()
}, 300)

const goToPage = (page) => {
  pagination.value.current_page = page
  fetchTransfers()
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-ES')
}

const formatNumber = (num) => {
  return Number(num || 0).toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

const getStatusLabel = (status) => {
  return transferService.getStatusLabel(status)
}

const getStatusBadgeClass = (status) => {
  const color = transferService.getStatusColor(status)
  const classes = {
    yellow: 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
    blue: 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
    indigo: 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/30',
    green: 'bg-green-500/20 text-green-400 border border-green-500/30',
    orange: 'bg-orange-500/20 text-orange-400 border border-orange-500/30',
    gray: 'bg-gray-500/20 text-gray-400 border border-gray-500/30',
    red: 'bg-red-500/20 text-red-400 border border-red-500/30',
  }
  return `px-3 py-1 rounded-full text-sm font-medium ${classes[color] || classes.gray}`
}

onMounted(() => {
  fetchTransfers()
  fetchStats()
  fetchWarehouses()
})
</script>
