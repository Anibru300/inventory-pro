<template>
  <div class="p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-white">Nueva Transferencia</h1>
      <p class="text-cj-silver mt-1">Crear transferencia entre almacenes</p>
    </div>

    <form @submit.prevent="submitForm" class="max-w-4xl">
      <!-- General Info -->
      <div class="bg-cj-navy/30 border border-cj-gold/20 rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-cj-gold mb-4">Información General</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-cj-silver mb-2">Almacén Origen *</label>
            <select
              v-model="form.source_warehouse_id"
              required
              class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
            >
              <option value="">Seleccionar almacén</option>
              <option v-for="w in warehouses" :key="w.id" :value="w.id">{{ w.name }}</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-cj-silver mb-2">Almacén Destino *</label>
            <select
              v-model="form.destination_warehouse_id"
              required
              class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
            >
              <option value="">Seleccionar almacén</option>
              <option v-for="w in warehouses" :key="w.id" :value="w.id" :disabled="w.id === form.source_warehouse_id">
                {{ w.name }}
              </option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-cj-silver mb-2">Fecha de Transferencia *</label>
            <input
              v-model="form.transfer_date"
              type="date"
              required
              class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-cj-silver mb-2">Fecha Estimada de Llegada</label>
            <input
              v-model="form.expected_arrival_date"
              type="date"
              class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
            />
          </div>
        </div>
        
        <div class="mt-4">
          <label class="block text-sm font-medium text-cj-silver mb-2">Notas</label>
          <textarea
            v-model="form.notes"
            rows="2"
            class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
          ></textarea>
        </div>
      </div>

      <!-- Items -->
      <div class="bg-cj-navy/30 border border-cj-gold/20 rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold text-cj-gold">Productos</h2>
          <button
            type="button"
            @click="addItem"
            class="text-cj-gold hover:text-cj-gold/80 text-sm font-medium"
          >
            + Agregar Producto
          </button>
        </div>

        <div v-for="(item, index) in form.items" :key="index" class="mb-4 p-4 bg-cj-navy/50 rounded-lg">
          <div class="flex justify-between items-start mb-3">
            <span class="text-sm font-medium text-cj-silver">Producto {{ index + 1 }}</span>
            <button
              v-if="form.items.length > 1"
              type="button"
              @click="removeItem(index)"
              class="text-red-400 hover:text-red-300 text-sm"
            >
              Eliminar
            </button>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
              <label class="block text-sm text-cj-silver mb-1">Producto *</label>
              <select
                v-model="item.product_id"
                required
                class="w-full px-4 py-2 bg-cj-navy border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
              >
                <option value="">Seleccionar producto</option>
                <option v-for="p in availableProducts" :key="p.id" :value="p.id">
                  {{ p.name }} (Stock: {{ getProductStock(p.id) }})
                </option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm text-cj-silver mb-1">Cantidad *</label>
              <input
                v-model.number="item.quantity"
                type="number"
                step="0.0001"
                min="0.0001"
                required
                class="w-full px-4 py-2 bg-cj-navy border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
              />
            </div>
          </div>
          
          <div v-if="item.product_id && productLots[item.product_id]?.length > 0" class="mt-3">
            <label class="block text-sm text-cj-silver mb-1">Lote (opcional)</label>
            <select
              v-model="item.lot_id"
              class="w-full px-4 py-2 bg-cj-navy border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
            >
              <option value="">Sin lote específico</option>
              <option v-for="lot in productLots[item.product_id]" :key="lot.id" :value="lot.id">
                {{ lot.lot_number }} (Disp: {{ lot.remaining_quantity }})
              </option>
            </select>
          </div>
          
          <div class="mt-3">
            <label class="block text-sm text-cj-silver mb-1">Notas</label>
            <input
              v-model="item.notes"
              type="text"
              class="w-full px-4 py-2 bg-cj-navy border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
            />
          </div>
        </div>
      </div>

      <!-- Summary -->
      <div class="bg-cj-navy/30 border border-cj-gold/20 rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
          <span class="text-cj-silver">Total de Productos:</span>
          <span class="text-white font-semibold">{{ totalItems }}</span>
        </div>
      </div>

      <!-- Error -->
      <div v-if="error" class="bg-red-500/20 border border-red-500/50 text-red-400 p-4 rounded-lg mb-6">
        {{ error }}
      </div>

      <!-- Actions -->
      <div class="flex gap-4">
        <button
          type="submit"
          :disabled="isSubmitting || !isFormValid"
          class="bg-cj-gold text-cj-navy px-6 py-2 rounded-lg font-medium hover:bg-cj-gold/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ isSubmitting ? 'Creando...' : 'Crear Transferencia' }}
        </button>
        <router-link
          to="/transfers"
          class="px-6 py-2 border border-cj-gold/50 text-cj-gold rounded-lg hover:bg-cj-gold/10 transition-colors"
        >
          Cancelar
        </router-link>
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
