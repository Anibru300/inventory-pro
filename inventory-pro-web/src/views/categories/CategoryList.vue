<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <div>
        <h1 class="text-3xl font-bold text-slate-800 mb-1">Categorías</h1>
        <p class="text-slate-500">Organiza tus productos por categorías</p>
      </div>
      <button @click="showCreateModal = true" 
        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nueva Categoría
      </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
      <div v-for="category in categories" :key="category.id" 
        class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 group hover:shadow-md transition-all">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center" 
            :style="{ backgroundColor: category.color + '20' }">
            <div class="w-6 h-6 rounded-full" :style="{ backgroundColor: category.color }"></div>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-semibold text-slate-800 truncate">{{ category.name }}</h3>
            <p class="text-sm text-slate-500">{{ category.products_count || 0 }} productos</p>
          </div>
          <button @click="deleteCategory(category)" 
            class="opacity-0 group-hover:opacity-100 p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="categories.length === 0" class="text-center py-12">
      <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        </svg>
      </div>
      <h3 class="text-lg font-semibold text-slate-800 mb-2">No hay categorías</h3>
      <p class="text-slate-500 mb-4">Crea tu primera categoría para organizar productos</p>
      <button @click="showCreateModal = true" 
        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all">
        Crear Categoría
      </button>
    </div>

    <!-- Create Modal -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-xl font-bold text-slate-800 mb-6">Nueva Categoría</h3>
        <form @submit.prevent="createCategory" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2 text-slate-700">Nombre *</label>
            <input v-model="createForm.name" type="text" required 
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
              placeholder="Ej: Electrónicos" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 text-slate-700">Color</label>
            <div class="flex gap-3 flex-wrap">
              <button v-for="color in colors" :key="color" type="button" @click="createForm.color = color" 
                class="w-10 h-10 rounded-lg border-2 transition-all" 
                :class="createForm.color === color ? 'border-slate-800 scale-110' : 'border-transparent'"
                :style="{ backgroundColor: color }"></button>
            </div>
          </div>
          <div class="flex gap-3 pt-4">
            <button type="button" @click="showCreateModal = false" 
              class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors">
              Cancelar
            </button>
            <button type="submit" :disabled="creating" 
              class="flex-1 px-4 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors disabled:opacity-50">
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
