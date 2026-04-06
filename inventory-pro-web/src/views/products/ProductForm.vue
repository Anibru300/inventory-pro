<template>
  <div>
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
      <button
        @click="$router.back()"
        class="p-2 text-text-secondary hover:text-text-primary rounded-lg hover:bg-bg-tertiary transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
      </button>
      <div>
        <h1 class="text-2xl font-bold">Nuevo Producto</h1>
        <p class="text-text-secondary">Agrega un producto a tu catálogo</p>
      </div>
    </div>

    <!-- Form -->
    <form @submit.prevent="handleSubmit" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Info -->
      <div class="lg:col-span-2 space-y-6">
        <div class="card">
          <h2 class="text-lg font-semibold mb-4">Información General</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-2">Nombre del producto *</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full"
                placeholder="Ej: Laptop HP Pavilion"
              />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2">SKU *</label>
                <input
                  v-model="form.sku"
                  type="text"
                  required
                  class="w-full"
                  placeholder="Ej: LAP-HP-001"
                />
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">Categoría</label>
                <select v-model="form.category_id" class="w-full">
                  <option value="">Seleccionar...</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium mb-2">Descripción</label>
              <textarea
                v-model="form.description"
                rows="3"
                class="w-full"
                placeholder="Descripción del producto..."
              ></textarea>
            </div>
          </div>
        </div>

        <div class="card">
          <h2 class="text-lg font-semibold mb-4">Precios</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2">Costo de compra *</label>
              <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary">$</span>
                <input
                  v-model="form.cost"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  class="w-full pl-8"
                  placeholder="0.00"
                />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium mb-2">Precio de venta *</label>
              <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary">$</span>
                <input
                  v-model="form.price"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  class="w-full pl-8"
                  placeholder="0.00"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <div class="card">
          <h2 class="text-lg font-semibold mb-4">Inventario</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-2">Cantidad inicial</label>
              <input
                v-model="form.initial_stock"
                type="number"
                min="0"
                class="w-full"
                placeholder="0"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2">Stock mínimo</label>
              <input
                v-model="form.min_stock"
                type="number"
                min="0"
                class="w-full"
                placeholder="10"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2">Almacén</label>
              <select v-model="form.warehouse_id" class="w-full">
                <option value="">Seleccionar...</option>
                <option v-for="wh in warehouses" :key="wh.id" :value="wh.id">{{ wh.name }}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="card">
          <h2 class="text-lg font-semibold mb-4">Método de valoración</h2>
          <div class="space-y-2">
            <label class="flex items-center gap-3 p-3 rounded-lg bg-bg-tertiary cursor-pointer">
              <input v-model="form.valuation_method" type="radio" value="FIFO" class="text-accent-primary" />
              <div>
                <p class="font-medium">FIFO</p>
                <p class="text-xs text-text-secondary">Primero en entrar, primero en salir</p>
              </div>
            </label>
            <label class="flex items-center gap-3 p-3 rounded-lg bg-bg-tertiary cursor-pointer">
              <input v-model="form.valuation_method" type="radio" value="AVERAGE" class="text-accent-primary" />
              <div>
                <p class="font-medium">Promedio Ponderado</p>
                <p class="text-xs text-text-secondary">Costo promedio de existencias</p>
              </div>
            </label>
          </div>
        </div>

        <div class="flex gap-3">
          <button
            type="button"
            @click="$router.back()"
            class="btn-secondary flex-1"
          >
            Cancelar
          </button>
          <button
            type="submit"
            :disabled="saving"
            class="btn-primary flex-1"
          >
            <svg v-if="saving" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ saving ? 'Guardando...' : 'Guardar' }}</span>
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useProductsStore } from '../../stores/products'
import axios from 'axios'

const router = useRouter()
const productsStore = useProductsStore()
const saving = ref(false)

const form = ref({
  name: '',
  sku: '',
  category_id: '',
  description: '',
  cost: '',
  price: '',
  initial_stock: 0,
  min_stock: 10,
  warehouse_id: '',
  valuation_method: 'FIFO',
})

const categories = ref([])
const warehouses = ref([])

async function handleSubmit() {
  saving.value = true
  try {
    await productsStore.createProduct(form.value)
    router.push('/products')
  } catch (err) {
    console.error('Error creating product:', err)
  } finally {
    saving.value = false
  }
}

onMounted(async () => {
  // Fetch categories and warehouses
  try {
    const [catRes, whRes] = await Promise.all([
      axios.get('/api/categories'),
      axios.get('/api/warehouses'),
    ])
    categories.value = catRes.data
    warehouses.value = whRes.data
  } catch (err) {
    console.error('Error fetching data:', err)
  }
})
</script>