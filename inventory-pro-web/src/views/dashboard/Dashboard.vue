<template>
  <div class="p-6">
    <!-- Page Header -->
    <PageHeader title="Dashboard" subtitle="Métricas y análisis de inventario">
      <template #actions>
        <!-- Help Button -->
        <ModuleHelp title="Dashboard - Centro de Control">
          <div class="space-y-4">
            <div>
              <h4 class="font-semibold text-slate-800 mb-2">📊 ¿Qué es el Dashboard?</h4>
              <p class="text-slate-600">El Dashboard es tu centro de control visual donde puedes monitorear el estado de tu inventario en tiempo real.</p>
            </div>
            <div>
              <h4 class="font-semibold text-slate-800 mb-2">📈 Tarjetas de Estadísticas</h4>
              <ul class="list-disc list-inside text-slate-600 space-y-1">
                <li><strong>Total Productos:</strong> Cantidad de productos registrados</li>
                <li><strong>Valor Inventario:</strong> Valor monetario total de tu stock</li>
                <li><strong>Stock Bajo:</strong> Productos que necesitan reabastecimiento</li>
                <li><strong>Sin Stock:</strong> Productos agotados</li>
              </ul>
            </div>
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-xl border border-blue-100">
              <p class="text-blue-700 text-sm"><strong>💡 Tip:</strong> Usa el filtro de fechas para ver diferentes períodos. Las alertas en rojo indican productos que requieren atención inmediata.</p>
            </div>
          </div>
        </ModuleHelp>

        <!-- Date Filter -->
        <div class="flex items-center gap-2">
          <select v-model="dateRange" @change="updateDashboard" 
            class="px-4 py-2.5 rounded-xl border text-sm font-medium transition-all focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
            :class="isDark ? 'bg-slate-800 border-slate-600 text-white' : 'bg-white border-slate-200 text-slate-700'">
            <option value="7">Últimos 7 días</option>
            <option value="30">Últimos 30 días</option>
            <option value="90">Últimos 3 meses</option>
            <option value="365">Último año</option>
          </select>
          <button @click="refreshData" 
            class="p-2.5 rounded-xl transition-all duration-200 hover:scale-105 active:scale-95"
            :class="isDark ? 'hover:bg-slate-800 text-slate-300 bg-slate-800/50' : 'hover:bg-slate-100 text-slate-600 bg-white border border-slate-200'"
            :disabled="loading"
            title="Actualizar datos">
            <svg class="w-5 h-5" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
          </button>
        </div>
      </template>
    </PageHeader>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <template v-if="loading">
        <div v-for="i in 4" :key="i" class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <SkeletonLoader type="text" class="w-24 mb-2" />
              <SkeletonLoader type="title" class="w-16" />
            </div>
            <SkeletonLoader type="circle" />
          </div>
        </div>
      </template>
      <template v-else>
        <StatCard 
          label="Total Productos" 
          :value="stats.totalProducts" 
          :icon="PackageIcon"
          icon-bg="blue"
          :trend="12"
          subtitle="vs mes anterior"
        />
        <StatCard 
          label="Valor Inventario" 
          :value="stats.totalValue" 
          prefix="$"
          :icon="CurrencyIcon"
          icon-bg="green"
          :trend="8.5"
          subtitle="valor total estimado"
        />
        <StatCard 
          label="Stock Bajo" 
          :value="stats.lowStock" 
          :icon="AlertIcon"
          icon-bg="amber"
          :progress="stats.lowStock > 0 ? Math.min((stats.lowStock / stats.totalProducts) * 100, 100) : 0"
        />
        <StatCard 
          label="Sin Stock" 
          :value="stats.outOfStock" 
          :icon="ErrorIcon"
          icon-bg="rose"
          :sparkline-data="[10, 8, 12, 6, 5, 3, 2]"
        />
      </template>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
      <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Acciones Rápidas</h3>
      <div class="flex flex-wrap gap-3">
        <button v-for="action in quickActions" :key="action.id"
          @click="$router.push(action.route)"
          class="group flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 hover:border-blue-300 hover:text-blue-600 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
          <component :is="action.icon" class="w-4 h-4 text-slate-400 group-hover:text-blue-500 transition-colors" />
          {{ action.label }}
        </button>
      </div>
    </div>

    <!-- Charts Configuration -->
    <div class="mb-6 flex flex-wrap items-center gap-4">
      <h3 class="text-lg font-semibold" :class="isDark ? 'text-white' : 'text-slate-800'">
        Gráficas
      </h3>
      <div class="flex items-center gap-2 flex-wrap">
        <button 
          v-for="chart in availableCharts" 
          :key="chart.id"
          @click="toggleChart(chart.id)"
          class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200"
          :class="activeCharts.includes(chart.id) 
            ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/25 hover:bg-blue-700 hover:shadow-blue-600/30'
            : (isDark ? 'bg-slate-800 text-slate-400 hover:bg-slate-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 hover:-translate-y-0.5')">
          {{ chart.name }}
        </button>
      </div>
      
      <div class="h-6 w-px" :class="isDark ? 'bg-slate-700' : 'bg-slate-300'"></div>
      
      <!-- Chart Color Theme -->
      <select v-model="chartTheme" @change="updateChartColors"
        class="px-3 py-1.5 rounded-lg text-sm border transition-all focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
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
        class="group rounded-2xl p-6 border transition-all duration-300 hover:shadow-lg"
        :class="isDark ? 'bg-slate-800/50 border-slate-700 hover:border-slate-600' : 'bg-white border-slate-200 hover:border-slate-300'">
        <div class="flex items-center justify-between mb-4">
          <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-slate-800'">Estado del Inventario</h4>
          <button @click="removeChart('status')" 
            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="h-64">
          <Doughnut :data="inventoryStatusData" :options="chartOptions" />
        </div>
      </div>

      <!-- Monthly Movements Chart -->
      <div v-if="activeCharts.includes('movements')" 
        class="group rounded-2xl p-6 border transition-all duration-300 hover:shadow-lg"
        :class="isDark ? 'bg-slate-800/50 border-slate-700 hover:border-slate-600' : 'bg-white border-slate-200 hover:border-slate-300'">
        <div class="flex items-center justify-between mb-4">
          <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-slate-800'">Movimientos Mensuales</h4>
          <button @click="removeChart('movements')" 
            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="h-64">
          <Bar :data="monthlyMovementsData" :options="chartOptions" />
        </div>
      </div>

      <!-- Top Products Chart -->
      <div v-if="activeCharts.includes('top')" 
        class="group rounded-2xl p-6 border transition-all duration-300 hover:shadow-lg"
        :class="isDark ? 'bg-slate-800/50 border-slate-700 hover:border-slate-600' : 'bg-white border-slate-200 hover:border-slate-300'">
        <div class="flex items-center justify-between mb-4">
          <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-slate-800'">Productos más Movidos</h4>
          <button @click="removeChart('top')" 
            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="h-64">
          <Bar :data="topProductsData" :options="horizontalChartOptions" />
        </div>
      </div>

      <!-- Category Distribution -->
      <div v-if="activeCharts.includes('categories')" 
        class="group rounded-2xl p-6 border transition-all duration-300 hover:shadow-lg"
        :class="isDark ? 'bg-slate-800/50 border-slate-700 hover:border-slate-600' : 'bg-white border-slate-200 hover:border-slate-300'">
        <div class="flex items-center justify-between mb-4">
          <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-slate-800'">Distribución por Categoría</h4>
          <button @click="removeChart('categories')" 
            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="h-64">
          <Pie :data="categoryDistributionData" :options="chartOptions" />
        </div>
      </div>
    </div>

    <!-- Low Stock Alerts Table -->
    <div v-if="lowStockProducts.length > 0" 
      class="rounded-2xl border overflow-hidden transition-all duration-300 hover:shadow-lg"
      :class="isDark ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-slate-200'">
      <div class="p-4 border-b flex items-center justify-between"
        :class="isDark ? 'border-slate-700' : 'border-slate-200'">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div>
            <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-slate-800'">
              Productos con Stock Bajo
            </h4>
            <p class="text-xs text-slate-500">Requieren reabastecimiento pronto</p>
          </div>
        </div>
        <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
          {{ lowStockProducts.length }} productos
        </span>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead :class="isDark ? 'bg-slate-700/50' : 'bg-slate-50'">
            <tr>
              <th class="text-left py-3.5 px-4 text-xs font-semibold uppercase tracking-wider" 
                :class="isDark ? 'text-slate-400' : 'text-slate-600'">Producto</th>
              <th class="text-center py-3.5 px-4 text-xs font-semibold uppercase tracking-wider" 
                :class="isDark ? 'text-slate-400' : 'text-slate-600'">Stock Actual</th>
              <th class="text-center py-3.5 px-4 text-xs font-semibold uppercase tracking-wider" 
                :class="isDark ? 'text-slate-400' : 'text-slate-600'">Mínimo</th>
              <th class="text-right py-3.5 px-4 text-xs font-semibold uppercase tracking-wider" 
                :class="isDark ? 'text-slate-400' : 'text-slate-600'">Acción</th>
            </tr>
          </thead>
          <tbody class="divide-y" :class="isDark ? 'divide-slate-700' : 'divide-slate-200'">
            <tr v-for="product in lowStockProducts.slice(0, 5)" :key="product.id" 
              class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl flex items-center justify-center"
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
              <td class="py-3.5 px-4 text-center">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-semibold" 
                  :class="isDark ? 'bg-amber-500/20 text-amber-400' : 'bg-amber-100 text-amber-700'">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                  </svg>
                  {{ product.stock_quantity }}
                </span>
              </td>
              <td class="py-3.5 px-4 text-center" :class="isDark ? 'text-slate-400' : 'text-slate-500'">
                {{ product.stock_min }}
              </td>
              <td class="py-3.5 px-4 text-right">
                <button @click="$router.push('/movements/new')" 
                  class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 hover:scale-105 active:scale-95"
                  :class="isDark ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-600/20' : 'bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-600/20 hover:shadow-blue-600/30'">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  Reabastecer
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Empty state when no low stock -->
    <div v-else-if="!loading" 
      class="rounded-2xl border p-8 text-center"
      :class="isDark ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-slate-200'">
      <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <h4 class="font-semibold text-lg mb-1" :class="isDark ? 'text-white' : 'text-slate-800'">¡Todo bien!</h4>
      <p class="text-slate-500">No hay productos con stock bajo en este momento.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, h } from 'vue'
