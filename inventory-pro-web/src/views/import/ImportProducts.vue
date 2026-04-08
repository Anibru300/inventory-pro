<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-slate-800 mb-2">Importación Masiva</h1>
      <p class="text-slate-500">Importa productos desde archivo Excel o CSV</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Upload Section -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Upload Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-blue-600 rounded-full"></span>
            Subir Archivo
          </h2>

          <!-- Drop Zone -->
          <div
            @dragover.prevent
            @drop.prevent="handleDrop"
            :class="['border-2 border-dashed rounded-xl p-8 text-center transition-all',
              isDragging ? 'border-blue-500 bg-blue-50' : 'border-slate-300 hover:border-slate-400']"
            @dragenter="isDragging = true"
            @dragleave="isDragging = false"
          >
            <div v-if="!selectedFile" class="space-y-4">
              <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
              </div>
              <div>
                <p class="text-slate-700 font-medium">Arrastra tu archivo aquí</p>
                <p class="text-slate-400 text-sm">o</p>
              </div>
              <label class="inline-block">
                <input type="file" accept=".csv,.xlsx,.xls" class="hidden" @change="handleFileSelect" />
                <span class="px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all cursor-pointer inline-block">
                  Seleccionar archivo
                </span>
              </label>
              <p class="text-xs text-slate-400">Formatos: CSV, XLSX, XLS (Máx. 10MB)</p>
            </div>

            <div v-else class="space-y-4">
              <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p class="text-slate-700 font-medium">{{ selectedFile.name }}</p>
                <p class="text-slate-400 text-sm">{{ formatFileSize(selectedFile.size) }}</p>
              </div>
              <div class="flex justify-center gap-3">
                <button @click="selectedFile = null" class="px-4 py-2 text-slate-600 hover:text-slate-800">
                  Cambiar
                </button>
                <button 
                  @click="uploadFile" 
                  :disabled="uploading"
                  class="px-6 py-2 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all disabled:opacity-50 flex items-center gap-2"
                >
                  <svg v-if="uploading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ uploading ? 'Importando...' : 'Importar' }}
                </button>
              </div>
            </div>
          </div>

          <!-- Results -->
          <div v-if="results" class="mt-6 p-4 bg-slate-50 rounded-xl">
            <h3 class="font-semibold text-slate-800 mb-3">Resultados</h3>
            <div class="grid grid-cols-3 gap-4">
              <div class="text-center p-3 bg-white rounded-lg">
                <p class="text-2xl font-bold text-emerald-600">{{ results.created }}</p>
                <p class="text-sm text-slate-500">Creados</p>
              </div>
              <div class="text-center p-3 bg-white rounded-lg">
                <p class="text-2xl font-bold text-blue-600">{{ results.updated }}</p>
                <p class="text-sm text-slate-500">Actualizados</p>
              </div>
              <div class="text-center p-3 bg-white rounded-lg">
                <p class="text-2xl font-bold" :class="results.errors.length ? 'text-rose-600' : 'text-slate-400'">
                  {{ results.errors.length }}
                </p>
                <p class="text-sm text-slate-500">Errores</p>
              </div>
            </div>
            <div v-if="results.errors.length" class="mt-4">
              <p class="text-sm font-medium text-rose-600 mb-2">Errores encontrados:</p>
              <ul class="text-sm text-slate-600 space-y-1 max-h-32 overflow-auto">
                <li v-for="(error, index) in results.errors" :key="index" class="flex gap-2">
                  <span class="text-rose-500">•</span>
                  {{ error }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Template Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <h3 class="font-semibold text-slate-800 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Plantilla
          </h3>
          <p class="text-sm text-slate-500 mb-4">
            Descarga la plantilla con el formato correcto para importar productos.
          </p>
          <button 
            @click="downloadTemplate"
            class="w-full px-4 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Descargar CSV
          </button>
        </div>

        <!-- Instructions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <h3 class="font-semibold text-slate-800 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Instrucciones
          </h3>
          <ul class="space-y-2 text-sm text-slate-600">
            <li class="flex gap-2">
              <span class="text-blue-500">1.</span>
              Descarga la plantilla CSV
            </li>
            <li class="flex gap-2">
              <span class="text-blue-500">2.</span>
              Llena los datos (no modifiques los encabezados)
            </li>
            <li class="flex gap-2">
              <span class="text-blue-500">3.</span>
              Guarda como CSV o Excel
            </li>
            <li class="flex gap-2">
              <span class="text-blue-500">4.</span>
              Sube el archivo aquí
            </li>
          </ul>
        </div>

        <!-- Required Fields -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <h3 class="font-semibold text-slate-800 mb-3">Campos Requeridos</h3>
          <div class="space-y-2">
            <div class="flex items-center gap-2 text-sm">
              <span class="w-2 h-2 bg-rose-500 rounded-full"></span>
              <span class="text-slate-600">sku</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
              <span class="w-2 h-2 bg-rose-500 rounded-full"></span>
              <span class="text-slate-600">name</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
              <span class="w-2 h-2 bg-slate-300 rounded-full"></span>
              <span class="text-slate-500">cost (opcional)</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
              <span class="w-2 h-2 bg-slate-300 rounded-full"></span>
              <span class="text-slate-500">price (opcional)</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import apiClient from '../../services/api'

const isDragging = ref(false)
const selectedFile = ref(null)
const uploading = ref(false)
const results = ref(null)

function handleDrop(e) {
  isDragging.value = false
  const file = e.dataTransfer.files[0]
  if (file && isValidFile(file)) {
    selectedFile.value = file
    results.value = null
  } else {
    alert('Por favor selecciona un archivo CSV, XLSX o XLS válido')
  }
}

function handleFileSelect(e) {
  const file = e.target.files[0]
  if (file && isValidFile(file)) {
    selectedFile.value = file
    results.value = null
  }
}

function isValidFile(file) {
  const validTypes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
  return validTypes.includes(file.type) || file.name.endsWith('.csv') || file.name.endsWith('.xlsx') || file.name.endsWith('.xls')
}

function formatFileSize(bytes) {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i]
}

async function uploadFile() {
  if (!selectedFile.value) return

  uploading.value = true
  results.value = null

  try {
    const formData = new FormData()
    formData.append('file', selectedFile.value)

    const response = await apiClient.post('/products-import/bulk', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })

    results.value = response.data
  } catch (err) {
    alert('Error al importar: ' + (err.response?.data?.message || err.message))
  } finally {
    uploading.value = false
  }
}

async function downloadTemplate() {
  try {
    const response = await apiClient.get('/products-import/template', {
      responseType: 'blob',
    })

    const blob = new Blob([response.data], { type: 'text/csv' })
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = 'template_productos.csv'
    a.click()
    window.URL.revokeObjectURL(url)
  } catch (err) {
    alert('Error al descargar plantilla')
  }
}
</script>
