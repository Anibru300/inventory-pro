<template>
  <div class="bg-white rounded-2xl shadow-lg p-6">
    <h2 class="text-xl font-bold text-slate-800 mb-4">Importar Productos</h2>
    <p class="text-slate-500 mb-6">
      Sube un archivo CSV con los productos a importar. 
      <a 
        href="#" 
        @click.prevent="downloadTemplate"
        class="text-blue-600 hover:underline font-medium"
      >
        Descargar template
      </a>
    </p>

    <!-- Drop Zone -->
    <div
      ref="dropZone"
      class="border-2 border-dashed rounded-xl p-8 text-center transition-all duration-200"
      :class="[
        isDragging 
          ? 'border-blue-500 bg-blue-50' 
          : 'border-slate-300 hover:border-slate-400 bg-slate-50'
      ]"
      @dragenter.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @dragover.prevent
      @drop.prevent="handleDrop"
    >
      <div class="flex flex-col items-center gap-4">
        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
          <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
          </svg>
        </div>
        
        <div>
          <p class="text-slate-700 font-medium">
            Arrastra y suelta tu archivo CSV aquí
          </p>
          <p class="text-slate-500 text-sm mt-1">
            o
          </p>
        </div>
        
        <input
          ref="fileInput"
          type="file"
          accept=".csv,.txt"
          class="hidden"
          @change="handleFileSelect"
        >
        
        <button
          @click="$refs.fileInput.click()"
          class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors font-medium"
        >
          Seleccionar archivo
        </button>
      </div>
    </div>

    <!-- File Selected -->
    <div v-if="selectedFile" class="mt-6 p-4 bg-slate-50 rounded-xl">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <p class="font-medium text-slate-800">{{ selectedFile.name }}</p>
            <p class="text-sm text-slate-500">{{ formatFileSize(selectedFile.size) }}</p>
          </div>
        </div>
        <button
          @click="clearFile"
          class="p-2 text-slate-400 hover:text-rose-500 transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Preview Button -->
      <div class="mt-4 flex gap-3">
        <button
          @click="validateFile"
          :disabled="validating"
          class="flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors font-medium disabled:opacity-50"
        >
          <svg v-if="validating" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <template v-else>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </template>
          Vista previa
        </button>
        
        <button
          @click="importFile"
          :disabled="importing"
          class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium disabled:opacity-50 shadow-lg shadow-blue-600/20"
        >
          <svg v-if="importing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <template v-else>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
          </template>
          Importar productos
        </button>
      </div>
    </div>

    <!-- Preview Results -->
    <div v-if="previewData" class="mt-6">
      <h3 class="font-semibold text-slate-800 mb-3">
        Vista previa (primeras 10 filas)
      </h3>
      <div class="overflow-x-auto rounded-xl border border-slate-200">
        <table class="w-full text-sm">
          <thead class="bg-slate-50">
            <tr>
              <th class="px-4 py-3 text-left font-medium text-slate-600">Fila</th>
              <th class="px-4 py-3 text-left font-medium text-slate-600">Nombre</th>
              <th class="px-4 py-3 text-left font-medium text-slate-600">SKU</th>
              <th class="px-4 py-3 text-left font-medium text-slate-600">Precio</th>
              <th class="px-4 py-3 text-left font-medium text-slate-600">Stock</th>
              <th class="px-4 py-3 text-left font-medium text-slate-600">Estado</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200">
            <tr v-for="row in previewData.preview" :key="row.row">
              <td class="px-4 py-3 text-slate-600">{{ row.row }}</td>
              <td class="px-4 py-3">{{ row.data.nombre }}</td>
              <td class="px-4 py-3 font-mono text-slate-600">{{ row.data.sku }}</td>
              <td class="px-4 py-3">${{ row.data.precio }}</td>
              <td class="px-4 py-3">{{ row.data.stock_inicial }}</td>
              <td class="px-4 py-3">
                <span
                  v-if="row.valid"
                  class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700"
                >
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Válido
                </span>
                <span
                  v-else
                  class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700"
                >
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  {{ row.error }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <p class="mt-2 text-sm text-slate-500">
        Total de filas: {{ previewData.total_rows }}
      </p>
    </div>

    <!-- Import Results -->
    <div v-if="importResults" class="mt-6 p-6 rounded-xl" :class="resultClass">
      <h3 class="font-semibold mb-3">Resultados de la importación</h3>
      
      <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="text-center">
          <p class="text-2xl font-bold">{{ importResults.results.total }}</p>
          <p class="text-sm opacity-80">Total</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-emerald-600">{{ importResults.results.imported }}</p>
          <p class="text-sm opacity-80">Importados</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold" :class="importResults.results.failed > 0 ? 'text-rose-600' : ''">
            {{ importResults.results.failed }}
          </p>
          <p class="text-sm opacity-80">Errores</p>
        </div>
      </div>

      <!-- Success List -->
      <div v-if="importResults.results.success.length > 0" class="mb-4">
        <p class="font-medium mb-2">Productos importados:</p>
        <div class="max-h-40 overflow-y-auto bg-white/50 rounded-lg p-3">
          <p v-for="(msg, idx) in importResults.results.success.slice(0, 5)" :key="idx" 
            class="text-sm text-emerald-700">
            ✓ {{ msg }}
          </p>
          <p v-if="importResults.results.success.length > 5" class="text-sm text-slate-500">
            ... y {{ importResults.results.success.length - 5 }} más
          </p>
        </div>
      </div>

      <!-- Error List -->
      <div v-if="importResults.results.errors.length > 0">
        <p class="font-medium mb-2">Errores:</p>
        <div class="max-h-40 overflow-y-auto bg-white/50 rounded-lg p-3">
          <p v-for="(error, idx) in importResults.results.errors" :key="idx" 
            class="text-sm text-rose-700">
            ✗ {{ error }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import apiClient from '@/services/api'

const dropZone = ref(null)
const fileInput = ref(null)
const isDragging = ref(false)
const selectedFile = ref(null)
const validating = ref(false)
const importing = ref(false)
const previewData = ref(null)
const importResults = ref(null)

const resultClass = computed(() => {
  if (!importResults.value) return ''
  const { failed, imported } = importResults.value.results
  if (failed === 0) return 'bg-emerald-50 border border-emerald-200'
  if (imported === 0) return 'bg-rose-50 border border-rose-200'
  return 'bg-amber-50 border border-amber-200'
})

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i]
}

