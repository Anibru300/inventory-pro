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
        <!-- Image Gallery -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-purple-500 rounded-full"></span>
            Galería de Imágenes
          </h2>
          
          <!-- Gallery Grid -->
          <div class="grid grid-cols-4 md:grid-cols-6 gap-4 mb-4">
            <!-- Existing Images -->
            <div v-for="(img, index) in galleryImages" :key="index" 
              class="relative aspect-square bg-slate-100 rounded-xl overflow-hidden group">
              <img :src="img.url || img" class="w-full h-full object-cover" />
              
              <!-- Set Primary -->
              <button v-if="index !== 0" type="button" @click="setPrimaryImage(index)"
                class="absolute top-1 left-1 p-1 bg-blue-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                title="Hacer principal">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
              </button>
              
              <!-- Primary Badge -->
              <span v-if="index === 0" class="absolute top-1 left-1 px-2 py-0.5 bg-blue-500 text-white text-xs rounded-full">
                Principal
              </span>
              
              <!-- Remove button -->
              <button type="button" @click="removeGalleryImage(index)"
                class="absolute top-1 right-1 p-1 bg-rose-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <!-- Add Image Button -->
            <label class="aspect-square bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl flex flex-col items-center justify-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all">
              <input type="file" accept="image/*" multiple class="hidden" @change="handleGalleryUpload" />
              <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              <span class="text-xs text-slate-400 mt-1">Agregar</span>
            </label>
          </div>
          
          <p class="text-sm text-slate-400">JPG, PNG o WebP. Máx. 2MB por imagen. La primera imagen es la principal.</p>
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
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 z-10">$</span>
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
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 z-10">$</span>
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
import apiClient from '../../services/api'

const router = useRouter()
const route = useRoute()
const saving = ref(false)
const product = ref({})

const isEditing = computed(() => !!route.params.id)

const form = ref({
  name: '',
  sku: '',
  category_id: '',
  description: '',
  cost: null,
  price: null,
  initial_stock: 0,
  min_stock: 10,
  warehouse_id: '',
  valuation_method: 'FIFO',
})

const categories = ref([])
const warehouses = ref([])
const galleryImages = ref([]) // Array of { url, file } for new uploads
const imagesToDelete = ref([]) // Array of indices to delete on server

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
      // Load existing images
      if (product.value.images && Array.isArray(product.value.images)) {
        galleryImages.value = product.value.images.map(img => ({ url: img.url || img }))
      } else if (product.value.primary_image) {
        galleryImages.value = [{ url: product.value.primary_image }]
      }
    } catch (err) {
      console.error('Error loading product:', err)
      alert('Error al cargar el producto')
    }
  }
}

function handleGalleryUpload(event) {
  const files = Array.from(event.target.files)
  files.forEach(file => {
    if (file.size > 2 * 1024 * 1024) {
      alert(`La imagen ${file.name} excede 2MB`)
      return
    }
    const reader = new FileReader()
    reader.onload = (e) => {
      galleryImages.value.push({
        url: e.target.result,
        file: file,
        isNew: true
      })
    }
    reader.readAsDataURL(file)
  })
  event.target.value = ''
}

function removeGalleryImage(index) {
  const img = galleryImages.value[index]
  if (!img.isNew && img.url) {
    imagesToDelete.value.push(img.url)
  }
  galleryImages.value.splice(index, 1)
}

function setPrimaryImage(index) {
  if (index === 0) return
  const img = galleryImages.value.splice(index, 1)[0]
  galleryImages.value.unshift(img)
}

async function handleSubmit() {
  saving.value = true
  try {
    // Validar campos requeridos
    if (!form.value.name || !form.value.sku) {
      alert('Nombre y SKU son requeridos')
      saving.value = false
      return
    }
    
    if (!form.value.cost || form.value.cost <= 0) {
      alert('El costo debe ser mayor a 0')
      saving.value = false
      return
    }
    
    if (!form.value.price || form.value.price <= 0) {
      alert('El precio debe ser mayor a 0')
      saving.value = false
      return
    }
    
    const formData = new FormData()
    
    // Append all form fields con conversión de tipos
    formData.append('name', form.value.name)
    formData.append('sku', form.value.sku)
    formData.append('description', form.value.description || '')
    formData.append('category_id', form.value.category_id || '')
    formData.append('cost', parseFloat(form.value.cost) || 0)
    formData.append('price', parseFloat(form.value.price) || 0)
    formData.append('min_stock', parseInt(form.value.min_stock) || 0)
    formData.append('initial_stock', parseInt(form.value.initial_stock) || 0)
    formData.append('warehouse_id', form.value.warehouse_id || '')
    formData.append('valuation_method', form.value.valuation_method || 'FIFO')
    
    // Append new images
    const newImages = galleryImages.value.filter(img => img.isNew && img.file)
    newImages.forEach((img, index) => {
      formData.append(`images[${index}]`, img.file)
    })
    
    // Append images to delete
    if (imagesToDelete.value.length > 0) {
      formData.append('images_to_delete', JSON.stringify(imagesToDelete.value))
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
