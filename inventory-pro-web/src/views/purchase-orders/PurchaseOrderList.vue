<template>
  <div class="p-6" :class="isDark ? 'bg-[#0B1F3A] min-h-screen' : 'bg-slate-50 min-h-screen'">
    <div class="flex justify-between items-center mb-6">
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
          <h1 class="text-3xl font-bold" :class="isDark ? 'text-white' : 'text-slate-800'">Ordenes de Compra</h1>
          <p :class="isDark ? 'text-slate-400' : 'text-slate-500'">Gestiona tus ordenes de compra</p>
        </div>
      </div>
      <router-link to="/purchase-orders/new" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700">
        Nueva Orden
      </router-link>
    </div>
    
    <div v-if="loading" class="text-center py-12">Cargando...</div>
    
    <div v-else-if="orders.length === 0" class="text-center py-12">
      <p>No hay ordenes de compra</p>
    </div>
    
    <div v-else class="bg-white rounded-2xl shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-slate-50">
          <tr>
            <th class="text-left py-4 px-6">Orden</th>
            <th class="text-left py-4 px-6">Proveedor</th>
            <th class="text-right py-4 px-6">Total</th>
            <th class="text-center py-4 px-6">Estado</th>
            <th class="text-right py-4 px-6">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in orders" :key="order.id" class="border-t">
            <td class="py-4 px-6">{{ order.order_number }}</td>
            <td class="py-4 px-6">{{ order.supplier?.name }}</td>
            <td class="py-4 px-6 text-right">${{ order.total }}</td>
            <td class="py-4 px-6 text-center">
              <span :class="getStatusClass(order.status)">{{ order.status }}</span>
            </td>
            <td class="py-4 px-6 text-right">
              <router-link :to="`/purchase-orders/${order.id}`" class="text-blue-600 hover:underline">Ver</router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useDarkMode } from '../../composables/useDarkMode'
import { purchaseOrderService } from '../../services/purchaseOrderService'

const { isDark } = useDarkMode()
const orders = ref([])
const loading = ref(false)

async function loadOrders() {
  loading.value = true
  try {
    const response = await purchaseOrderService.getAll()
    orders.value = response.data.data
  } catch (err) {
    console.error('Error:', err)
  } finally {
    loading.value = false
  }
}

function getStatusClass(status) {
  const classes = {
    draft: 'bg-slate-200 text-slate-700 px-2 py-1 rounded',
    sent: 'bg-blue-100 text-blue-700 px-2 py-1 rounded',
    partial: 'bg-amber-100 text-amber-700 px-2 py-1 rounded',
    received: 'bg-emerald-100 text-emerald-700 px-2 py-1 rounded',
    cancelled: 'bg-rose-100 text-rose-700 px-2 py-1 rounded',
  }
  return classes[status] || classes.draft
}

onMounted(loadOrders)
</script>
