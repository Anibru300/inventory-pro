<template>
  <div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
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
          <h1 class="text-3xl font-bold gradient-text font-heading mb-2">Vales de Movimiento</h1>
          <p class="text-cj-silver-dark font-tagline italic">Comprobantes de entradas y salidas</p>
        </div>
      </div>
      <div class="flex gap-3">
        <button @click="fetchStatistics" class="btn-secondary">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          Estadísticas
        </button>
      </div>
    </div>

    <!-- Statistics -->
    <div v-if="stats" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="stat-card border-l-4 border-cj-gold">
        <p class="text-cj-silver-dark text-xs uppercase">Hoy</p>
        <p class="text-2xl font-bold">{{ stats.today }}</p>
      </div>
      <div class="stat-card border-l-4 border-info">
        <p class="text-cj-silver-dark text-xs uppercase">Esta Semana</p>
        <p class="text-2xl font-bold">{{ stats.week }}</p>
      </div>
      <div class="stat-card border-l-4 border-success">
        <p class="text-cj-silver-dark text-xs uppercase">Este Mes</p>
        <p class="text-2xl font-bold">{{ stats.month }}</p>
      </div>
      <div class="stat-card border-l-4 border-warning">
        <p class="text-cj-silver-dark text-xs uppercase">Sin Imprimir</p>
        <p class="text-2xl font-bold">{{ stats.pending_print }}</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="card-premium p-4 mb-6">
      <div class="flex flex-wrap gap-4 items-end">
        <div>
          <label class="block text-sm text-cj-silver-dark mb-1">Folio</label>
          <input v-model="filters.folio" type="text" placeholder="Ej: SAL-2026-00001" class="w-48" />
        </div>
        <div>
          <label class="block text-sm text-cj-silver-dark mb-1">Tipo</label>
          <select v-model="filters.type" class="w-32">
            <option value="">Todos</option>
            <option value="entry">Entrada</option>
            <option value="exit">Salida</option>
          </select>
        </div>
        <div>
          <label class="block text-sm text-cj-silver-dark mb-1">Desde</label>
          <input v-model="filters.date_from" type="date" class="w-40" />
        </div>
        <div>
          <label class="block text-sm text-cj-silver-dark mb-1">Hasta</label>
          <input v-model="filters.date_to" type="date" class="w-40" />
        </div>
        <button @click="applyFilters" class="btn-primary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          Buscar
        </button>
        <button @click="resetFilters" class="btn-secondary">
          Limpiar
        </button>
      </div>
    </div>

    <!-- Receipts Table -->
    <div class="card-premium p-6">
      <div class="overflow-x-auto">
        <table class="data-table">
          <thead>
            <tr>
              <th>Folio</th>
              <th>Tipo</th>
              <th>Fecha</th>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Almacén</th>
              <th>Destinatario</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="receipt in receipts" :key="receipt.id">
              <td class="font-mono text-cj-gold font-semibold">{{ receipt.folio }}</td>
              <td>
                <span :class="['px-2 py-1 rounded-full text-xs font-medium', 
                  receipt.type === 'entry' ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger']">
                  {{ receipt.type === 'entry' ? 'Entrada' : 'Salida' }}
                </span>
              </td>
              <td class="text-cj-silver">{{ formatDate(receipt.created_at) }}</td>
              <td class="font-medium">{{ receipt.product?.name }}</td>
              <td class="font-bold" :class="receipt.type === 'entry' ? 'text-success' : 'text-danger'">
                {{ receipt.type === 'entry' ? '+' : '-' }}{{ receipt.quantity }}
              </td>
              <td class="text-cj-silver">{{ receipt.warehouse?.name }}</td>
              <td class="text-cj-silver">{{ receipt.recipient_name || '-' }}</td>
              <td>
                <span v-if="receipt.printed_at" class="text-cj-silver-dark text-xs">
                  <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                  </svg>
                  Impreso
                </span>
                <span v-else class="text-warning text-xs">
                  <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Pendiente
                </span>
              </td>
              <td>
                <div class="flex gap-2">
                  <button @click="downloadPdf(receipt)" class="p-2 text-cj-silver-dark hover:text-cj-gold transition-colors" title="Descargar PDF">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </button>
                  <button @click="viewPdf(receipt)" class="p-2 text-cj-silver-dark hover:text-info transition-colors" title="Ver PDF">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="receipts.length === 0">
              <td colspan="9" class="text-center py-8 text-cj-silver-dark">
                No hay vales registrados
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.lastPage > 1" class="flex items-center justify-between mt-6 pt-6 border-t border-white/10">
        <p class="text-sm text-cj-silver-dark">
          Mostrando {{ receipts.length }} de {{ pagination.total }} vales
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

const receipts = ref([])
const stats = ref(null)
const filters = ref({
  folio: '',
  type: '',
  date_from: '',
  date_to: '',
})
const pagination = ref({
  currentPage: 1,
  lastPage: 1,
  total: 0,
})

const apiUrl = import.meta.env.VITE_API_URL || 'https://inventory-pro-api-v2.onrender.com/api'

function formatDate(date) {
  return new Date(date).toLocaleDateString('es-ES', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

async function fetchReceipts() {
  try {
    const params = {
      page: pagination.value.currentPage,
      ...filters.value,
    }
    const response = await apiClient.get('/receipts', { params })
    receipts.value = response.data.data
    pagination.value = {
      currentPage: response.data.current_page,
      lastPage: response.data.last_page,
      total: response.data.total,
    }
  } catch (err) {
    console.error('Error fetching receipts:', err)
  }
}

async function fetchStatistics() {
  try {
    const response = await apiClient.get('/receipts/statistics')
    stats.value = response.data
  } catch (err) {
    console.error('Error fetching statistics:', err)
  }
}

function applyFilters() {
  pagination.value.currentPage = 1
  fetchReceipts()
}

function resetFilters() {
  filters.value = {
    folio: '',
    type: '',
    date_from: '',
    date_to: '',
  }
  pagination.value.currentPage = 1
  fetchReceipts()
}

function changePage(page) {
  pagination.value.currentPage = page
  fetchReceipts()
}

function downloadPdf(receipt) {
  window.open(`${apiUrl}/receipts/${receipt.id}/pdf`, '_blank')
}

function viewPdf(receipt) {
  window.open(`${apiUrl}/receipts/${receipt.id}/preview`, '_blank')
}

onMounted(() => {
  fetchReceipts()
  fetchStatistics()
})
</script>