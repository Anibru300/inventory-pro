<template>
  <div class="p-6">
    <!-- Page Header -->
    <PageHeader title="Movimientos de Inventario" subtitle="Control de entradas y salidas">
      <template #actions>
        <button @click="showFilters = !showFilters" 
          class="flex items-center gap-2 px-4 py-2 rounded-lg border transition-colors font-medium"
          :class="isDark 
            ? 'bg-slate-800 border-slate-600 text-slate-200 hover:bg-slate-700' 
            : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
          </svg>
          Filtros
        </button>
        <router-link to="/movements/new" 
          class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Nuevo Movimiento
        </router-link>
      </template>
    </PageHeader>

    <!-- Filters -->
    <div v-if="showFilters" 
      class="rounded-2xl p-6 mb-6 border"
      :class="isDark ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-slate-200'">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-slate-300' : 'text-slate-700'">Tipo</label>
          <select v-model="filters.type" 
            class="w-full px-4 py-2.5 rounded-lg border transition-colors"
            :class="isDark 
              ? 'bg-slate-800 border-slate-600 text-white focus:border-blue-500' 
              : 'bg-white border-slate-200 text-slate-900 focus:border-blue-500'">
            <option value="">Todos</option>
            <option value="entry">Entrada</option>
            <option value="exit">Salida</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-slate-300' : 'text-slate-700'">Desde</label>
          <input v-model="filters.dateFrom" type="date" 
            class="w-full px-4 py-2.5 rounded-lg border transition-colors"
            :class="isDark 
              ? 'bg-slate-800 border-slate-600 text-white focus:border-blue-500' 
              : 'bg-white border-slate-200 text-slate-900 focus:border-blue-500'" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-slate-300' : 'text-slate-700'">Hasta</label>
          <input v-model="filters.dateTo" type="date" 
            class="w-full px-4 py-2.5 rounded-lg border transition-colors"
            :class="isDark 
              ? 'bg-slate-800 border-slate-600 text-white focus:border-blue-500' 
              : 'bg-white border-slate-200 text-slate-900 focus:border-blue-500'" />
        </div>
        <div class="flex items-end">
          <button @click="applyFilters" 
            class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
            Aplicar
          </button>
        </div>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
      <div class="rounded-2xl p-6 border-l-4 border-emerald-500 transition-colors"
        :class="isDark ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-slate-200'">
        <p class="text-sm uppercase tracking-wider mb-1" :class="isDark ? 'text-slate-400' : 'text-slate-500'">Entradas del Mes</p>
        <p class="text-2xl font-bold text-emerald-500">{{ summary.entries }}</p>
        <p class="text-xs mt-1" :class="isDark ? 'text-slate-400' : 'text-slate-500'">Unidades: +{{ summary.entryUnits }}</p>
      </div>
      <div class="rounded-2xl p-6 border-l-4 border-rose-500 transition-colors"
        :class="isDark ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-slate-200'">
        <p class="text-sm uppercase tracking-wider mb-1" :class="isDark ? 'text-slate-400' : 'text-slate-500'">Salidas del Mes</p>
        <p class="text-2xl font-bold text-rose-500">{{ summary.exits }}</p>
        <p class="text-xs mt-1" :class="isDark ? 'text-slate-400' : 'text-slate-500'">Unidades: -{{ summary.exitUnits }}</p>
      </div>
      <div class="rounded-2xl p-6 border-l-4 border-amber-500 transition-colors"
        :class="isDark ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-slate-200'">
        <p class="text-sm uppercase tracking-wider mb-1" :class="isDark ? 'text-slate-400' : 'text-slate-500'">Balance</p>
        <p class="text-2xl font-bold" :class="isDark ? 'text-amber-400' : 'text-amber-600'">{{ summary.balance }}</p>
        <p class="text-xs mt-1" :class="isDark ? 'text-slate-400' : 'text-slate-500'">Diferencia neto</p>
      </div>
    </div>

    <!-- Movements Table -->
    <div class="rounded-2xl border overflow-hidden"
      :class="isDark ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-slate-200'">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead :class="isDark ? 'bg-slate-700/50' : 'bg-slate-50'">
            <tr>
              <th class="text-left py-3 px-4 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-slate-300' : 'text-slate-600'">Fecha</th>
              <th class="text-left py-3 px-4 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-slate-300' : 'text-slate-600'">Producto</th>
              <th class="text-left py-3 px-4 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-slate-300' : 'text-slate-600'">Tipo</th>
              <th class="text-left py-3 px-4 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-slate-300' : 'text-slate-600'">Cantidad</th>
              <th class="text-left py-3 px-4 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-slate-300' : 'text-slate-600'">Almacén</th>
              <th class="text-left py-3 px-4 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-slate-300' : 'text-slate-600'">Referencia</th>
              <th class="text-left py-3 px-4 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-slate-300' : 'text-slate-600'">Usuario</th>
            </tr>
          </thead>
          <tbody class="divide-y" :class="isDark ? 'divide-slate-700' : 'divide-slate-200'">
            <tr v-for="movement in movements" :key="movement.id" 
              class="transition-colors" :class="isDark ? 'hover:bg-slate-700/50' : 'hover:bg-slate-50'">
              <td class="py-3 px-4 text-sm" :class="isDark ? 'text-slate-400' : 'text-slate-500'">{{ formatDate(movement.created_at) }}</td>
              <td class="py-3 px-4 font-medium" :class="isDark ? 'text-white' : 'text-slate-800'">{{ movement.product?.name }}</td>
              <td class="py-3 px-4">
                <span class="px-3 py-1 rounded-full text-xs font-medium border"
                  :class="movement.type === 'entry' 
                    ? (isDark ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-emerald-100 text-emerald-700 border-emerald-200')
                    : (isDark ? 'bg-rose-500/20 text-rose-400 border-rose-500/30' : 'bg-rose-100 text-rose-700 border-rose-200')">
                  {{ movement.type === 'entry' ? 'Entrada' : 'Salida' }}
                </span>
              </td>
              <td class="py-3 px-4 font-bold"
                :class="movement.type === 'entry' ? 'text-emerald-500' : 'text-rose-500'">
                {{ movement.type === 'entry' ? '+' : '-' }}{{ movement.quantity }}
              </td>
              <td class="py-3 px-4 text-sm" :class="isDark ? 'text-slate-400' : 'text-slate-500'">{{ movement.warehouse?.name }}</td>
              <td class="py-3 px-4 text-sm" :class="isDark ? 'text-slate-400' : 'text-slate-500'">{{ movement.reference_number || '-' }}</td>
              <td class="py-3 px-4 text-sm" :class="isDark ? 'text-slate-400' : 'text-slate-500'">{{ movement.user?.name }}</td>
            </tr>
            <tr v-if="movements.length === 0">
              <td colspan="7" class="text-center py-12" :class="isDark ? 'text-slate-400' : 'text-slate-500'">
                No hay movimientos registrados
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.lastPage > 1" 
        class="flex items-center justify-between px-6 py-4 border-t"
        :class="isDark ? 'border-slate-700' : 'border-slate-200'">
        <p class="text-sm" :class="isDark ? 'text-slate-400' : 'text-slate-500'">
          Mostrando {{ movements.length }} de {{ pagination.total }} movimientos
        </p>
        <div class="flex gap-2">
          <button @click="changePage(pagination.currentPage - 1)" :disabled="pagination.currentPage === 1" 
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors disabled:opacity-50"
            :class="isDark ? 'bg-slate-700 text-slate-300 hover:bg-slate-600' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'">
            Anterior
          </button>
          <button @click="changePage(pagination.currentPage + 1)" :disabled="pagination.currentPage === pagination.lastPage" 
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors disabled:opacity-50"
            :class="isDark ? 'bg-slate-700 text-slate-300 hover:bg-slate-600' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'">
            Siguiente
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useDarkMode } from '../../composables/useDarkMode'
import PageHeader from '../../components/PageHeader.vue'
import apiClient from '../../services/api'

const { isDark } = useDarkMode()

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
