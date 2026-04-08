<template>
  <div class="p-6">
    <!-- Page Header -->
    <PageHeader title="Dashboard" subtitle="Métricas y análisis de inventario">
      <template #actions>
        <!-- Date Filter -->
        <div class="flex items-center gap-2">
          <select v-model="dateRange" @change="updateDashboard" 
            class="px-3 py-2 rounded-lg border text-sm"
            :class="isDark ? 'bg-slate-800 border-slate-600 text-white' : 'bg-white border-slate-200 text-slate-700'">
            <option value="7">Últimos 7 días</option>
            <option value="30">Últimos 30 días</option>
            <option value="90">Últimos 3 meses</option>
            <option value="365">Último año</option>
          </select>
          <button @click="refreshData" class="p-2 rounded-lg transition-colors"
            :class="isDark ? 'hover:bg-slate-800 text-slate-300' : 'hover:bg-slate-100 text-slate-600'"
            :disabled="loading">
            <svg class="w-5 h-5" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
          </button>
        </div>
      </template>
    </PageHeader>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <StatCard 
        title="Total Productos" 
        :value="stats.totalProducts" 
        icon="package"
        :loading="loading"
        color="blue"
      />
      <StatCard 
        title="Valor Inventario" 
        :value="formatCurrency(stats.totalValue)" 
        icon="currency"
        :loading="loading"
        color="emerald"
      />
      <StatCard 
        title="Stock Bajo" 
        :value="stats.lowStock" 
        icon="alert"
        :loading="loading"
        color="amber"
        :alert="stats.lowStock > 0"
      />
      <StatCard 
        title="Sin Stock" 
        :value="stats.outOfStock" 
        icon="error"
        :loading="loading"
        color="rose"
        :alert="stats.outOfStock > 0"
      />
    </div>

    <!-- Charts Configuration -->
    <div class="mb-6 flex flex-wrap items-center gap-4">
      <h3 class="text-lg font-semibold" :class="isDark ? 'text-white' : 'text-slate-800'">
        Gráficas
      </h3>
      <div class="flex items-center gap-2">
        <button 
          v-for="chart in availableCharts" 
          :key="chart.id"
          @click="toggleChart(chart.id)"
          class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
          :class="activeCharts.includes(chart.id) 
            ? (isDark ? 'bg-blue-600 text-white' : 'bg-blue-600 text-white')
            : (isDark ? 'bg-slate-800 text-slate-400 hover:bg-slate-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200')">
          {{ chart.name }}
        </button>
      </div>
      
      <div class="h-6 w-px" :class="isDark ? 'bg-slate-700' : 'bg-slate-300'"></div>
      
      <!-- Chart Color Theme -->
      <select v-model="chartTheme" @change="updateChartColors"
        class="px-3 py-1.5 rounded-lg text-sm border"
        :class="isDark ? 'bg-slate-800 border-slate-600 text-white' : 'bg-white border-slate-200 text-slate-700'">
        <option value="default">Colores por defecto</option>
        <option value="blue">Tonos Azules</option>
        <option value="green">Tonos Verdes</option>
        <option value="purple">Tonos Púrpuras</option>
        <option value="monochrome">Monocromático</option>
      </select>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Inventory Status Chart -->
      <div v-if="activeCharts.includes('status')" 
        class="rounded-2xl p-6 border"
        :class="isDark ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-slate-200'">
        <div class="flex items-center justify-between mb-4">
          <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-slate-800'">Estado del Inventario</h4>
          <button @click="removeChart('status')" class="text-slate-400 hover:text-rose-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="h-64">
          <DoughnutChart :data="inventoryStatusData" :options="chartOptions" />
        </div>
      </div>

      <!-- Monthly Movements Chart -->
      <div v-if="activeCharts.includes('movements')" 
        class="rounded-2xl p-6 border"
        :class="isDark ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-slate-200'">
        <div class="flex items-center justify-between mb-4">
          <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-slate-800'">Movimientos Mensuales</h4>
          <button @click="removeChart('movements')" class="text-slate-400 hover:text-rose-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="h-64">
          <BarChart :data="monthlyMovementsData" :options="chartOptions" />
        </div>
      </div>

      <!-- Top Products Chart -->
      <div v-if="activeCharts.includes('top')" 
        class="rounded-2xl p-6 border"
        :class="isDark ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-slate-200'">
        <div class="flex items-center justify-between mb-4">
          <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-slate-800'">Productos más Movidos</h4>
          <button @click="removeChart('top')" class="text-slate-400 hover:text-rose-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="h-64">
          <BarChart :data="topProductsData" :options="horizontalChartOptions" />
        </div>
      </div>

      <!-- Category Distribution -->
      <div v-if="activeCharts.includes('categories')" 
        class="rounded-2xl p-6 border"
        :class="isDark ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-slate-200'">
        <div class="flex items-center justify-between mb-4">
          <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-slate-800'">Distribución por Categoría</h4>
          <button @click="removeChart('categories')" class="text-slate-400 hover:text-rose-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="h-64">
          <PieChart :data="categoryDistributionData" :options="chartOptions" />
        </div>
      </div>
    </div>

    <!-- Low Stock Alerts Table -->
    <div v-if="lowStockProducts.length > 0" 
      class="rounded-2xl border overflow-hidden"
      :class="isDark ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-slate-200'">
      <div class="p-4 border-b flex items-center justify-between"
        :class="isDark ? 'border-slate-700' : 'border-slate-200'">
        <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-slate-800'">
          Productos con Stock Bajo
        </h4>
        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
          {{ lowStockProducts.length }} productos
        </span>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead :class="isDark ? 'bg-slate-700/50' : 'bg-slate-50'">
            <tr>
              <th class="text-left py-3 px-4 text-xs font-medium uppercase tracking-wider" 
                :class="isDark ? 'text-slate-400' : 'text-slate-600'">Producto</th>
              <th class="text-center py-3 px-4 text-xs font-medium uppercase tracking-wider" 
                :class="isDark ? 'text-slate-400' : 'text-slate-600'">Stock Actual</th>
              <th class="text-center py-3 px-4 text-xs font-medium uppercase tracking-wider" 
                :class="isDark ? 'text-slate-400' : 'text-slate-600'">Mínimo</th>
              <th class="text-right py-3 px-4 text-xs font-medium uppercase tracking-wider" 
                :class="isDark ? 'text-slate-400' : 'text-slate-600'">Acción</th>
            </tr>
          </thead>
          <tbody class="divide-y" :class="isDark ? 'divide-slate-700' : 'divide-slate-200'">
            <tr v-for="product in lowStockProducts.slice(0, 5)" :key="product.id" 
              class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
              <td class="py-3 px-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                    :class="isDark ? 'bg-slate-700' : 'bg-slate-100'">
                    <svg class="w-5 h-5" :class="isDark ? 'text-slate-400' : 'text-slate-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                  </div>
                  <div>
                    <p class="font-medium" :class="isDark ? 'text-white' : 'text-slate-800'">{{ product.name }}</p>
                    <p class="text-xs" :class="isDark ? 'text-slate-400' : 'text-slate-500'">{{ product.sku }}</p>
                  </div>
                </div>
              </td>
              <td class="py-3 px-4 text-center">
                <span class="font-semibold" :class="isDark ? 'text-amber-400' : 'text-amber-600'">
                  {{ product.stock_quantity }}
                </span>
              </td>
              <td class="py-3 px-4 text-center" :class="isDark ? 'text-slate-400' : 'text-slate-500'">
                {{ product.stock_min }}
              </td>
              <td class="py-3 px-4 text-right">
                <button @click="$router.push('/movements/new')" 
                  class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
                  :class="isDark ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-blue-600 hover:bg-blue-700 text-white'">
                  Reabastecer
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useDarkMode } from '../../composables/useDarkMode'
import PageHeader from '../../components/PageHeader.vue'
import StatCard from '../../components/StatCard.vue'
import { DoughnutChart, BarChart, PieChart } from 'vue-chartjs'
import apiClient from '../../services/api'