import { useDarkMode } from '../../composables/useDarkMode'
import PageHeader from '../../components/PageHeader.vue'
import ModuleHelp from '../../components/ModuleHelp.vue'
import { StatCard, SkeletonLoader } from '../../components/ui'
import { Doughnut, Bar, Pie } from 'vue-chartjs'
import apiClient from '../../services/api'

const { isDark } = useDarkMode()
const loading = ref(false)
const dateRange = ref('30')
const chartTheme = ref('default')

// Icon components
const PackageIcon = {
  render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' })
  ])
}

const CurrencyIcon = {
  render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' })
  ])
}

const AlertIcon = {
  render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' })
  ])
}

const ErrorIcon = {
  render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' })
  ])
}

// Quick actions
const PlusIcon = {
  render: () => h('svg', { class: 'w-4 h-4', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 4v16m8-8H4' })
  ])
}

const TruckIcon = {
  render: () => h('svg', { class: 'w-4 h-4', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0' })
  ])
}

const DocumentIcon = {
  render: () => h('svg', { class: 'w-4 h-4', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' })
  ])
}

const quickActions = [
  { id: 'product', label: 'Nuevo Producto', route: '/products/new', icon: PlusIcon },
  { id: 'movement', label: 'Registrar Movimiento', route: '/movements/new', icon: TruckIcon },
  { id: 'report', label: 'Ver Reportes', route: '/reports', icon: DocumentIcon },
]

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
    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
    borderWidth: 0,
    hoverOffset: 4
  }]
})

