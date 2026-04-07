<template>
  <div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold">Productos</h1>
        <p class="text-text-secondary">Gestiona tu catálogo de productos</p>
      </div>
      <router-link
        to="/products/new"
        class="btn-primary"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nuevo Producto
      </router-link>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
      <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
          <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
              v-model="filters.search"
              @input="debouncedSearch"
              type="text"
              placeholder="Buscar productos..."
              class="w-full pl-10 pr-4 py-2.5 bg-bg-tertiary border border-border-default rounded-lg focus:outline-none focus:border-accent-primary"
            />
          </div>
        </div>
        <div class="flex gap-4">
          <select v-model="filters.category" @change="fetchProducts" class="bg-bg-tertiary border border-border-default rounded-lg px-4 py-2.5 focus:outline-none focus:border-accent-primary">
            <option value="">Todas las categorías</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
          <select v-model="filters.stock_status" @change="fetchProducts" class="bg-bg-tertiary border border-border-default rounded-lg px-4 py-2.5 focus:outline-none focus:border-accent-primary">
            <option value="">Estado de stock</option>
            <option value="in_stock">En stock</option>
            <option value="low_stock">Stock bajo</option>
            <option value="out_of_stock">Sin stock</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Products Table -->
    <div class="card overflow-hidden">
      <div v-if="productsStore.loading" class="p-8 text-center">
        <svg class="w-8 h-8 animate-spin mx-auto text-accent-primary" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <div v-else-if="productsStore.products.length === 0" class="p-8 text-center text-text-secondary">
        No se encontraron productos
      </div>

      <table v-else class="data-table">
        <thead>
          <tr>
            <th>Producto</th>
            <th>SKU</th>
            <th>Categoría</th>
            <th>Stock</th>
            <th>Costo</th>
            <th>Precio</th>
            <th class="text-right">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in productsStore.products" :key="product.id" class="hover:bg-bg-tertiary/50">
            <td>
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-bg-tertiary rounded-lg flex items-center justify-center">
                  <img v-if="product.image" :src="product.image" class="w-full h-full object-cover rounded-lg" />
                  <svg v-else class="w-5 h-5 text-text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <p class="font-medium">{{ product.name }}</p>
                  <p v-if="product.description" class="text-xs text-text-tertiary truncate max-w-[200px]">{{ product.description }}</p>
                </div>
              </div>
            </td>
            <td class="font-mono text-sm">{{ product.sku }}</td>
            <td>
              <span class="px-2 py-1 bg-bg-tertiary rounded-full text-xs">{{ product.category?.name || '-' }}</span>
            </td>
            <td>
              <div class="flex items-center gap-2">
                <span :class="[
                  'w-2 h-2 rounded-full',
                  product.stock_status === 'in_stock' ? 'bg-success' :
                  product.stock_status === 'low_stock' ? 'bg-warning' : 'bg-danger'
                ]"></span>
                <span>{{ product.stock_quantity || 0 }}</span>
              </div>
            </td>
            <td class="text-text-secondary">${{ product.cost }}</td>
            <td class="font-medium">${{ product.price }}</td>
            <td class="text-right">
              <div class="flex items-center justify-end gap-2">
                <router-link
                  :to="`/products/${product.id}`"
                  class="p-2 text-text-secondary hover:text-accent-primary rounded-lg hover:bg-bg-tertiary transition-colors"
                  title="Ver detalle"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </router-link>
                <button
                  @click="deleteProduct(product.id)"
                  class="p-2 text-text-secondary hover:text-danger rounded-lg hover:bg-danger/10 transition-colors"
                  title="Eliminar"
                >
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
      <div v-if="productsStore.pagination.last_page > 1" class="flex items-center justify-between px-4 py-3 border-t border-border-default">
        <p class="text-sm text-text-secondary">
          Mostrando {{ productsStore.products.length }} de {{ productsStore.pagination.total }} productos
        </p>
        <div class="flex gap-2">
          <button
            :disabled="productsStore.pagination.current_page === 1"
            @click="changePage(productsStore.pagination.current_page - 1)"
            class="px-3 py-1.5 text-sm rounded-lg bg-bg-tertiary hover:bg-bg-elevated disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            Anterior
          </button>
          <button
            :disabled="productsStore.pagination.current_page === productsStore.pagination.last_page"
            @click="changePage(productsStore.pagination.current_page + 1)"
            class="px-3 py-1.5 text-sm rounded-lg bg-bg-tertiary hover:bg-bg-elevated disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
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
  // Fetch categories
  try {
    const response = await apiClient.get('/categories')
    categories.value = response.data
  } catch (err) {
    console.error('Error fetching categories:', err)
  }
})
</script>