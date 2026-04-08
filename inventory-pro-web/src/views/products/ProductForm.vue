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
        <h1 class="text-3xl font-bold text-slate-800">{{ isEditing ? 'Editar' : 'Nuevo' }} Producto</h1>
        <p class="text-slate-500">{{ isEditing ? 'Actualiza la información del producto' : 'Agrega un producto a tu catálogo' }}</p>
      </div>
    </div>

    <!-- Form -->
    <form @submit.prevent="handleSubmit" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Info -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Image Upload -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-purple-500 rounded-full"></span>
            Imagen del Producto
          </h2>
          
          <div class="flex items-center gap-6">
            <!-- Image Preview -->
            <div class="relative w-32 h-32 bg-slate-100 rounded-xl overflow-hidden flex items-center justify-center">
              <img v-if="imagePreview || product.image" :src="imagePreview || product.image" class="w-full h-full object-cover" />
              <svg v-else class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              
              <!-- Remove button -->
              <button v-if="imagePreview || product.image" type="button" @click="removeImage"
                class="absolute top-1 right-1 p-1 bg-rose-500 text-white rounded-full hover:bg-rose-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <!-- Upload Button -->
            <div class="flex-1">
              <label class="inline-block">
                <input type="file" accept="image/*" class="hidden" @change="handleImageUpload" ref="imageInput" />
                <span class="px-6 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors cursor-pointer inline-flex items-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                  </svg>
                  {{ imagePreview || product.image ? 'Cambiar imagen' : 'Subir imagen' }}
                </span>
              </label>
              <p class="text-sm text-slate-400 mt-2">JPG, PNG o WebP. Máx. 2MB.</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-blue-600 rounded-full"></span>
            Información General
          </h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Nombre del producto *</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                placeholder="Ej: Laptop HP Pavilion"
              />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2 text-slate-700">SKU *</label>
                <input
                  v-model="form.sku"
                  type="text"
                  required
                  :disabled="isEditing"
                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all disabled:bg-slate-100 disabled:text-slate-500"
                  placeholder="Ej: LAP-HP-001"
                />
              </div>
              <div>
                <label class="block text-sm font-medium mb-2 text-slate-700">Categoría</label>
                <select v-model="form.category_id" 
                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                  <option value="">Seleccionar...</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Descripción</label>
              <textarea
                v-model="form.description"
                rows="3"
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                placeholder="Descripción del producto..."
              ></textarea>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-emerald-500 rounded-full"></span>
            Precios
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Costo de compra *</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                <input
                  v-model="form.cost"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                  placeholder="0.00"
                />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Precio de venta *</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                <input
                  v-model="form.price"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                  placeholder="0.00"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-indigo-500 rounded-full"></span>
            Inventario
          </h2>
          <div class="space-y-4">
            <div v-if="!isEditing">
              <label class="block text-sm font-medium mb-2 text-slate-700">Cantidad inicial</label>
              <input
                v-model="form.initial_stock"
                type="number"
                min="0"
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                placeholder="0"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Stock mínimo</label>
              <input
                v-model="form.min_stock"
                type="number"
                min="0"
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                placeholder="10"
              />
            </div>
            <div v-if="!isEditing">
              <label class="block text-sm font-medium mb-2 text-slate-700">Almacén inicial</label>
              <select v-model="form.warehouse_id" 
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                <option value="">Seleccionar...</option>
                <option v-for="wh in warehouses" :key="wh.id" :value="wh.id">{{ wh.name }}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-amber-500 rounded-full"></span>
            Método de valoración
          </h2>
          <div class="space-y-2">
            <label class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 cursor-pointer transition-all">
              <input v-model="form.valuation_method" type="radio" value="FIFO" class="text-blue-600" />
              <div>
                <p class="font-medium text-slate-800">FIFO</p>
                <p class="text-xs text-slate-500">Primero en entrar, primero en salir</p>
              </div>
            </label>
            <label class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 cursor-pointer transition-all">
              <input v-model="form.valuation_method" type="radio" value="AVERAGE" class="text-blue-600" />
              <div>
                <p class="font-medium text-slate-800">Promedio Ponderado</p>
                <p class="text-xs text-slate-500">Costo promedio de existencias</p>
              </div>
            </label>
          </div>
        </div>

        <div class="flex gap-3">
          <button
            type="button"
            @click="$router.back()"
            class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors"
          >
            Cancelar
          </button>
          <button
            type="submit"
            :disabled="saving"
            class="flex-1 px-4 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
          >
            <svg v-if="saving" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ saving ? 'Guardando...' : (isEditing ? 'Actualizar' : 'Guardar') }}</span>
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useProductsStore } from '../../stores/products'
import apiClient from '../../services/api'

const router = useRouter()
const route = useRoute()
const productsStore = useProductsStore()
const saving = ref(false)
const imageInput = ref(null)
const imagePreview = ref(null)
const imageFile = ref(null)
const product = ref({})

const isEditing = computed(() => !!route.params.id)

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

async function loadProduct() {
  if (isEditing.value) {
    try {
      const response = await apiClient.get(`/products/${route.params.id}`)
      product.value = response.data
      form.value = {
        name: product.value.name,
        sku: product.value.sku,
        category_id: product.value.category_id || '',
        description: product.value.description || '',
        cost: product.value.unit_cost,
        price: product.value.selling_price,
        min_stock: product.value.stock_min || 10,
        valuation_method: product.value.valuation_method || 'FIFO',
      }
    } catch (err) {
      console.error('Error loading product:', err)
      alert('Error al cargar el producto')
    }
  }
}

function handleImageUpload(event) {
  const file = event.target.files[0]
  if (file) {
    imageFile.value = file
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

function removeImage() {
  imageFile.value = null
  imagePreview.value = null
  if (product.value.image) {
    product.value.image = null
  }
  if (imageInput.value) {
    imageInput.value.value = ''
  }
}

async function handleSubmit() {
  saving.value = true
  try {
    const formData = new FormData()
    
    // Append all form fields
    Object.keys(form.value).forEach(key => {
      if (form.value[key] !== null && form.value[key] !== undefined) {
        formData.append(key, form.value[key])
      }
    })
    
    // Append image if selected
    if (imageFile.value) {
      formData.append('image', imageFile.value)
    }
    
    // If editing and image was removed
    if (isEditing.value && !imagePreview.value && !product.value.image) {
      formData.append('remove_image', 'true')
    }

    if (isEditing.value) {
      await apiClient.post(`/products/${route.params.id}?_method=PUT`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
    } else {
      await apiClient.post('/products', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
    }
    
    router.push('/products')
  } catch (err) {
    console.error('Error saving product:', err)
    alert(err.response?.data?.message || 'Error al guardar el producto')
  } finally {
    saving.value = false
  }
}

onMounted(async () => {
  await loadProduct()
  try {
    const [catRes, whRes] = await Promise.all([
      apiClient.get('/categories'),
      apiClient.get('/warehouses'),
    ])
    categories.value = catRes.data
    warehouses.value = whRes.data
  } catch (err) {
    console.error('Error fetching data:', err)
  }
})
</script>
