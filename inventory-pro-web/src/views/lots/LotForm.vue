<template>
  <div class="p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-white">Nuevo Lote</h1>
      <p class="text-cj-silver mt-1">Crear un nuevo lote de producto</p>
    </div>

    <form @submit.prevent="submitForm" class="max-w-2xl">
      <div class="bg-cj-navy/30 border border-cj-gold/20 rounded-lg p-6 space-y-4">
        <!-- Producto -->
        <div>
          <label class="block text-sm font-medium text-cj-silver mb-2">Producto *</label>
          <select
            v-model="form.product_id"
            required
            class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
          >
            <option value="">Seleccionar producto</option>
            <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }}</option>
          </select>
        </div>

        <!-- Almacén -->
        <div>
          <label class="block text-sm font-medium text-cj-silver mb-2">Almacén *</label>
          <select
            v-model="form.warehouse_id"
            required
            class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
          >
            <option value="">Seleccionar almacén</option>
            <option v-for="w in warehouses" :key="w.id" :value="w.id">{{ w.name }}</option>
          </select>
        </div>

        <!-- Número de Lote -->
        <div>
          <label class="block text-sm font-medium text-cj-silver mb-2">Número de Lote *</label>
          <input
            v-model="form.lot_number"
            type="text"
            required
            class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
          />
        </div>

        <!-- Cantidad -->
        <div>
          <label class="block text-sm font-medium text-cj-silver mb-2">Cantidad Inicial *</label>
          <input
            v-model.number="form.initial_quantity"
            type="number"
            step="0.0001"
            min="0.0001"
            required
            class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
          />
        </div>

        <!-- Costo Unitario -->
        <div>
          <label class="block text-sm font-medium text-cj-silver mb-2">Costo Unitario *</label>
          <input
            v-model.number="form.unit_cost"
            type="number"
            step="0.0001"
            min="0"
            required
            class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
          />
        </div>

        <!-- Fechas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-cj-silver mb-2">Fecha de Fabricación</label>
            <input
              v-model="form.manufacturing_date"
              type="date"
              class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-cj-silver mb-2">Fecha de Vencimiento</label>
            <input
              v-model="form.expiry_date"
              type="date"
              class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
            />
          </div>
        </div>

        <!-- Notas -->
        <div>
          <label class="block text-sm font-medium text-cj-silver mb-2">Notas</label>
          <textarea
            v-model="form.notes"
            rows="3"
            class="w-full px-4 py-2 bg-cj-navy/50 border border-cj-gold/30 rounded-lg text-white focus:border-cj-gold focus:outline-none"
          ></textarea>
        </div>
      </div>

      <!-- Error -->
      <div v-if="error" class="bg-red-500/20 border border-red-500/50 text-red-400 p-4 rounded-lg mt-4">
        {{ error }}
      </div>

      <!-- Actions -->
      <div class="flex gap-4 mt-6">
        <button
          type="submit"
          :disabled="isSubmitting"
          class="bg-cj-gold text-cj-navy px-6 py-2 rounded-lg font-medium hover:bg-cj-gold/90 disabled:opacity-50"
        >
          {{ isSubmitting ? 'Creando...' : 'Crear Lote' }}
        </button>
        <router-link
          to="/lots"
          class="px-6 py-2 border border-cj-gold/50 text-cj-gold rounded-lg hover:bg-cj-gold/10"
        >
          Cancelar
        </router-link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { lotService } from '@/services/lotService'
import { productService } from '@/services/productService'
import { warehouseService } from '@/services/warehouseService'

const router = useRouter()

const products = ref([])
const warehouses = ref([])
const isSubmitting = ref(false)
const error = ref('')

const form = ref({
  product_id: '',
  warehouse_id: '',
  lot_number: '',
  initial_quantity: 1,
  unit_cost: 0,
  manufacturing_date: '',
  expiry_date: '',
  notes: '',
})

const fetchData = async () => {
  try {
    const [productsRes, warehousesRes] = await Promise.all([
      productService.getAll(),
      warehouseService.getAll(),
    ])
    products.value = productsRes.data.data || productsRes.data
    warehouses.value = warehousesRes.data
  } catch (err) {
    console.error('Error fetching data:', err)
  }
}

const submitForm = async () => {
  isSubmitting.value = true
  error.value = ''

  try {
    await lotService.create(form.value)
    router.push('/lots')
  } catch (err) {
    error.value = err.response?.data?.message || 'Error al crear el lote'
    console.error('Error creating lot:', err)
  } finally {
    isSubmitting.value = false
  }
}

onMounted(fetchData)
</script>
