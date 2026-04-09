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
          <h1 class="text-3xl font-bold text-slate-800 mb-1">Almacenes</h1>
          <p class="text-slate-500">Gestión de ubicaciones de inventario</p>
        </div>
      </div>
      <button @click="showCreateModal = true" 
        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 hover:shadow-xl hover:shadow-blue-600/30">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nuevo Almacén
      </button>
    </div>

    <!-- Warehouses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="warehouse in warehouses" :key="warehouse.id" 
        class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative group hover:shadow-md transition-all">
        <!-- Primary Badge -->
        <div v-if="warehouse.is_primary" class="absolute top-4 right-4">
          <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Principal</span>
        </div>

        <div class="flex items-start gap-4">
          <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center">
            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-slate-800 mb-1">{{ warehouse.name }}</h3>
            <p class="text-sm text-indigo-600 font-medium mb-2">{{ warehouse.code }}</p>
            <p class="text-sm text-slate-500">{{ warehouse.address || 'Sin dirección' }}</p>
            <p class="text-sm text-slate-500" v-if="warehouse.city">{{ warehouse.city }}, {{ warehouse.state }}</p>
          </div>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center">
          <span class="text-sm text-slate-500">
            <span class="font-semibold text-slate-700">{{ warehouse.products_count || 0 }}</span> productos
          </span>
          <div class="flex gap-2">
            <button @click="editWarehouse(warehouse)" 
              class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </button>
            <button v-if="!warehouse.is_primary" @click="deleteWarehouse(warehouse)" 
              class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="warehouses.length === 0" class="text-center py-12">
      <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
      </div>
      <p class="text-slate-500 text-lg">No hay almacenes configurados</p>
      <button @click="showCreateModal = true" class="mt-4 inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
        Crear primer almacén
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>

    <!-- Create Modal -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-xl font-bold text-slate-800 mb-6">Nuevo Almacén</h3>
        <form @submit.prevent="createWarehouse" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2 text-slate-700">Nombre *</label>
            <input v-model="createForm.name" type="text" required 
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
              placeholder="Ej: Almacén Norte" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 text-slate-700">Código *</label>
            <input v-model="createForm.code" type="text" required 
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
              placeholder="Ej: ALM-Norte" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 text-slate-700">Dirección</label>
            <input v-model="createForm.address" type="text" 
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
              placeholder="Calle, número, colonia" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Ciudad</label>
              <input v-model="createForm.city" type="text" 
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Estado</label>
              <input v-model="createForm.state" type="text" 
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
            </div>
          </div>
          <div class="flex gap-3 pt-4">
            <button type="button" @click="showCreateModal = false" 
              class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors">
              Cancelar
            </button>
            <button type="submit" :disabled="creating" 
              class="flex-1 px-4 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors disabled:opacity-50">
              {{ creating ? 'Creando...' : 'Crear Almacén' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import apiClient from '../../services/api'

const warehouses = ref([])
const showCreateModal = ref(false)
const creating = ref(false)
const createForm = ref({ name: '', code: '', address: '', city: '', state: '' })

async function fetchWarehouses() {
  try {
    const response = await apiClient.get('/warehouses')
    warehouses.value = response.data
  } catch (err) {
    console.error('Error fetching warehouses:', err)
  }
}

async function createWarehouse() {
  creating.value = true
  try {
    await apiClient.post('/warehouses', createForm.value)
    showCreateModal.value = false
    createForm.value = { name: '', code: '', address: '', city: '', state: '' }
    fetchWarehouses()
  } catch (err) {
    console.error('Error creating warehouse:', err)
  }
  creating.value = false
}

async function deleteWarehouse(warehouse) {
  if (!confirm(`¿Eliminar el almacén "${warehouse.name}"?`)) return
  try {
    await apiClient.delete(`/warehouses/${warehouse.id}`)
    fetchWarehouses()
  } catch (err) {
    console.error('Error deleting warehouse:', err)
  }
}

function editWarehouse(warehouse) {
  // TODO: Implement edit modal
  console.log('Edit warehouse:', warehouse)
}

onMounted(fetchWarehouses)
</script>
