<template>
  <div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
      <div>
        <h1 class="text-3xl font-bold gradient-text font-heading mb-2">Categorías</h1>
        <p class="text-cj-silver-dark font-tagline italic">Organiza tus productos por categorías</p>
      </div>
      <button @click="showCreateModal = true" class="btn-primary">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nueva Categoría
      </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
      <div v-for="category in categories" :key="category.id" class="card-premium p-5 group hover:border-cj-gold/30 transition-all">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center" :style="{ backgroundColor: category.color + '20', border: `1px solid ${category.color}40` }">
            <div class="w-6 h-6 rounded-full" :style="{ backgroundColor: category.color }"></div>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-semibold font-heading truncate">{{ category.name }}</h3>
            <p class="text-sm text-cj-silver-dark">{{ category.products_count || 0 }} productos</p>
          </div>
          <button @click="deleteCategory(category)" class="opacity-0 group-hover:opacity-100 p-2 text-cj-silver-dark hover:text-danger transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="categories.length === 0" class="card-premium p-12 text-center">
      <div class="w-20 h-20 bg-cj-gold/10 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-10 h-10 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        </svg>
      </div>
      <h3 class="text-lg font-semibold mb-2">No hay categorías</h3>
      <p class="text-cj-silver-dark mb-4">Crea tu primera categoría para organizar productos</p>
      <button @click="showCreateModal = true" class="btn-primary">Crear Categoría</button>
    </div>

    <!-- Create Modal -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
      <div class="card-premium w-full max-w-md p-6">
        <h3 class="text-xl font-semibold mb-4 font-heading">Nueva Categoría</h3>
        <form @submit.prevent="createCategory" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2 text-cj-silver">Nombre *</label>
            <input v-model="createForm.name" type="text" required class="w-full" placeholder="Ej: Electrónicos" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 text-cj-silver">Color</label>
            <div class="flex gap-3 flex-wrap">
              <button v-for="color in colors" :key="color" type="button" @click="createForm.color = color" 
                class="w-10 h-10 rounded-lg border-2 transition-all" 
                :class="createForm.color === color ? 'border-white scale-110' : 'border-transparent'"
                :style="{ backgroundColor: color }"></button>
            </div>
          </div>
          <div class="flex gap-4 pt-4">
            <button type="button" @click="showCreateModal = false" class="btn-secondary flex-1">Cancelar</button>
            <button type="submit" :disabled="creating" class="btn-primary flex-1">
              {{ creating ? 'Creando...' : 'Crear' }}
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

const categories = ref([])
const showCreateModal = ref(false)
const creating = ref(false)
const createForm = ref({ name: '', color: '#3b82f6' })

const colors = ['#3b82f6', '#ec4899', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4', '#6366f1', '#84cc16', '#f97316']

async function fetchCategories() {
  try {
    const response = await apiClient.get('/categories')
    categories.value = response.data
  } catch (err) {
    console.error('Error fetching categories:', err)
  }
}

async function createCategory() {
  creating.value = true
  try {
    await apiClient.post('/categories', createForm.value)
    showCreateModal.value = false
    createForm.value = { name: '', color: '#3b82f6' }
    fetchCategories()
  } catch (err) {
    console.error('Error creating category:', err)
  }
  creating.value = false
}

async function deleteCategory(category) {
  if (!confirm(`¿Eliminar la categoría "${category.name}"?`)) return
  try {
    await apiClient.delete(`/categories/${category.id}`)
    fetchCategories()
  } catch (err) {
    console.error('Error deleting category:', err)
  }
}

onMounted(fetchCategories)
</script>
