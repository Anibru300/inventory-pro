<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
      <button
        @click="$router.back()"
        class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-white hover:shadow-sm transition-all"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
      </button>
      <div>
        <h1 class="text-3xl font-bold text-slate-800">Nueva Transferencia</h1>
        <p class="text-slate-500">Crear transferencia entre almacenes</p>
      </div>
    </div>

    <form @submit.prevent="submitForm" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Form -->
      <div class="lg:col-span-2 space-y-6">
        <!-- General Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-blue-600 rounded-full"></span>
            Información General
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Almacén Origen *</label>
              <select
                v-model="form.source_warehouse_id"
                required
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
              >
                <option value="">Seleccionar almacén</option>
                <option v-for="w in warehouses" :key="w.id" :value="w.id">{{ w.name }}</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Almacén Destino *</label>
              <select
                v-model="form.destination_warehouse_id"
                required
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
              >
                <option value="">Seleccionar almacén</option>
                <option v-for="w in warehouses" :key="w.id" :value="w.id" :disabled="w.id === form.source_warehouse_id">
                  {{ w.name }}
                </option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Fecha de Transferencia *</label>
              <input
                v-model="form.transfer_date"
                type="date"
                required
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Fecha Estimada de Llegada</label>
              <input
                v-model="form.expected_arrival_date"
                type="date"
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
              />
            </div>
          </div>
          
          <div class="mt-4">
            <label class="block text-sm font-medium mb-2 text-slate-700">Notas</label>
            <textarea
              v-model="form.notes"
              rows="2"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
              placeholder="Observaciones adicionales..."
            ></textarea>
          </div>
        </div>

        <!-- Items -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
              <span class="w-1 h-5 bg-emerald-500 rounded-full"></span>
              Productos
            </h2>
            <button
              type="button"
              @click="addItem"
              class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 font-medium rounded-lg hover:bg-blue-100 transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Agregar Producto
            </button>
          </div>

          <div v-for="(item, index) in form.items" :key="index" class="mb-4 p-4 bg-slate-50 border border-slate-200 rounded-xl">
            <div class="flex justify-between items-start mb-3">
              <span class="text-sm font-medium text-slate-700">Producto {{ index + 1 }}</span>
              <button
                v-if="form.items.length > 1"
                type="button"
                @click="removeItem(index)"
                class="text-rose-500 hover:text-rose-700 text-sm font-medium"
              >
                Eliminar
              </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="md:col-span-2">
                <label class="block text-sm text-slate-600 mb-1">Producto *</label>
                <select
                  v-model="item.product_id"
                  required
                  class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                >
                  <option value="">Seleccionar producto</option>
                  <option v-for="p in availableProducts" :key="p.id" :value="p.id">
                    {{ p.name }} (Stock: {{ getProductStock(p.id) }})
                  </option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm text-slate-600 mb-1">Cantidad *</label>
                <input
                  v-model.number="item.quantity"
                  type="number"
                  step="0.0001"
                  min="0.0001"
                  required
                  class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                />
              </div>
            </div>
            
            <div v-if="item.product_id && productLots[item.product_id]?.length > 0" class="mt-3">
              <label class="block text-sm text-slate-600 mb-1">Lote (opcional)</label>
              <select
                v-model="item.lot_id"
                class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
              >
                <option value="">Sin lote específico</option>
                <option v-for="lot in productLots[item.product_id]" :key="lot.id" :value="lot.id">
                  {{ lot.lot_number }} (Disp: {{ lot.remaining_quantity }})
                </option>
              </select>
            </div>
            
            <div class="mt-3">
              <label class="block text-sm text-slate-600 mb-1">Notas del item</label>
              <input
                v-model="item.notes"
                type="text"
                class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                placeholder="Observaciones..."
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Summary -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-indigo-500 rounded-full"></span>
            Resumen
          </h2>
          <div class="flex justify-between items-center py-3 border-b border-slate-100">
            <span class="text-slate-600">Total de Productos:</span>
            <span class="text-xl font-bold text-slate-800">{{ totalItems }}</span>
          </div>
          <div class="flex justify-between items-center py-3">
            <span class="text-slate-600">Items:</span>
            <span class="font-medium text-slate-800">{{ form.items.length }}</span>
          </div>
        </div>

        <!-- Error -->
        <div v-if="error" class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl">
          {{ error }}
        </div>

        <!-- Actions -->
        <div class="flex flex-col gap-3">
          <button
            type="submit"
            :disabled="isSubmitting || !isFormValid"
            class="w-full px-4 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
          >
            <svg v-if="isSubmitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ isSubmitting ? 'Creando...' : 'Crear Transferencia' }}</span>
          </button>
          <router-link
            to="/transfers"
            class="w-full px-4 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors text-center"
          >
            Cancelar
          </router-link>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { transferService } from '@/services/transferService'
