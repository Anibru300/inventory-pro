<template>
  <div class="relative">
    <!-- Search Button -->
    <button 
      @click="isOpen = true"
      class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-all text-sm"
    >
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
      <span class="hidden sm:inline">Buscar...</span>
      <span class="hidden md:inline text-xs text-slate-400 bg-white px-2 py-0.5 rounded">Ctrl+K</span>
    </button>

    <!-- Modal -->
    <Teleport to="body">
      <div v-if="isOpen" class="fixed inset-0 z-50 flex items-start justify-center pt-[15vh] p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="isOpen = false"></div>
        
        <!-- Search Panel -->
        <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden">
          <!-- Search Input -->
          <div class="flex items-center gap-3 px-4 py-4 border-b border-slate-100">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
              ref="searchInput"
              v-model="searchQuery"
              type="text"
              placeholder="Buscar productos, categorías, almacenes..."
              class="flex-1 text-lg outline-none placeholder:text-slate-400"
              @keydown.esc="isOpen = false"
              @keydown.enter="selectFirstResult"
            />
            <button @click="isOpen = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Results -->
          <div class="max-h-[50vh] overflow-auto">
            <!-- Loading -->
            <div v-if="loading" class="p-8 text-center">
              <div class="w-8 h-8 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto"></div>
            </div>

            <!-- No Results -->
            <div v-else-if="searchQuery.length >= 2 && !hasResults" class="p-8 text-center text-slate-500">
              No se encontraron resultados
            </div>

            <!-- Products -->
            <div v-if="results.products?.length" class="p-2">
              <h3 class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase">Productos</h3>
              <button
                v-for="product in results.products"
                :key="product.id"
                @click="navigateTo(product.url)"
                class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-100 transition-colors text-left"
              >
                <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center overflow-hidden">
                  <img v-if="product.image" :src="product.image" class="w-full h-full object-cover" />
                  <svg v-else class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-slate-800 truncate">{{ product.title }}</p>
                  <p class="text-sm text-slate-500">{{ product.subtitle }}</p>
                </div>
                <span :class="['w-2 h-2 rounded-full', 
                  product.stock_status === 'ok' ? 'bg-emerald-500' :
                  product.stock_status === 'low_stock' ? 'bg-amber-500' : 'bg-rose-500']">
                </span>
              </button>
            </div>

            <!-- Categories -->
            <div v-if="results.categories?.length" class="p-2 border-t border-slate-100">
              <h3 class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase">Categorías</h3>
              <button
                v-for="category in results.categories"
                :key="category.id"
                @click="navigateTo(category.url)"
                class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-100 transition-colors text-left"
              >
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                  </svg>
                </div>
                <div>
                  <p class="font-medium text-slate-800">{{ category.title }}</p>
                  <p class="text-sm text-slate-500">{{ category.subtitle }}</p>
                </div>
              </button>
            </div>

            <!-- Warehouses -->
            <div v-if="results.warehouses?.length" class="p-2 border-t border-slate-100">
              <h3 class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase">Almacenes</h3>
              <button
                v-for="warehouse in results.warehouses"
                :key="warehouse.id"
                @click="navigateTo(warehouse.url)"
                class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-100 transition-colors text-left"
              >
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                </div>
                <div>
                  <p class="font-medium text-slate-800">{{ warehouse.title }}</p>
                  <p class="text-sm text-slate-500">{{ warehouse.subtitle }}</p>
                </div>
              </button>
            </div>

            <!-- Quick Commands -->
            <div v-if="!searchQuery" class="p-2 border-t border-slate-100">
              <h3 class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase">Comandos Rápidos</h3>
              <button
                v-for="command in commands"
                :key="command.id"
                @click="navigateTo(command.url)"
                class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-100 transition-colors text-left"
              >
                <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path v-if="command.icon === 'plus'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    <path v-else-if="command.icon === 'arrows'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    <path v-else-if="command.icon === 'transfer'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    <path v-else-if="command.icon === 'chart'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    <path v-else-if="command.icon === 'alert'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                </div>
                <div class="flex-1">
                  <p class="font-medium text-slate-800">{{ command.title }}</p>
                </div>
                <span class="text-xs text-slate-400 bg-white px-2 py-1 rounded">{{ command.shortcut }}</span>
              </button>
            </div>
          </div>

          <!-- Footer -->
          <div class="flex items-center justify-between px-4 py-3 bg-slate-50 text-xs text-slate-500">
            <div class="flex gap-4">
              <span class="flex items-center gap-1">
                <kbd class="px-2 py-0.5 bg-white rounded border">↑↓</kbd> Navegar
              </span>
              <span class="flex items-center gap-1">
                <kbd class="px-2 py-0.5 bg-white rounded border">↵</kbd> Seleccionar
              </span>
            </div>
            <span class="flex items-center gap-1">
              <kbd class="px-2 py-0.5 bg-white rounded border">Esc</kbd> Cerrar
            </span>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import apiClient from '../services/api'

const router = useRouter()
const isOpen = ref(false)
const searchQuery = ref('')
const searchInput = ref(null)
const loading = ref(false)
const results = ref({ products: [], categories: [], warehouses: [] })
const commands = ref([])

const hasResults = computed(() => {
  return results.value.products?.length > 0 || 
         results.value.categories?.length > 0 || 
         results.value.warehouses?.length > 0
})

let debounceTimer = null

watch(searchQuery, (newQuery) => {
  clearTimeout(debounceTimer)
  if (newQuery.length >= 2) {
    debounceTimer = setTimeout(() => performSearch(newQuery), 200)
  } else {
    results.value = { products: [], categories: [], warehouses: [] }
  }
})

watch(isOpen, (newVal) => {
  if (newVal) {
    setTimeout(() => {
      searchInput.value?.focus()
    }, 100)
    loadCommands()
  } else {
    searchQuery.value = ''
  }
})

async function performSearch(query) {
  loading.value = true
  try {
    const response = await apiClient.get('/search', { params: { q: query } })
    results.value = response.data
  } catch (err) {
    console.error('Search error:', err)
  } finally {
    loading.value = false
  }
}

async function loadCommands() {
  try {
    const response = await apiClient.get('/search/commands')
    commands.value = response.data
  } catch (err) {
    console.error('Commands error:', err)
  }
}

function navigateTo(url) {
  isOpen.value = false
  router.push(url)
}

function selectFirstResult() {
  if (results.value.products?.length) {
    navigateTo(results.value.products[0].url)
  } else if (results.value.categories?.length) {
    navigateTo(results.value.categories[0].url)
  } else if (results.value.warehouses?.length) {
    navigateTo(results.value.warehouses[0].url)
  }
}

// Keyboard shortcut
function handleKeydown(e) {
  if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
    e.preventDefault()
    isOpen.value = true
  }
}

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
})
</script>