const { isDark } = useDarkMode()
const loading = ref(false)
const dateRange = ref('30')
const chartTheme = ref('default')

const activeCharts = ref(['status', 'movements', 'top', 'categories'])
const availableCharts = [
  { id: 'status', name: 'Estado Inventario' },
  { id: 'movements', name: 'Movimientos' },
  { id: 'top', name: 'Top Productos' },
  { id: 'categories', name: 'Categorías' },
]

const stats = ref({
  totalProducts: 0,
  totalValue: 0,
  lowStock: 0,
  outOfStock: 0
})
const lowStockProducts = ref([])

// Chart data
const inventoryStatusData = ref({
  labels: ['Normal', 'Stock Bajo', 'Sin Stock'],
  datasets: [{
    data: [0, 0, 0],
    backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
  }]
})

const monthlyMovementsData = ref({
  labels: [],
  datasets: [
    { label: 'Entradas', data: [], backgroundColor: '#10b981' },
    { label: 'Salidas', data: [], backgroundColor: '#ef4444' }
  ]
})

const topProductsData = ref({
  labels: [],
  datasets: [{
    label: 'Movimientos',
    data: [],
    backgroundColor: '#3b82f6'
  }]
})

const categoryDistributionData = ref({
  labels: [],
  datasets: [{
    data: [],
    backgroundColor: ['#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981']
  }]
})

