<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
    <!-- Header with Back Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <div class="flex items-center gap-4">
        <button 
          @click="$router.push('/menu')"
          class="flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm"
          title="Volver al menú"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
        </button>
        <div>
          <h1 class="text-3xl font-bold text-slate-800 mb-1">Transferencias</h1>
          <p class="text-slate-500">Gestiona las transferencias entre almacenes</p>
        </div>
      </div>
      <router-link to="/transfers/new" 
        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 hover:shadow-xl hover:shadow-blue-600/30">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nueva Transferencia
      </router-link>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <div class="text-2xl font-bold text-slate-800">{{ stats.pending || 0 }}</div>
            <div class="text-sm text-slate-500">Pendientes</div>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <div>
            <div class="text-2xl font-bold text-slate-800">{{ stats.in_transit || 0 }}</div>
            <div class="text-sm text-slate-500">En Tránsito</div>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <div class="text-2xl font-bold text-slate-800">{{ stats.received || 0 }}</div>
            <div class="text-sm text-slate-500">Recibidas (Hoy)</div>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <div>
            <div class="text-2xl font-bold text-slate-800">{{ stats.to_receive || 0 }}</div>
            <div class="text-sm text-slate-500">Por Recibir</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
      <div class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
          <div class="relative">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
              v-model="filters.search"
              type="text"
              placeholder="Buscar por número..."
              class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
              @input="handleSearch"
            />
          </div>
        </div>
        <select
          v-model="filters.status"
          class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 min-w-[180px]"
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
          class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 min-w-[180px]"
          @change="fetchTransfers"
        >
          <option value="">Todos los almacenes</option>
          <option v-for="w in warehouses" :key="w.id" :value="w.id">{{ w.name }}</option>
        </select>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
      <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-100">
          <tr>
            <th class="text-left text-slate-600 font-semibold p-4">Número</th>
            <th class="text-left text-slate-600 font-semibold p-4">Origen</th>
            <th class="text-left text-slate-600 font-semibold p-4">Destino</th>
            <th class="text-left text-slate-600 font-semibold p-4">Fecha</th>
            <th class="text-left text-slate-600 font-semibold p-4">Items</th>
            <th class="text-left text-slate-600 font-semibold p-4">Valor</th>
            <th class="text-left text-slate-600 font-semibold p-4">Estado</th>
            <th class="text-left text-slate-600 font-semibold p-4">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="transfer in transfers" :key="transfer.id" class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
            <td class="p-4 text-slate-800 font-medium">{{ transfer.transfer_number }}</td>
            <td class="p-4 text-slate-600">{{ transfer.source_warehouse?.name }}</td>
            <td class="p-4 text-slate-600">{{ transfer.destination_warehouse?.name }}</td>
            <td class="p-4 text-slate-600">{{ formatDate(transfer.transfer_date) }}</td>
            <td class="p-4 text-slate-600">{{ transfer.total_items }}</td>
            <td class="p-4 text-blue-600 font-semibold">${{ formatNumber(transfer.total_value) }}</td>
            <td class="p-4">
              <span :class="getStatusBadgeClass(transfer.status)">
                {{ getStatusLabel(transfer.status) }}
              </span>
            </td>
            <td class="p-4">
              <div class="flex gap-2">
                <router-link
                  :to="`/transfers/${transfer.id}`"
                  class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
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
            <td colspan="8" class="p-12 text-center text-slate-500">
              <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
              <p class="text-lg font-medium">No se encontraron transferencias</p>
              <p class="text-sm">Crea una nueva transferencia para comenzar</p>
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
            'px-4 py-2 rounded-xl font-medium transition-all',
            page === pagination.current_page
              ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20'
              : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
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
    yellow: 'bg-amber-100 text-amber-700 border border-amber-200',
    blue: 'bg-blue-100 text-blue-700 border border-blue-200',
    indigo: 'bg-indigo-100 text-indigo-700 border border-indigo-200',
    green: 'bg-emerald-100 text-emerald-700 border border-emerald-200',
    orange: 'bg-orange-100 text-orange-700 border border-orange-200',
    gray: 'bg-slate-100 text-slate-700 border border-slate-200',
    red: 'bg-rose-100 text-rose-700 border border-rose-200',
  }
  return `px-3 py-1 rounded-full text-sm font-medium ${classes[color] || classes.gray}`
}

onMounted(() => {
  fetchTransfers()
  fetchStats()
  fetchWarehouses()
})
</script>
