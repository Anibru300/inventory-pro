<template>
  <div class="relative">
    <!-- Alert Button -->
    <button 
      @click="isOpen = !isOpen"
      class="relative p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-all"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
      <!-- Badge -->
      <span v-if="summary.total > 0" 
        :class="['absolute -top-1 -right-1 w-5 h-5 rounded-full text-xs font-bold flex items-center justify-center',
          summary.critical > 0 ? 'bg-rose-500 text-white' : 'bg-amber-500 text-white']">
        {{ summary.total > 9 ? '9+' : summary.total }}
      </span>
    </button>

    <!-- Dropdown -->
    <div v-if="isOpen" class="absolute right-0 mt-2 w-96 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 overflow-hidden">
      <!-- Header -->
      <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
        <h3 class="font-semibold text-slate-800">Alertas</h3>
        <div class="flex gap-2">
          <span v-if="summary.critical > 0" class="px-2 py-1 bg-rose-100 text-rose-700 text-xs rounded-full">
            {{ summary.critical }} Críticas
          </span>
          <span v-if="summary.warning > 0" class="px-2 py-1 bg-amber-100 text-amber-700 text-xs rounded-full">
            {{ summary.warning }} Advertencias
          </span>
        </div>
      </div>

      <!-- Alerts List -->
      <div class="max-h-80 overflow-auto">
        <div v-if="loading" class="p-8 text-center">
          <div class="w-8 h-8 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto"></div>
        </div>

        <div v-else-if="alerts.length === 0" class="p-8 text-center">
          <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <p class="text-slate-500">No hay alertas activas</p>
        </div>

        <div v-else>
          <div v-for="alert in alerts" :key="alert.id" 
            class="flex items-start gap-3 p-4 border-b border-slate-50 hover:bg-slate-50 transition-colors">
            <!-- Icon -->
            <div :class="['w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0',
              alert.severity === 'critical' ? 'bg-rose-100' : 'bg-amber-100']">
              <svg v-if="alert.type === 'out_of_stock'" class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <svg v-else class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
              <p class="font-medium text-slate-800">{{ alert.title }}</p>
              <p class="text-sm text-slate-500">{{ alert.message }}</p>
              <div v-if="alert.type === 'low_stock'" class="flex items-center gap-2 mt-1">
                <span class="text-xs text-slate-400">Stock: {{ alert.current_stock }} / Mín: {{ alert.min_stock }}</span>
              </div>
            </div>

            <!-- Action -->
            <button 
              @click="goToProduct(alert.product?.id)"
              class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
              title="Ver producto"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div v-if="alerts.length > 0" class="p-3 border-t border-slate-100 bg-slate-50">
        <router-link to="/products?stock_status=low_stock" 
          class="block text-center text-sm text-blue-600 hover:text-blue-700 font-medium">
          Ver todos los productos con alertas →
        </router-link>
      </div>
    </div>

    <!-- Click outside to close -->
    <div v-if="isOpen" class="fixed inset-0 z-40" @click="isOpen = false"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import apiClient from '../services/api'

const router = useRouter()
const isOpen = ref(false)
const loading = ref(false)
const alerts = ref([])
const summary = ref({ total: 0, critical: 0, warning: 0 })

let refreshInterval = null

async function fetchAlerts() {
  try {
    const [alertsRes, summaryRes] = await Promise.all([
      apiClient.get('/alerts'),
      apiClient.get('/alerts/summary'),
    ])
    alerts.value = alertsRes.data.alerts
    summary.value = summaryRes.data
  } catch (err) {
    console.error('Error fetching alerts:', err)
  }
}

function goToProduct(productId) {
  isOpen.value = false
  if (productId) {
    router.push(`/products/${productId}/edit`)
  }
}

onMounted(() => {
  fetchAlerts()
  // Refresh every 30 seconds
  refreshInterval = setInterval(fetchAlerts, 30000)
})

onUnmounted(() => {
  if (refreshInterval) {
    clearInterval(refreshInterval)
  }
})
</script>
