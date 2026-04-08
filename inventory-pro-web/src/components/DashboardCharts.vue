<template>
  <div class="space-y-6">
    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Stock Status Chart -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Estado del Inventario</h3>
        <div class="h-64">
          <Doughnut v-if="stockData" :data="stockData" :options="doughnutOptions" />
        </div>
        <div class="grid grid-cols-3 gap-4 mt-4">
          <div class="text-center">
            <p class="text-2xl font-bold text-emerald-600">{{ stats.in_stock }}</p>
            <p class="text-xs text-slate-500">En Stock</p>
          </div>
          <div class="text-center">
            <p class="text-2xl font-bold text-amber-600">{{ stats.low_stock }}</p>
            <p class="text-xs text-slate-500">Stock Bajo</p>
          </div>
          <div class="text-center">
            <p class="text-2xl font-bold text-rose-600">{{ stats.out_of_stock }}</p>
            <p class="text-xs text-slate-500">Sin Stock</p>
          </div>
        </div>
      </div>

      <!-- Monthly Movements Chart -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Movimientos Mensuales</h3>
        <div class="h-64">
          <Bar v-if="movementsData" :data="movementsData" :options="barOptions" />
        </div>
      </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Top Products -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Top Productos Movidos</h3>
        <div class="h-48">
          <Bar v-if="topProductsData" :data="topProductsData" :options="horizontalBarOptions" />
        </div>
      </div>

      <!-- Inventory Value Trend -->
      <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Tendencia de Valor del Inventario</h3>
        <div class="h-48">
          <Line v-if="valueTrendData" :data="valueTrendData" :options="lineOptions" />
        </div>
      </div>
    </div>

    <!-- Category Distribution -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
      <h3 class="text-lg font-bold text-slate-800 mb-4">Distribución por Categoría</h3>
      <div class="h-64">
        <Bar v-if="categoryData" :data="categoryData" :options="categoryBarOptions" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Doughnut, Bar, Line } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  ArcElement,
  Title,
  Tooltip,
  Legend,
  Filler
} from 'chart.js'
import apiClient from '../services/api'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  ArcElement,
  Title,
  Tooltip,
  Legend,
  Filler
)

const stats = ref({
  in_stock: 0,
  low_stock: 0,
  out_of_stock: 0,
})

const stockData = ref(null)
const movementsData = ref(null)
const topProductsData = ref(null)
const valueTrendData = ref(null)
const categoryData = ref(null)

const doughnutOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { position: 'bottom' }
  },
  cutout: '60%'
}

const barOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { position: 'top' }
  },
  scales: {
    y: { beginAtZero: true, ticks: { stepSize: 1 } }
  }
}

const horizontalBarOptions = {
  indexAxis: 'y',
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { display: false } },
  scales: { x: { beginAtZero: true } }
}

const lineOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { display: false } },
  scales: { y: { beginAtZero: true } },
  elements: { line: { tension: 0.4 } }
}

const categoryBarOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { display: false } },
  scales: { y: { beginAtZero: true } }
}

async function fetchDashboardData() {
  try {
    // Stock status
    const productsRes = await apiClient.get('/products', { params: { per_page: 1000 } })
    const products = productsRes.data.data
    
    stats.value.in_stock = products.filter(p => p.stock_status === 'ok').length
    stats.value.low_stock = products.filter(p => p.stock_status === 'low_stock').length
    stats.value.out_of_stock = products.filter(p => p.stock_status === 'out_of_stock').length
    
    stockData.value = {
      labels: ['En Stock', 'Stock Bajo', 'Sin Stock'],
      datasets: [{
        data: [stats.value.in_stock, stats.value.low_stock, stats.value.out_of_stock],
        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
        borderWidth: 0
      }]
    }

    // Monthly movements (mock data - in production, fetch from API)
    movementsData.value = {
      labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
      datasets: [
        {
          label: 'Entradas',
          data: [45, 52, 38, 65, 48, 58],
          backgroundColor: '#10b981',
          borderRadius: 4
        },
        {
          label: 'Salidas',
          data: [38, 45, 42, 55, 52, 48],
          backgroundColor: '#ef4444',
          borderRadius: 4
        }
      ]
    }

    // Top products
    topProductsData.value = {
      labels: ['Producto A', 'Producto B', 'Producto C', 'Producto D', 'Producto E'],
      datasets: [{
        label: 'Movimientos',
        data: [120, 95, 80, 65, 50],
        backgroundColor: '#3b82f6',
        borderRadius: 4
      }]
    }

    // Value trend
    valueTrendData.value = {
      labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
      datasets: [{
        label: 'Valor Inventario',
        data: [45000, 48000, 52000, 49000, 55000, 58000],
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        fill: true,
        tension: 0.4
      }]
    }

    // Category distribution
    const categories = {}
    products.forEach(p => {
      const cat = p.category?.name || 'Sin categoría'
      categories[cat] = (categories[cat] || 0) + 1
    })
    
    categoryData.value = {
      labels: Object.keys(categories),
      datasets: [{
        label: 'Productos',
        data: Object.values(categories),
        backgroundColor: [
          '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'
        ],
        borderRadius: 4
      }]
    }

  } catch (err) {
    console.error('Error loading dashboard data:', err)
  }
}

onMounted(() => {
  fetchDashboardData()
})
</script>
