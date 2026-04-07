<template>
  <div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
      <div>
        <h1 class="text-3xl font-bold gradient-text font-heading mb-2">Movimientos de Inventario</h1>
        <p class="text-cj-silver-dark font-tagline italic">Control de entradas y salidas</p>
      </div>
      <div class="flex gap-3">
        <button @click="showFilters = !showFilters" class="btn-secondary">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
          </svg>
          Filtros
        </button>
        <router-link to="/movements/new" class="btn-primary">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Nuevo Movimiento
        </router-link>
      </div>
    </div>

    <!-- Filters -->
    <div v-if="showFilters" class="card-premium p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">Tipo</label>
          <select v-model="filters.type" class="w-full">
            <option value="">Todos</option>
            <option value="entry">Entrada</option>
            <option value="exit">Salida</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">Desde</label>
          <input v-model="filters.dateFrom" type="date" class="w-full" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">Hasta</label>
          <input v-model="filters.dateTo" type="date" class="w-full" />
        </div>
        <div class="flex items-end">
          <button @click="applyFilters" class="btn-primary w-full">Aplicar</button>
        </div>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
      <div class="stat-card border-l-4 border-success">
        <p class="text-cj-silver-dark text-sm font-heading uppercase">Entradas del Mes</p>
        <p class="text-2xl font-bold mt-1 text-success">{{ summary.entries }}</p>
        <p class="text-xs text-cj-silver-dark mt-1">Unidades: +{{ summary.entryUnits }}</p>
      </div>
      <div class="stat-card border-l-4 border-danger">
        <p class="text-cj-silver-dark text-sm font-heading uppercase">Salidas del Mes</p>
        <p class="text-2xl font-bold mt-1 text-danger">{{ summary.exits }}</p>
        <p class="text-xs text-cj-silver-dark mt-1">Unidades: -{{ summary.exitUnits }}</p>
      </div>
      <div class="stat-card border-l-4 border-cj-gold">
        <p class="text-cj-silver-dark text-sm font-heading uppercase">Balance</p>
        <p class="text-2xl font-bold mt-1 gradient-text">{{ summary.balance }}</p>
        <p class="text-xs text-cj-silver-dark mt-1">Diferencia neto</p>
      </div>
    </div>

    <!-- Movements Table -->
    <div class="card-premium p-6">
      <div class="overflow-x-auto">
        <table class="data-table">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Producto</th>
              <th>Tipo</th>
              <th>Cantidad</th>
              <th>Almacén</th>
              <th>Referencia</th>
              <th>Usuario</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="movement in movements" :key="movement.id">
              <td class="text-cj-silver">{{ formatDate(movement.created_at) }}</td>
              <td class="font-medium">{{ movement.product?.name }}</td>
              <td>
                <span :class="['px-3 py-1 rounded-full text-xs font-medium border', 
                  movement.type === 'entry' ? 'bg-success/10 text-success border-success/20' : 'bg-danger/10 text-danger border-danger/20']">
                  {{ movement.type === 'entry' ? 'Entrada' : 'Salida' }}
                </span>
              </td>
              <td class="font-bold" :class="movement.type === 'entry' ? 'text-success' : 'text-danger'">
                {{ movement.type === 'entry' ? '+' : '-' }}{{ movement.quantity }}
              </td>
              <td class="text-cj-silver">{{ movement.warehouse?.name }}</td>
              <td class="text-cj-silver text-sm">{{ movement.reference_number || '-' }}</td>
              <td class="text-cj-silver text-sm">{{ movement.user?.name }}</td>
            </tr>
            <tr v-if="movements.length === 0">
              <td colspan="7" class="text-center py-8 text-cj-silver-dark">
                No hay movimientos registrados
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.lastPage > 1" class="flex items-center justify-between mt-6 pt-6 border-t border-white/10">
        <p class="text-sm text-cj-silver-dark">
          Mostrando {{ movements.length }} de {{ pagination.total }} movimientos
        </p>
        <div class="flex gap-2">
          <button @click="changePage(pagination.currentPage - 1)" :disabled="pagination.currentPage === 1" class="btn-secondary py-2 px-4 text-sm">
            Anterior
          </button>
          <button @click="changePage(pagination.currentPage + 1)" :disabled="pagination.currentPage === pagination.lastPage" class="btn-secondary py-2 px-4 text-sm">
            Siguiente
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import apiClient from '../../services/api'

const movements = ref([])
const showFilters = ref(false)
const filters = ref({ type: '', dateFrom: '', dateTo: '' })
const summary = ref({ entries: 0, exits: 0, entryUnits: 0, exitUnits: 0, balance: 0 })
const pagination = ref({ currentPage: 1, lastPage: 1, total: 0 })

function formatDate(date) {
  return new Date(date).toLocaleDateString('es-ES', {
    day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
  })
}

async function fetchMovements() {
  try {
    const response = await apiClient.get('/stock-movements', {
      params: { page: pagination.value.currentPage, ...filters.value }
    })
    movements.value = response.data.data
    pagination.value = {
      currentPage: response.data.current_page,
      lastPage: response.data.last_page,
      total: response.data.total
    }
  } catch (err) {
    console.error('Error fetching movements:', err)
  }
}

async function fetchSummary() {
  try {
    const response = await apiClient.get('/stock-movements/summary')
    summary.value = response.data
  } catch (err) {
    console.error('Error fetching summary:', err)
  }
}

function applyFilters() {
  pagination.value.currentPage = 1
  fetchMovements()
}

function changePage(page) {
  pagination.value.currentPage = page
  fetchMovements()
}

onMounted(() => {
  fetchMovements()
  fetchSummary()
})
</script>