const chartOptions = computed(() => ({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        color: isDark.value ? '#94a3b8' : '#475569',
        padding: 15,
        font: { size: 12 }
      }
    }
  },
  scales: isDark.value ? {
    x: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } },
    y: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } }
  } : {}
}))

const horizontalChartOptions = computed(() => ({
  ...chartOptions.value,
  indexAxis: 'y',
  scales: isDark.value ? {
    x: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } },
    y: { ticks: { color: '#94a3b8' }, grid: { display: false } }
  } : { x: {}, y: { grid: { display: false } } }
}))

function formatCurrency(value) {
  return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(value || 0)
}

function toggleChart(chartId) {
  if (activeCharts.value.includes(chartId)) {
    if (activeCharts.value.length > 1) {
      activeCharts.value = activeCharts.value.filter(c => c !== chartId)
    }
  } else {
    activeCharts.value.push(chartId)
  }
}

function removeChart(chartId) {
  if (activeCharts.value.length > 1) {
    activeCharts.value = activeCharts.value.filter(c => c !== chartId)
  }
}

function updateChartColors() {
  const themes = {
    default: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
    blue: ['#1e40af', '#3b82f6', '#60a5fa', '#93c5fd', '#dbeafe'],
    green: ['#064e3b', '#10b981', '#34d399', '#6ee7b7', '#d1fae5'],
    purple: ['#581c87', '#8b5cf6', '#a78bfa', '#c4b5fd', '#ede9fe'],
    monochrome: ['#0f172a', '#334155', '#64748b', '#94a3b8', '#cbd5e1']
  }
  
  const colors = themes[chartTheme.value] || themes.default
  
  inventoryStatusData.value.datasets[0].backgroundColor = [colors[1], colors[2], colors[3]]
  monthlyMovementsData.value.datasets[0].backgroundColor = colors[1]
  monthlyMovementsData.value.datasets[1].backgroundColor = colors[3]
  topProductsData.value.datasets[0].backgroundColor = colors[0]
  categoryDistributionData.value.datasets[0].backgroundColor = colors
}

async function refreshData() {
  loading.value = true
  await updateDashboard()
  loading.value = false
}

async function updateDashboard() {
  try {
    const response = await apiClient.get('/dashboard', {
      params: { days: dateRange.value }
    })
    
    stats.value = response.data.stats
    lowStockProducts.value = response.data.low_stock_products || []
    
    // Update chart data
    const { normal, low, out } = calculateInventoryDistribution(response.data)
    inventoryStatusData.value.datasets[0].data = [normal, low, out]
    
  } catch (err) {
    console.error('Error loading dashboard:', err)
  }
}

function calculateInventoryDistribution(data) {
  const total = data.stats.totalProducts || 1
  const low = data.stats.lowStock || 0
  const out = data.stats.outOfStock || 0
  const normal = total - low - out
  return { normal, low, out }
}

onMounted(() => {
  refreshData()
})
</script>
