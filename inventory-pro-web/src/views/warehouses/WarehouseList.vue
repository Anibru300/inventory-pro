<template>
  <div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
      <div>
        <h1 class="text-3xl font-bold gradient-text font-heading mb-2">Almacenes</h1>
        <p class="text-cj-silver-dark font-tagline italic">Gestión de ubicaciones de inventario</p>
      </div>
      <button @click="showCreateModal = true" class="btn-primary">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nuevo Almacén
      </button>
    </div>

    <!-- Warehouses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="warehouse in warehouses" :key="warehouse.id" class="card-premium p-6 relative group">
        <!-- Primary Badge -->
        <div v-if="warehouse.is_primary" class="absolute top-4 right-4">
          <span class="badge-gold">Principal</span>
        </div>

        <div class="flex items-start gap-4">
          <div class="w-14 h-14 bg-cj-gold/10 rounded-xl flex items-center justify-center border border-cj-gold/20">
            <svg class="w-7 h-7 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-semibold font-heading mb-1">{{ warehouse.name }}</h3>
            <p class="text-sm text-cj-gold font-medium mb-2">{{ warehouse.code }}</p>
            <p class="text-sm text-cj-silver-dark">
              {{ warehouse.address || 'Sin dirección' }}
            </p>
            <p class="text-sm text-cj-silver-dark" v-if="warehouse.city">
              {{ warehouse.city }}, {{ warehouse.state }}
            </p>
          </div>
        </div>

        <div class="mt-4 pt-4 border-t border-white/10 flex justify-between items-center">
          <span class="text-sm text-cj-silver-dark">
            {{ warehouse.products_count || 0 }} productos
          </span>
          <div class="flex gap-2">
            <button @click="editWarehouse(warehouse)" class="p-2 text-cj-silver-dark hover:text-cj-gold transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </button>
            <button v-if="!warehouse.is_primary" @click="deleteWarehouse(warehouse)" class="p-2 text-cj-silver-dark hover:text-danger transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create Modal -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
      <div class="card-premium w-full max-w-md p-6">
        <h3 class="text-xl font-semibold mb-4 font-heading">Nuevo Almacén</h3>
        <form @submit.prevent="createWarehouse" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2 text-cj-silver">Nombre *</label>
            <input v-model="createForm.name" type="text" required class="w-full" placeholder="Ej: Almacén Norte" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 text-cj-silver">Código *</label>
            <input v-model="createForm.code" type="text" required class="w-full" placeholder="Ej: ALM-Norte" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 text-cj-silver">Dirección</label>
            <input v-model="createForm.address" type="text" class="w-full" placeholder="Calle, número, colonia" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2 text-cj-silver">Ciudad</label>
              <input v-model="createForm.city" type="text" class="w-full" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-cj-silver">Estado</label>
              <input v-model="createForm.state" type="text" class="w-full" />
            </div>
          </div>
          <div class="flex gap-4 pt-4">
            <button type="button" @click="showCreateModal = false" class="btn-secondary flex-1">Cancelar</button>
            <button type="submit" :disabled="creating" class="btn-primary flex-1">
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
