<template>
  <div>
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold gradient-text font-heading mb-2">Nuevo Movimiento</h1>
      <p class="text-cj-silver-dark font-tagline italic">Registra entrada o salida de inventario</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Form -->
      <div class="lg:col-span-2">
        <div class="card-premium p-8">
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <!-- Movement Type -->
            <div>
              <label class="block text-sm font-medium mb-3 text-cj-silver font-heading">Tipo de Movimiento</label>
              <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer">
                  <input v-model="form.type" type="radio" value="entry" class="peer hidden" />
                  <div class="p-4 rounded-xl border-2 border-white/10 peer-checked:border-success peer-checked:bg-success/5 text-center transition-all">
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center mx-auto mb-2">
                      <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                      </svg>
                    </div>
                    <p class="font-medium text-success">Entrada</p>
                  </div>
                </label>
                <label class="cursor-pointer">
                  <input v-model="form.type" type="radio" value="exit" class="peer hidden" />
                  <div class="p-4 rounded-xl border-2 border-white/10 peer-checked:border-danger peer-checked:bg-danger/5 text-center transition-all">
                    <div class="w-12 h-12 bg-danger/10 rounded-full flex items-center justify-center mx-auto mb-2">
                      <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                      </svg>
                    </div>
                    <p class="font-medium text-danger">Salida</p>
                  </div>
                </label>
              </div>
            </div>

            <!-- Product -->
            <div>
              <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">Producto *</label>
              <select v-model="form.product_id" required class="w-full">
                <option value="">Selecciona un producto</option>
                <option v-for="product in products" :key="product.id" :value="product.id">
                  {{ product.name }} (Stock: {{ product.stock_quantity }})
                </option>
              </select>
            </div>

            <!-- Warehouse -->
            <div>
              <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">Almacén *</label>
              <select v-model="form.warehouse_id" required class="w-full">
                <option value="">Selecciona un almacén</option>
                <option v-for="warehouse in warehouses" :key="warehouse.id" :value="warehouse.id">
                  {{ warehouse.name }}
                </option>
              </select>
            </div>

            <!-- Quantity -->
            <div>
              <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">Cantidad *</label>
              <input v-model.number="form.quantity" type="number" min="1" required class="w-full" placeholder="0" />
            </div>

            <!-- Unit Cost -->
            <div>
              <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">Costo Unitario</label>
              <input v-model.number="form.unit_cost" type="number" step="0.01" class="w-full" placeholder="0.00" />
            </div>

            <!-- Reference -->
            <div>
              <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">Número de Referencia</label>
              <input v-model="form.reference_number" type="text" class="w-full" placeholder="Ej: FAC-001, REM-002" />
            </div>

            <!-- Notes -->
            <div>
              <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">Notas</label>
              <textarea v-model="form.notes" rows="3" class="w-full" placeholder="Observaciones adicionales..."></textarea>
            </div>

            <!-- Error -->
            <div v-if="error" class="p-4 bg-danger/10 border border-danger/30 rounded-xl text-danger text-sm">
              {{ error }}
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-4">
              <router-link to="/movements" class="btn-secondary flex-1">Cancelar</router-link>
              <button type="submit" :disabled="loading" class="btn-primary flex-1">
                <svg v-if="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>{{ loading ? 'Guardando...' : 'Guardar Movimiento' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Tips -->
      <div>
        <div class="card-premium p-6">
          <h3 class="text-lg font-semibold mb-4 font-heading flex items-center gap-2">
            <span class="w-1 h-5 bg-cj-gold rounded-full"></span>
            Consejos
          </h3>
          <ul class="space-y-3 text-sm text-cj-silver">
            <li class="flex gap-3">
              <span class="text-cj-gold">•</span>
              <span>Verifica siempre el stock antes de registrar una salida</span>
            </li>
            <li class="flex gap-3">
              <span class="text-cj-gold">•</span>
              <span>Usa números de referencia para facturas o remisiones</span>
            </li>
            <li class="flex gap-3">
              <span class="text-cj-gold">•</span>
              <span>El costo unitario es opcional pero recomendado para entradas</span>
            </li>
            <li class="flex gap-3">
              <span class="text-cj-gold">•</span>
              <span>Las notas ayudan a recordar el motivo del movimiento</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import apiClient from '../../services/api'

const router = useRouter()
const loading = ref(false)
const error = ref('')
const products = ref([])
const warehouses = ref([])

const form = ref({
  type: 'entry',
  product_id: '',
  warehouse_id: '',
  quantity: 1,
  unit_cost: null,
  reference_number: '',
  notes: '',
})

async function fetchData() {
  try {
    const [productsRes, warehousesRes] = await Promise.all([
      apiClient.get('/products'),
      apiClient.get('/warehouses'),
    ])
    products.value = productsRes.data.data
    warehouses.value = warehousesRes.data
  } catch (err) {
    console.error('Error fetching data:', err)
  }
}

async function handleSubmit() {
  loading.value = true
  error.value = ''

  try {
    await apiClient.post('/stock-movements', form.value)
    router.push('/movements')
  } catch (err) {
    error.value = err.response?.data?.message || 'Error al guardar el movimiento'
    loading.value = false
  }
}

onMounted(fetchData)
</script>