const handleDrop = (e) => {
  isDragging.value = false
  const files = e.dataTransfer.files
  if (files.length > 0) {
    selectFile(files[0])
  }
}

const handleFileSelect = (e) => {
  const files = e.target.files
  if (files.length > 0) {
    selectFile(files[0])
  }
}

const selectFile = (file) => {
  if (file.type !== 'text/csv' && !file.name.endsWith('.csv')) {
    alert('Por favor selecciona un archivo CSV válido')
    return
  }
  selectedFile.value = file
  previewData.value = null
  importResults.value = null
}

const clearFile = () => {
  selectedFile.value = null
  previewData.value = null
  importResults.value = null
  fileInput.value.value = ''
}

const downloadTemplate = async () => {
  try {
    const response = await apiClient.get('/import/template', {
      responseType: 'blob'
    })
    
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', 'template_importacion_productos.csv')
    document.body.appendChild(link)
    link.click()
    link.remove()
  } catch (error) {
    alert('Error al descargar el template')
  }
}

const validateFile = async () => {
  if (!selectedFile.value) return
  
  validating.value = true
  
  try {
    const formData = new FormData()
    formData.append('file', selectedFile.value)
    
    const response = await apiClient.post('/import/validate', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    previewData.value = response.data
  } catch (error) {
    alert('Error al validar: ' + (error.response?.data?.message || error.message))
  } finally {
    validating.value = false
  }
}

const importFile = async () => {
  if (!selectedFile.value) return
  
  if (!confirm('¿Estás seguro de importar estos productos?')) {
    return
  }
  
  importing.value = true
  
  try {
    const formData = new FormData()
    formData.append('file', selectedFile.value)
    
    const response = await apiClient.post('/import/products', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    importResults.value = response.data
    
    if (response.data.results.failed === 0) {
      setTimeout(() => {
        clearFile()
      }, 3000)
    }
  } catch (error) {
    alert('Error al importar: ' + (error.response?.data?.message || error.message))
  } finally {
    importing.value = false
  }
}
</script>