import { warehouseService } from '@/services/warehouseService'
import { productService } from '@/services/productService'
import { lotService } from '@/services/lotService'

const router = useRouter()

const warehouses = ref([])
const products = ref([])
const productLots = ref({})
const isSubmitting = ref(false)
const error = ref('')

const form = ref({
  source_warehouse_id: '',
  destination_warehouse_id: '',
  transfer_date: new Date().toISOString().split('T')[0],
  expected_arrival_date: '',
  notes: '',
  items: [{ product_id: '', quantity: 1, lot_id: null, notes: '' }],
})

const availableProducts = computed(() => {
  if (!form.value.source_warehouse_id) return []
  return products.value.filter(p => getProductStock(p.id) > 0)
})

const totalItems = computed(() => {
  return form.value.items.reduce((sum, item) => sum + (Number(item.quantity) || 0), 0)
})

const isFormValid = computed(() => {
  return (
    form.value.source_warehouse_id &&
    form.value.destination_warehouse_id &&
    form.value.source_warehouse_id !== form.value.destination_warehouse_id &&
    form.value.transfer_date &&
    form.value.items.every(item => item.product_id && item.quantity > 0)
  )
})

const getProductStock = (productId) => {
  const product = products.value.find(p => p.id === productId)
  if (!product || !product.stock_levels) return 0
  const stock = product.stock_levels.find(s => s.warehouse_id === form.value.source_warehouse_id)
  return stock?.available_quantity || 0
}

const addItem = () => {
  form.value.items.push({ product_id: '', quantity: 1, lot_id: null, notes: '' })
}

const removeItem = (index) => {
  form.value.items.splice(index, 1)
}

const fetchLotsForProduct = async (productId) => {
  if (!productId || productLots.value[productId]) return
  
  try {
    const response = await lotService.getAvailable({
      product_id: productId,
      warehouse_id: form.value.source_warehouse_id,
    })
    productLots.value[productId] = response.data
  } catch (err) {
    console.error('Error fetching lots:', err)
  }
}

// Watch for product selection to fetch lots
watch(() => form.value.items.map(i => i.product_id), (newIds, oldIds) => {
  newIds.forEach((id, index) => {
    if (id && id !== oldIds?.[index]) {
      fetchLotsForProduct(id)
    }
  })
}, { deep: true })

const submitForm = async () => {
  if (!isFormValid.value) return
  
  isSubmitting.value = true
  error.value = ''

  try {
    const payload = {
      source_warehouse_id: form.value.source_warehouse_id,
      destination_warehouse_id: form.value.destination_warehouse_id,
      transfer_date: form.value.transfer_date,
      expected_arrival_date: form.value.expected_arrival_date || null,
      notes: form.value.notes,
      items: form.value.items.map(item => ({
        product_id: item.product_id,
        quantity: item.quantity,
        lot_id: item.lot_id || null,
        notes: item.notes,
      })),
    }

    await transferService.create(payload)
    router.push('/transfers')
  } catch (err) {
    error.value = err.response?.data?.message || 'Error al crear la transferencia'
    console.error('Error creating transfer:', err)
  } finally {
    isSubmitting.value = false
  }
}

onMounted(async () => {
  try {
    const [warehousesRes, productsRes] = await Promise.all([
      warehouseService.getAll(),
      productService.getAll(),
    ])
    warehouses.value = warehousesRes.data
    products.value = productsRes.data.data || productsRes.data
  } catch (err) {
    console.error('Error loading data:', err)
  }
})
</script>
