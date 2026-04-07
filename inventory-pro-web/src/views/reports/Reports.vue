<template>
  <div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
      <div>
        <h1 class="text-3xl font-bold gradient-text font-heading mb-2">Reportes</h1>
        <p class="text-cj-silver-dark font-tagline italic">Análisis y estadísticas de inventario</p>
      </div>
    </div>

    <!-- Report Types -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <button 
        v-for="report in reportTypes" 
        :key="report.id"
        @click="currentReport = report.id"
        :class="[
          'card-premium p-4 text-left transition-all',
          currentReport === report.id ? 'border-cj-gold ring-1 ring-cj-gold' : 'hover:border-cj-gold/50'
        ]"
      >
        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3"
          :class="currentReport === report.id ? 'bg-cj-gold/20' : 'bg-cj-gold/10'">
          <component :is="report.icon" class="w-5 h-5" :class="currentReport === report.id ? 'text-cj-gold' : 'text-cj-gold/70'" />
        </div>
        <h3 class="font-semibold text-sm">{{ report.name }}</h3>
        <p class="text-xs text-cj-silver-dark mt-1">{{ report.description }}</p>
      </button>
    </div>

    <!-- Filters -->
    <div class="card-premium p-4 mb-6">
      <div class="flex flex-wrap gap-4 items-end">
        <div>
          <label class="block text-sm text-cj-silver-dark mb-1">Desde</label>
          <input v-model="filters.dateFrom" type="date" class="w-40" />
        </div>
        <div>
          <label class="block text-sm text-cj-silver-dark mb-1">Hasta</label>
          <input v-model="filters.dateTo" type="date" class="w-40" />
        </div>
        <button @click="loadReport" :disabled="loading" class="btn-primary">
          <svg v-if="loading" class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ loading ? 'Cargando...' : 'Generar Reporte' }}
        </button>
        <button v-if="reportData" @click="exportData" class="btn-secondary">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
          </svg>
          Exportar
        </button>
      </div>
    </div>

    <!-- Report: Inventory Valuation -->
    <div v-if="currentReport === 'inventory' && reportData" class="space-y-6">
      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="stat-card border-l-4 border-cj-gold">
          <p class="text-cj-silver-dark text-xs uppercase">Productos</p>
          <p class="text-2xl font-bold">{{ reportData.summary.total_products }}</p>
          <p class="text-xs text-cj-silver-dark">{{ reportData.summary.total_items }} unidades</p>
        </div>
        <div class="stat-card border-l-4 border-success">
          <p class="text-cj-silver-dark text-xs uppercase">Valor al Costo</p>
          <p class="text-2xl font-bold text-success">${{ formatNumber(reportData.summary.total_cost_value) }}</p>
        </div>
        <div class="stat-card border-l-4 border-info">
          <p class="text-cj-silver-dark text-xs uppercase">Valor de Venta</p>
          <p class="text-2xl font-bold text-info">${{ formatNumber(reportData.summary.total_price_value) }}</p>
        </div>
        <div class="stat-card border-l-4 border-warning">
          <p class="text-cj-silver-dark text-xs uppercase">Ganancia Potencial</p>
          <p class="text-2xl font-bold text-warning">${{ formatNumber(reportData.summary.potential_profit) }}</p>
        </div>
      </div>

      <!-- By Category -->
      <div class="card-premium p-6">
        <h3 class="text-lg font-semibold mb-4 font-heading">Por Categoría</h3>
        <div class="overflow-x-auto">
          <table class="data-table">
            <thead>
              <tr>
                <th>Categoría</th>
                <th class="text-right">Cantidad</th>
                <th class="text-right">Valor Costo</th>
                <th class="text-right">Valor Venta</th>
                <th class="text-right">Margen</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(data, category) in reportData.by_category" :key="category">
                <td class="font-medium">{{ category }}</td>
                <td class="text-right">{{ data.quantity }}</td>
                <td class="text-right">${{ formatNumber(data.cost_value) }}</td>
                <td class="text-right">${{ formatNumber(data.price_value) }}</td>
                <td class="text-right text-success">${{ formatNumber(data.price_value - data.cost_value) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- By Warehouse -->
      <div class="card-premium p-6">
        <h3 class="text-lg font-semibold mb-4 font-heading">Por Almacén</h3>
        <div class="overflow-x-auto">
          <table class="data-table">
            <thead>
              <tr>
                <th>Almacén</th>
                <th class="text-right">Cantidad</th>
                <th class="text-right">Valor Costo</th>
                <th class="text-right">Valor Venta</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(data, warehouse) in reportData.by_warehouse" :key="warehouse">
                <td class="font-medium">{{ warehouse }}</td>
                <td class="text-right">{{ data.quantity }}</td>
                <td class="text-right">${{ formatNumber(data.cost_value) }}</td>
                <td class="text-right">${{ formatNumber(data.price_value) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Stock Levels Detail -->
      <div class="card-premium p-6">
        <h3 class="text-lg font-semibold mb-4 font-heading">Detalle de Inventario</h3>
        <div class="overflow-x-auto">
          <table class="data-table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>SKU</th>
                <th>Almacén</th>
                <th class="text-right">Stock</th>
                <th class="text-right">Costo Unit.</th>
                <th class="text-right">Precio Venta</th>
                <th class="text-right">Valor Total</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in reportData.stock_levels" :key="item.product.id + item.warehouse">
                <td class="font-medium">{{ item.product.name }}</td>
                <td class="font-mono text-cj-gold text-sm">{{ item.product.sku }}</td>
                <td>{{ item.warehouse }}</td>
                <td class="text-right">{{ item.quantity }}</td>
                <td class="text-right">${{ formatNumber(item.unit_cost) }}</td>
                <td class="text-right">${{ formatNumber(item.selling_price) }}</td>
                <td class="text-right font-semibold">${{ formatNumber(item.total_cost) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Report: Movements -->
    <div v-if="currentReport === 'movements' && reportData" class="space-y-6">
      <!-- Summary -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat-card border-l-4 border-success">
          <p class="text-cj-silver-dark text-xs uppercase">Entradas</p>
          <p class="text-2xl font-bold text-success">{{ reportData.summary.entry_units }}</p>
          <p class="text-xs text-cj-silver-dark">{{ reportData.summary.total_entries }} movimientos</p>
        </div>
        <div class="stat-card border-l-4 border-danger">
          <p class="text-cj-silver-dark text-xs uppercase">Salidas</p>
          <p class="text-2xl font-bold text-danger">{{ reportData.summary.exit_units }}</p>
          <p class="text-xs text-cj-silver-dark">{{ reportData.summary.total_exits }} movimientos</p>
        </div>
        <div class="stat-card border-l-4 border-cj-gold">
          <p class="text-cj-silver-dark text-xs uppercase">Balance</p>
          <p class="text-2xl font-bold" :class="reportData.summary.balance >= 0 ? 'text-success' : 'text-danger'">
            {{ reportData.summary.balance >= 0 ? '+' : '' }}{{ reportData.summary.balance }}
          </p>
          <p class="text-xs text-cj-silver-dark">unidades netas</p>
        </div>
      </div>

      <!-- By Product -->
      <div class="card-premium p-6">
        <h3 class="text-lg font-semibold mb-4 font-heading">Por Producto</h3>
        <div class="overflow-x-auto">
          <table class="data-table">
            <thead>
              <tr>
                <th>Producto</th>
                <th class="text-right">Entradas</th>
                <th class="text-right">Salidas</th>
                <th class="text-right">Balance</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(data, product) in reportData.by_product" :key="product">
                <td class="font-medium">{{ product }}</td>
                <td class="text-right text-success">+{{ data.entries }}</td>
                <td class="text-right text-danger">-{{ data.exits }}</td>
                <td class="text-right font-semibold" :class="data.balance >= 0 ? 'text-success' : 'text-danger'">
                  {{ data.balance >= 0 ? '+' : '' }}{{ data.balance }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Report: Low Stock -->
    <div v-if="currentReport === 'low-stock' && reportData" class="space-y-6">
      <!-- Low Stock Products -->
      <div class="card-premium p-6">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 bg-warning/10 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-semibold font-heading">Stock Bajo</h3>
            <p class="text-sm text-cj-silver-dark">{{ reportData.low_stock.count }} productos por debajo del mínimo</p>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="data-table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>SKU</th>
                <th>Categoría</th>
                <th class="text-right">Stock Actual</th>
                <th class="text-right">Mínimo</th>
                <th class="text-right">Faltante</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in reportData.low_stock.products" :key="product.id">
                <td class="font-medium">{{ product.name }}</td>
                <td class="font-mono text-cj-gold text-sm">{{ product.sku }}</td>
                <td>{{ product.category || '-' }}</td>
                <td class="text-right">{{ product.current_stock }}</td>
                <td class="text-right">{{ product.min_stock }}</td>
                <td class="text-right text-danger">{{ product.needed }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Out of Stock -->
      <div class="card-premium p-6 border-danger/30">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 bg-danger/10 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-semibold font-heading">Sin Stock</h3>
            <p class="text-sm text-cj-silver-dark">{{ reportData.out_of_stock.count }} productos agotados</p>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="data-table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>SKU</th>
                <th>Categoría</th>
                <th>Último Movimiento</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in reportData.out_of_stock.products" :key="product.id">
                <td class="font-medium text-danger">{{ product.name }}</td>
                <td class="font-mono text-cj-gold text-sm">{{ product.sku }}</td>
                <td>{{ product.category || '-' }}</td>
                <td class="text-cj-silver-dark">{{ product.last_movement ? formatDate(product.last_movement) : 'Nunca' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Report: Top Products -->
    <div v-if="currentReport === 'top-products' && reportData" class="space-y-6">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Exits -->
        <div class="card-premium p-6">
          <h3 class="text-lg font-semibold mb-4 font-heading text-danger">Más Salidas</h3>
          <div class="space-y-3">
            <div v-for="(item, index) in reportData.top_exits" :key="index" class="flex items-center gap-4 p-3 bg-bg-tertiary rounded-lg">
              <div class="w-8 h-8 rounded-full bg-danger/10 flex items-center justify-center text-danger font-bold text-sm">
                {{ index + 1 }}
              </div>
              <div class="flex-1">
                <p class="font-medium">{{ item.product_name }}</p>
                <p class="text-xs text-cj-silver-dark">{{ item.product_sku }}</p>
              </div>
              <div class="text-right">
                <p class="font-bold text-danger">{{ item.total_quantity }}</p>
                <p class="text-xs text-cj-silver-dark">unidades</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Top Entries -->
        <div class="card-premium p-6">
          <h3 class="text-lg font-semibold mb-4 font-heading text-success">Más Entradas</h3>
          <div class="space-y-3">
            <div v-for="(item, index) in reportData.top_entries" :key="index" class="flex items-center gap-4 p-3 bg-bg-tertiary rounded-lg">
              <div class="w-8 h-8 rounded-full bg-success/10 flex items-center justify-center text-success font-bold text-sm">
                {{ index + 1 }}
              </div>
              <div class="flex-1">
                <p class="font-medium">{{ item.product_name }}</p>
                <p class="text-xs text-cj-silver-dark">{{ item.product_sku }}</p>
              </div>
              <div class="text-right">
                <p class="font-bold text-success">{{ item.total_quantity }}</p>
                <p class="text-xs text-cj-silver-dark">unidades</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- No Data -->
    <div v-if="!loading && !reportData && !error" class="card-premium p-12 text-center">
      <div class="w-20 h-20 bg-cj-gold/10 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-10 h-10 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
      </div>
      <h3 class="text-lg font-semibold mb-2">Selecciona un reporte</h3>
      <p class="text-cj-silver-dark">Elige el tipo de reporte y haz clic en "Generar Reporte"</p>
    </div>

    <!-- Error -->
    <div v-if="error" class="card-premium p-6 border-danger">
      <div class="flex items-center gap-3">
        <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-danger">{{ error }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, h } from 'vue'
import apiClient from '../../services/api'

const currentReport = ref('inventory')
const loading = ref(false)
const reportData = ref(null)
const error = ref(null)

const filters = ref({
  dateFrom: new Date(new Date().setDate(1)).toISOString().split('T')[0], // First day of current month
  dateTo: new Date().toISOString().split('T')[0], // Today
})

// Icons
const InventoryIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' })]) }
const MovementsIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4' })]) }
const AlertIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' })]) }
const TopIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' })]) }

const reportTypes = [
  { id: 'inventory', name: 'Valoración', description: 'Valor del inventario actual', icon: InventoryIcon },
  { id: 'movements', name: 'Movimientos', description: 'Entradas y salidas', icon: MovementsIcon },
  { id: 'low-stock', name: 'Stock Bajo', description: 'Alertas de inventario', icon: AlertIcon },
  { id: 'top-products', name: 'Top Productos', description: 'Más movidos', icon: TopIcon },
]

const endpoints = {
  'inventory': '/reports/inventory-valuation',
  'movements': '/reports/movements',
  'low-stock': '/reports/low-stock',
  'top-products': '/reports/top-products',
}

function formatNumber(num) {
  if (num === null || num === undefined) return '0.00'
  return num.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

function formatDate(date) {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('es-MX')
}

async function loadReport() {
  loading.value = true
  error.value = null
  reportData.value = null

  try {
    const params = {}
    if (filters.value.dateFrom) params.date_from = filters.value.dateFrom
    if (filters.value.dateTo) params.date_to = filters.value.dateTo

    const response = await apiClient.get(endpoints[currentReport.value], { params })
    reportData.value = response.data
  } catch (err) {
    error.value = err.response?.data?.message || 'Error al cargar el reporte'
    console.error('Error loading report:', err)
  } finally {
    loading.value = false
  }
}

function exportData() {
  // Simple CSV export
  let csv = ''
  const filename = `reporte_${currentReport.value}_${new Date().toISOString().split('T')[0]}.csv`

  if (currentReport.value === 'inventory' && reportData.value?.stock_levels) {
    csv = 'Producto,SKU,Almacén,Cantidad,Costo Unit,Precio Venta,Valor Total\n'
    reportData.value.stock_levels.forEach(item => {
      csv += `"${item.product.name}","${item.product.sku}","${item.warehouse}",${item.quantity},${item.unit_cost},${item.selling_price},${item.total_cost}\n`
    })
  }

  if (csv) {
    const blob = new Blob([csv], { type: 'text/csv' })
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = filename
    a.click()
    window.URL.revokeObjectURL(url)
  }
}
</script>