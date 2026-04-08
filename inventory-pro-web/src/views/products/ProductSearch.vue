<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-slate-800 mb-2">Buscador de Productos</h1>
      <p class="text-slate-500">Busca por código, nombre o escanea el código de barras</p>
    </div>

    <!-- Search Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-4">
        <!-- Text Search -->
        <div class="flex-1 relative">
          <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input 
            v-model="searchQuery" 
            @keyup.enter="performSearch"
            type="text" 
            placeholder="Buscar por código, nombre, SKU, barcode..."
            class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-lg"
          />
        </div>
        
        <!-- Scan Button -->
        <button 
          @click="showScanner = true"
          class="px-6 py-4 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-all flex items-center justify-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-2 0h-2m-2-2h-2m-2 2h-2m-2-2H6m-2 0H2m20-7h-2M4 8h2m2 0h2m2 0h2m2 0h2M4 12h16M4 16h16" />
          </svg>
          Escanear Código
        </button>
        
        <!-- Search Button -->
        <button 
          @click="performSearch"
          :disabled="loading"
          class="px-8 py-4 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all disabled:opacity-50 flex items-center justify-center gap-2"
        >
          <svg v-if="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>Buscar</span>
        </button>
      </div>
    </div>

    <!-- Results -->
    <div v-if="results.length > 0" class="space-y-4">
      <h2 class="text-lg font-semibold text-slate-800">Resultados ({{ results.length }})</h2>
      
      <div v-for="product in results" :key="product.id" 
        class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition-all">
        <div class="flex flex-col lg:flex-row gap-6">
          <!-- Image -->
          <div class="w-full lg:w-48 h-48 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0">
            <img v-if="product.primary_image" :src="product.primary_image" class="w-full h-full object-cover" />
            <div v-else class="w-full h-full flex items-center justify-center">
              <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
          
          <!-- Info -->
          <div class="flex-1">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
              <div>
                <h3 class="text-xl font-bold text-slate-800">{{ product.name }}</h3>
                <p class="text-slate-500 mt-1">SKU: <span class="font-mono">{{ product.sku }}</span></p>
                <p v-if="product.barcode" class="text-slate-500">Barcode: <span class="font-mono">{{ product.barcode }}</span></p>
              </div>
              <div class="flex gap-2">
                <span :class="['px-3 py-1 rounded-full text-xs font-medium',
                  product.stock_status === 'ok' ? 'bg-emerald-100 text-emerald-700' :
                  product.stock_status === 'low_stock' ? 'bg-amber-100 text-amber-700' :
                  'bg-rose-100 text-rose-700']">
                  {{ product.stock_status === 'ok' ? 'En Stock' : 
                     product.stock_status === 'low_stock' ? 'Stock Bajo' : 'Sin Stock' }}
                </span>
              </div>
            </div>
            
            <!-- Details Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
              <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs text-slate-500 uppercase">Categoría</p>
                <p class="font-medium text-slate-800">{{ product.category?.name || 'N/A' }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs text-slate-500 uppercase">Stock Total</p>
                <p class="font-medium text-slate-800">{{ product.total_stock }} und</p>
              </div>
              <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs text-slate-500 uppercase">Costo</p>
                <p class="font-medium text-slate-800">${{ formatNumber(product.unit_cost) }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs text-slate-500 uppercase">Precio Venta</p>
                <p class="font-medium text-emerald-600">${{ formatNumber(product.selling_price) }}</p>
              </div>
            </div>
            
            <!-- Stock by Warehouse -->
            <div v-if="product.stock_levels?.length" class="mt-6">
              <h4 class="text-sm font-medium text-slate-700 mb-3">Stock por Almacén</h4>
              <div class="flex flex-wrap gap-2">
                <div v-for="level in product.stock_levels" :key="level.id" 
                  class="px-4 py-2 bg-slate-100 rounded-lg text-sm">
                  <span class="text-slate-600">{{ level.warehouse?.name }}:</span>
                  <span class="font-medium text-slate-800 ml-1">{{ level.quantity }} und</span>
                </div>
              </div>
            </div>
            
            <!-- Actions -->
            <div class="flex gap-3 mt-6">
              <router-link :to="`/products/${product.id}/edit`" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                Editar Producto
              </router-link>
              <router-link :to="`/kardex?product_id=${product.id}`" 
                class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors text-sm font-medium">
                Ver Kardex
              </router-link>
              <button @click="viewProductDetails(product)" 
                class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors text-sm font-medium">
                Ver Detalles
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- No Results -->
    <div v-else-if="searched" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center">
      <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
      <p class="text-slate-500 text-lg">No se encontraron productos</p>
      <p class="text-slate-400 text-sm mt-2">Intenta con otro término de búsqueda</p>
    </div>

    <!-- Scanner Modal -->
    <BarcodeScanner 
      v-if="showScanner" 
      @close="showScanner = false" 
      @detected="onBarcodeDetected" 
    />

    <!-- Product Details Modal -->
    <div v-if="selectedProduct" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-auto">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
          <h3 class="text-xl font-bold text-slate-800">Detalles del Producto</h3>
          <button @click="selectedProduct = null" class="p-2 text-slate-400 hover:text-slate-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="p-6">
          <ProductDetails :product="selectedProduct" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import apiClient from '../../services/api'
import BarcodeScanner from '../../components/BarcodeScanner.vue'
import ProductDetails from '../../components/ProductDetails.vue'

const searchQuery = ref('')
const loading = ref(false)
const searched = ref(false)
const results = ref([])
const showScanner = ref(false)
const selectedProduct = ref(null)

async function performSearch() {
  if (!searchQuery.value.trim()) return
  
  loading.value = true
  searched.value = true
  
  try {
    const response = await apiClient.get('/products', {
      params: { search: searchQuery.value, per_page: 20 }
    })
    results.value = response.data.data
  } catch (err) {
    console.error('Search error:', err)
  } finally {
    loading.value = false
  }
}

function onBarcodeDetected(barcode) {
  searchQuery.value = barcode
  showScanner.value = false
  performSearch()
}

function viewProductDetails(product) {
  selectedProduct.value = product
}

function formatNumber(num) {
  if (!num) return '0.00'
  return parseFloat(num).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}
</script>
