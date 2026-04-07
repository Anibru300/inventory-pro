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
                  <input v-model="form.type" type="radio" value="entry" class="peer hidden" @change="updateMovementTypes" />
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
                  <input v-model="form.type" type="radio" value="exit" class="peer hidden" @change="updateMovementTypes" />
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

            <!-- Movement Type Detail -->
            <div>
              <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">Tipo Específico *</label>
              <select v-model="form.movement_type" required class="w-full">
                <option value="">Selecciona...</option>
                <option v-for="type in movementTypeOptions" :key="type.value" :value="type.value">
                  {{ type.label }}
                </option>
              </select>
            </div>

            <!-- Product -->
            <div>
              <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">Producto *</label>
              <select v-model="form.product_id" required class="w-full">
                <option value="">Selecciona un producto</option>
                <option v-for="product in products" :key="product.id" :value="product.id">
                  {{ product.name }} (Stock: {{ product.stock_levels?.reduce((a, b) => a + b.quantity, 0) || 0 }})
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
              <input v-model.number="form.unit_cost" type="number" step="0.01" min="0" class="w-full" placeholder="0.00" />
            </div>

            <!-- Reference -->
            <div>
              <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">Número de Referencia</label>
              <input v-model="form.reference_number" type="text" class="w-full" placeholder="Ej: FAC-001, OC-123, etc." />
            </div>

            <!-- Recipient Info (for exits) -->
            <template v-if="form.type === 'exit'">
              <div class="border-t border-white/10 pt-6">
                <h4 class="text-sm font-medium mb-4 text-cj-gold font-heading">Información del Destinatario</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm text-cj-silver-dark mb-2">Nombre de quien recibe</label>
                    <input v-model="form.recipient_name" type="text" class="w-full" placeholder="Ej: Juan Pérez" />
                  </div>
                  <div>
                    <label class="block text-sm text-cj-silver-dark mb-2">Departamento/Área</label>
                    <input v-model="form.recipient_department" type="text" class="w-full" placeholder="Ej: Ventas, Producción" />
                  </div>
                </div>
              </div>
            </template>

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
              <router-link to="/movements" class="btn-secondary flex-1 text-center py-3">Cancelar</router-link>
              <button type="submit" :disabled="loading" class="btn-primary flex-1">
                <svg v-if="loading" class="w-5 h-5 animate-spin mr-2 inline" fill="none" viewBox="0 0 24 24">
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
        <div class="card-premium p-6 mb-6">
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
              <span>Se generará un vale automático al guardar el movimiento</span>
            </li>
          </ul>
        </div>

        <!-- Receipt Preview (after creation) -->
        <div v-if="generatedReceipt" class="card-premium p-6 border-cj-gold">
          <div class="text-center mb-4">
            <div class="w-16 h-16 bg-success/10 rounded-full flex items-center justify-center mx-auto mb-2">
              <svg class="w-8 h-8 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="font-semibold">¡Movimiento Guardado!</h3>
            <p class="text-sm text-cj-silver-dark">Vale generado: <span class="text-cj-gold font-mono">{{ generatedReceipt.folio }}</span></p>
          </div>
          <div class="space-y-2">
            <a :href="apiUrl + generatedReceipt.download_url" target="_blank" class="btn-primary w-full block text-center">
              <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Descargar Vale PDF
            </a>
            <button @click="viewReceipt" class="btn-secondary w-full">
              <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              Ver Vale
            </button>
          </div>
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
const generatedReceipt = ref(null)

const apiUrl = import.meta.env.VITE_API_URL || 'https://inventory-pro-api-v2.onrender.com/api'

const form = ref({
  type: 'entry',
  movement_type: '',
  product_id: '',
  warehouse_id: '',
  quantity: 1,
  unit_cost: null,
  reference_number: '',
  notes: '',
  recipient_name: '',
  recipient_department: '',
})

const entryTypes = [
  { value: 'entrada_compra', label: 'Compra' },
  { value: 'entrada_devolucion_cliente', label: 'Devolución de Cliente' },
  { value: 'entrada_transferencia', label: 'Transferencia' },
  { value: 'entrada_ajuste', label: 'Ajuste Positivo' },
]

const exitTypes = [
  { value: 'salida_venta', label: 'Venta' },
  { value: 'salida_devolucion_proveedor', label: 'Devolución a Proveedor' },
  { value: 'salida_transferencia', label: 'Transferencia' },
  { value: 'salida_ajuste', label: 'Ajuste Negativo' },
  { value: 'salida_merma', label: 'Merma' },
]

const movementTypeOptions = ref(entryTypes)

function updateMovementTypes() {
  movementTypeOptions.value = form.value.type === 'entry' ? entryTypes : exitTypes
  form.value.movement_type = ''
}

async function fetchData() {
  try {
    const [productsRes, warehousesRes] = await Promise.all([
      apiClient.get('/products'),
      apiClient.get('/warehouses'),
    ])
    products.value = productsRes.data.data || []
    warehouses.value = warehousesRes.data || []
  } catch (err) {
    console.error('Error fetching data:', err)
  }
}

async function handleSubmit() {
  loading.value = true
  error.value = ''
  generatedReceipt.value = null

  try {
    const response = await apiClient.post('/stock-movements', {
      ...form.value,
      quantity: form.value.quantity,
    })
    
    // Show receipt info if generated
    if (response.data.receipt) {
      generatedReceipt.value = response.data.receipt
    } else {
      // Redirect after short delay if no receipt
      setTimeout(() => {
        router.push('/movements')
      }, 1000)
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Error al guardar el movimiento'
    loading.value = false
  }
}

function viewReceipt() {
  if (generatedReceipt.value?.preview_url) {
    window.open(apiUrl + generatedReceipt.value.preview_url, '_blank')
  }
}

onMounted(() => {
  fetchData()
  updateMovementTypes()
})
</script>