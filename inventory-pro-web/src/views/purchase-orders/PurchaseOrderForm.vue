<template>
  <div class="p-6" :class="isDark ? 'bg-[#0B1F3A] min-h-screen' : 'bg-slate-50 min-h-screen'">
    <div class="max-w-4xl mx-auto">
      <div class="flex items-center gap-4 mb-6">
        <router-link to="/purchase-orders" class="p-2 rounded-lg hover:bg-slate-200">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </router-link>
        <h1 class="text-2xl font-bold" :class="isDark ? 'text-white' : 'text-slate-800'">
          {{ isEditing ? 'Editar' : 'Nueva' }} Orden de Compra
        </h1>
      </div>
      
      <form @submit.prevent="saveOrder" class="bg-white rounded-2xl shadow p-6 space-y-6">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-2">Proveedor</label>
            <select v-model="form.supplier_id" required class="w-full px-4 py-2 border rounded-lg">
              <option v-for="s in suppliers" :key="s.id" :value="s.id">{{ s.name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">Almacen</label>
            <select v-model="form.warehouse_id" required class="w-full px-4 py-2 border rounded-lg">
              <option v-for="w in warehouses" :key="w.id" :value="w.id">{{ w.name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">Fecha</label>
            <input v-model="form.order_date" type="date" required class="w-full px-4 py-2 border rounded-lg">
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">Entrega Esperada</label>
            <input v-model="form.expected_date" type="date" class="w-full px-4 py-2 border rounded-lg">
          </div>
        </div>
        
        <div>
          <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold">Productos</h3>
            <button type="button" @click="addItem" class="text-blue-600 hover:underline">+ Agregar Producto</button>
          </div>
          
          <div v-for="(item, index) in form.items" :key="index" class="flex gap-4 mb-4 p-4 bg-slate-50 rounded-lg">
            <select v-model="item.product_id" required class="flex-1 px-4 py-2 border rounded-lg">
              <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }}</option>
            </select>
            <input v-model="item.quantity" type="number" min="1" placeholder="Cantidad" required class="w-32 px-4 py-2 border rounded-lg">
            <input v-model="item.unit_cost" type="number" min="0" step="0.01" placeholder="Costo" required class="w-32 px-4 py-2 border rounded-lg">
            <button type="button" @click="removeItem(index)" class="text-rose-600 hover:text-rose-700">Eliminar</button>
          </div>
        </div>
        
        <div class="flex justify-between items-center pt-6 border-t">
          <div class="text-xl font-bold">Total: ${{ calculateTotal.toFixed(2) }}</div>
          <div class="flex gap-4">
            <router-link to="/purchase-orders" class="px-6 py-3 border rounded-lg hover:bg-slate-50">Cancelar</router-link>
            <button type="submit" :disabled="saving" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
              {{ saving ? 'Guardando...' : 'Guardar' }}
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useDarkMode } from '../../composables/useDarkMode'
import { purchaseOrderService } from '../../services/purchaseOrderService'
import apiClient from '../../services/api'

const { isDark } = useDarkMode()
const route = useRoute()
const router = useRouter()
const isEditing = computed(() => route.params.id !== 'new')

const form = ref({
  supplier_id: '',
  warehouse_id: '',
  order_date: new Date().toISOString().split('T')[0],
  expected_date: '',
  items: [{ product_id: '', quantity: 1, unit_cost: 0 }],
})

const suppliers = ref([])
const warehouses = ref([])
const products = ref([])
const saving = ref(false)

const calculateTotal = computed(() => {
  return form.value.items.reduce((sum, item) => sum + (item.quantity * item.unit_cost), 0)
})

function addItem() {
  form.value.items.push({ product_id: '', quantity: 1, unit_cost: 0 })
}

function removeItem(index) {
  form.value.items.splice(index, 1)
}

async function saveOrder() {
  saving.value = true
  try {
    if (isEditing.value) {
      await purchaseOrderService.update(route.params.id, form.value)
    } else {
      await purchaseOrderService.create(form.value)
    }
    router.push('/purchase-orders')
  } catch (err) {
    alert('Error: ' + err.message)
  } finally {
    saving.value = false
  }
}

onMounted(async () => {
  const [sRes, wRes, pRes] = await Promise.all([
    apiClient.get('/suppliers'),
    apiClient.get('/warehouses'),
    apiClient.get('/products'),
  ])
  suppliers.value = sRes.data
  warehouses.value = wRes.data
  products.value = pRes.data.data || pRes.data
  
  if (isEditing.value) {
    const orderRes = await purchaseOrderService.getById(route.params.id)
    const order = orderRes.data
    form.value = {
      supplier_id: order.supplier_id,
      warehouse_id: order.warehouse_id,
      order_date: order.order_date,
      expected_date: order.expected_date || '',
      items: order.items.map(i => ({
        product_id: i.product_id,
        quantity: i.quantity,
        unit_cost: i.unit_cost,
      })),
    }
  }
})
</script>