const monthlyMovementsData = ref({
  labels: [],
  datasets: [
    { label: 'Entradas', data: [], backgroundColor: '#10b981', borderRadius: 4 },
    { label: 'Salidas', data: [], backgroundColor: '#ef4444', borderRadius: 4 }
  ]
})

const topProductsData = ref({
  labels: [],
  datasets: [{
    label: 'Movimientos',
    data: [],
    backgroundColor: '#3b82f6',
    borderRadius: 4
  }]
})

const categoryDistributionData = ref({
  labels: [],
  datasets: [{
    data: [],
    backgroundColor: ['#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'],
    borderWidth: 0
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
        font: { size: 12, family: 'Inter, system-ui, sans-serif' },
        usePointStyle: true,
        pointStyle: 'circle'
      }
    }
  },
  scales: isDark.value ? {
    x: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } },
    y: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } }
  } : {
    x: { grid: { display: false } },
    y: { grid: { color: '#f1f5f9' } }
  }
}))

const horizontalChartOptions = computed(() => ({
  ...chartOptions.value,
  indexAxis: 'y',
  scales: isDark.value ? {
    x: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } },
    y: { ticks: { color: '#94a3b8' }, grid: { display: false } }
  } : {
    x: { grid: { color: '#f1f5f9' } },
    y: { grid: { display: false } }
  }
}))

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
