<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <div class="flex items-center gap-4">
        <button 
          @click="$router.push('/menu')"
          class="flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm"
          title="Volver al menú"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
        </button>
        <div>
          <h1 class="text-3xl font-bold text-slate-800 mb-1">Productos</h1>
          <p class="text-slate-500">Gestiona tu catálogo de productos</p>
        </div>
        <ModuleHelp title="Productos - Catálogo">
          <div class="space-y-4">
            <div>
              <h4 class="font-semibold text-slate-800 mb-2">📦 ¿Qué son los Productos?</h4>
              <p class="text-slate-600">Los productos son todos los artículos que gestionas en tu inventario. Cada producto tiene información detallada como nombre, código, stock y precio.</p>
            </div>
            <div>
              <h4 class="font-semibold text-slate-800 mb-2">➕ Crear un Producto</h4>
              <ol class="list-decimal list-inside text-slate-600 space-y-1">
                <li>Haz clic en <strong>"Nuevo Producto"</strong></li>
                <li>Completa los datos: nombre, código SKU, categoría</li>
                <li>Define el stock inicial y el stock mínimo (para alertas)</li>
                <li>Agrega el costo y precio de venta</li>
                <li>Guarda el producto</li>
              </ol>
            </div>
            <div>
              <h4 class="font-semibold text-slate-800 mb-2">🔍 Buscar y Filtrar</h4>
              <ul class="list-disc list-inside text-slate-600 space-y-1">
                <li>Usa la barra de búsqueda para encontrar por nombre o código</li>
                <li>Filtra por categoría para ver productos específicos</li>
                <li>Filtra por estado de stock (en stock, bajo, agotado)</li>
              </ul>
            </div>
            <div>
              <h4 class="font-semibold text-slate-800 mb-2">⚠️ Estados de Stock</h4>
              <ul class="list-disc list-inside text-slate-600 space-y-1">
                <li><span class="text-emerald-600">● En Stock:</span> Cantidad normal disponible</li>
                <li><span class="text-amber-600">● Stock Bajo:</span> Por debajo del mínimo definido</li>
                <li><span class="text-rose-600">● Sin Stock:</span> Agotado (cantidad = 0)</li>
              </ul>
            </div>
            <div class="bg-emerald-50 p-4 rounded-xl">
              <p class="text-emerald-700 text-sm"><strong>💡 Tip:</strong> Define un stock mínimo realista para cada producto. El sistema te alertará cuando necesites reabastecer.</p>
            </div>
          </div>
        </ModuleHelp>
      </div>
      <router-link to="/products/new" 
        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 hover:shadow-xl hover:shadow-blue-600/30">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nuevo Producto
      </router-link>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
          <div class="relative">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input v-model="filters.search" @input="debouncedSearch" type="text" placeholder="Buscar productos..."
              class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-slate-800" />
          </div>
        </div>
        <div class="flex gap-3">
          <select v-model="filters.category" @change="fetchProducts" 
            class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 min-w-[180px]">
            <option value="">Todas las categorías</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
          <select v-model="filters.stock_status" @change="fetchProducts" 
            class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 min-w-[160px]">
            <option value="">Estado de stock</option>
            <option value="in_stock">En stock</option>
            <option value="low_stock">Stock bajo</option>
            <option value="out_of_stock">Sin stock</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
      <div v-if="productsStore.loading" class="p-12 text-center">
        <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto"></div>
        <p class="mt-4 text-slate-500">Cargando productos...</p>
      </div>

      <div v-else-if="productsStore.products.length === 0" class="p-12 text-center">
        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
        </div>
        <p class="text-slate-500 text-lg">No se encontraron productos</p>
        <router-link to="/products/new" class="mt-4 inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
          Crear primer producto
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </router-link>
      </div>

      <table v-else class="w-full">
        <thead>
          <tr class="border-b border-slate-100 bg-slate-50/50">
            <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Producto</th>
            <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">SKU</th>
            <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Categoría</th>
            <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Stock</th>
            <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Precio</th>
            <th class="text-right py-4 px-6 text-sm font-semibold text-slate-700">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in productsStore.products" :key="product.id" class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
            <td class="py-4 px-6">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                  <img v-if="product.image" :src="product.image" class="w-full h-full object-cover rounded-xl" />
                  <svg v-else class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <p class="font-semibold text-slate-800">{{ product.name }}</p>
                  <p v-if="product.description" class="text-xs text-slate-500 truncate max-w-[200px]">{{ product.description }}</p>
                </div>
              </div>
            </td>
            <td class="py-4 px-6 font-mono text-sm text-slate-600">{{ product.sku || '-' }}</td>
            <td class="py-4 px-6">
              <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-medium">{{ product.category?.name || '-' }}</span>
            </td>
            <td class="py-4 px-6">
              <div class="flex items-center gap-2">
                <span :class="['w-2.5 h-2.5 rounded-full', 
                  product.stock_status === 'in_stock' ? 'bg-emerald-500' : 
                  product.stock_status === 'low_stock' ? 'bg-amber-500' : 'bg-rose-500']">
                </span>
                <span class="font-medium text-slate-700">{{ product.stock_quantity || 0 }}</span>
              </div>
            </td>
            <td class="py-4 px-6 font-semibold text-slate-800">${{ product.price }}</td>
            <td class="py-4 px-6 text-right">
              <div class="flex items-center justify-end gap-2">
                <router-link :to="`/products/${product.id}/edit`" 
                  class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Editar">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </router-link>
                <button @click="deleteProduct(product.id)" 
                  class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Eliminar">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="productsStore.pagination.last_page > 1" class="flex items-center justify-between px-6 py-4 border-t border-slate-100">
        <p class="text-sm text-slate-500">
          Mostrando <span class="font-medium">{{ productsStore.products.length }}</span> de <span class="font-medium">{{ productsStore.pagination.total }}</span> productos
        </p>
        <div class="flex gap-2">
          <button :disabled="productsStore.pagination.current_page === 1" 
            @click="changePage(productsStore.pagination.current_page - 1)"
            class="px-4 py-2 text-sm font-medium rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
            Anterior
          </button>
          <button :disabled="productsStore.pagination.current_page === productsStore.pagination.last_page" 
            @click="changePage(productsStore.pagination.current_page + 1)"
            class="px-4 py-2 text-sm font-medium rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
            Siguiente
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useProductsStore } from '../../stores/products'
import ModuleHelp from '../../components/ModuleHelp.vue'
import apiClient from '../../services/api'

const productsStore = useProductsStore()
const categories = ref([])

const filters = ref({
  search: '',
  category: '',
  stock_status: '',
})

let searchTimeout

function debouncedSearch() {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchProducts()
  }, 300)
}

async function fetchProducts() {
  await productsStore.fetchProducts({
    page: productsStore.pagination.current_page,
    search: filters.value.search,
    category_id: filters.value.category,
    stock_status: filters.value.stock_status,
  })
}

function changePage(page) {
  productsStore.pagination.current_page = page
  fetchProducts()
}

async function deleteProduct(id) {
  if (!confirm('¿Estás seguro de eliminar este producto?')) return
  await productsStore.deleteProduct(id)
}

onMounted(async () => {
  await fetchProducts()
  try {
    const response = await apiClient.get('/categories')
    categories.value = response.data
  } catch (err) {
    console.error('Error fetching categories:', err)
  }
})
</script>
