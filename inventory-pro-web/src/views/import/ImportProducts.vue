<template>
  <div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
      <div>
        <h1 class="text-3xl font-bold gradient-text font-heading mb-2">Importar Productos</h1>
        <p class="text-cj-silver-dark font-tagline italic">Sube productos masivamente desde Excel o CSV</p>
      </div>
      <button @click="downloadTemplate" class="btn-secondary">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        Descargar Plantilla
      </button>
    </div>

    <!-- Upload Area -->
    <div class="card-premium p-8 mb-6">
      <div
        @dragover.prevent
        @drop.prevent="handleDrop"
        :class="[
          'border-2 border-dashed rounded-xl p-12 text-center transition-all',
          dragOver ? 'border-cj-gold bg-cj-gold/5' : 'border-cj-silver-dim/30',
          file ? 'bg-success/5 border-success' : ''
        ]"
        @dragenter="dragOver = true"
        @dragleave="dragOver = false"
      >
        <input
          ref="fileInput"
          type="file"
          accept=".csv,.xlsx,.xls"
          class="hidden"
          @change="handleFileSelect"
        />
        
        <div v-if="!file" class="space-y-4">
          <div class="w-20 h-20 bg-cj-gold/10 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-10 h-10 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
          </div>
          <div>
            <p class="text-lg font-medium text-cj-silver">Arrastra tu archivo aquí o</p>
            <button @click="$refs.fileInput.click()" class="text-cj-gold hover:underline mt-1">
              selecciona un archivo
            </button>
          </div>
          <p class="text-sm text-cj-silver-dark">Formatos soportados: CSV, Excel (.xlsx, .xls)</p>
        </div>

        <div v-else class="space-y-4">
          <div class="w-20 h-20 bg-success/10 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-10 h-10 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <p class="text-lg font-medium text-cj-silver">{{ file.name }}</p>
          <p class="text-sm text-cj-silver-dark">{{ formatFileSize(file.size) }}</p>
          <button @click="file = null; preview = []" class="text-danger hover:underline text-sm">
            Eliminar archivo
          </button>
        </div>
      </div>
    </div>

    <!-- Preview -->
    <div v-if="preview.length > 0" class="card-premium p-6 mb-6">
      <h3 class="text-lg font-semibold mb-4 font-heading">Vista previa ({{ preview.length }} productos)</h3>
      <div class="overflow-x-auto">
        <table class="data-table">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>SKU</th>
              <th>Categoría</th>
              <th>Almacén</th>
              <th>Cantidad</th>
              <th>Costo</th>
              <th>Precio</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, index) in preview.slice(0, 10)" :key="index">
              <td>{{ row.nombre }}</td>
              <td class="font-mono text-cj-gold">{{ row.sku }}</td>
              <td>{{ row.categoria || '-' }}</td>
              <td>{{ row.almacen || '-' }}</td>
              <td class="text-center">{{ row.cantidad || 0 }}</td>
              <td class="text-right">${{ row.costo || 0 }}</td>
              <td class="text-right">${{ row.precio_venta || 0 }}</td>
            </tr>
          </tbody>
        </table>
        <p v-if="preview.length > 10" class="text-center text-cj-silver-dark mt-4">
          ... y {{ preview.length - 10 }} productos más
        </p>
      </div>

      <div class="flex gap-4 mt-6">
        <button @click="file = null; preview = []" class="btn-secondary flex-1">
          Cancelar
        </button>
        <button 
          @click="importProducts" 
          :disabled="importing"
          class="btn-primary flex-1"
        >
          <svg v-if="importing" class="w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ importing ? 'Importando...' : 'Importar ' + preview.length + ' Productos' }}
        </button>
      </div>
    </div>

    <!-- Results -->
    <div v-if="results" class="card-premium p-6" :class="results.success ? 'border-success' : 'border-danger'">
      <div class="flex items-center gap-4 mb-4">
        <div 
          class="w-12 h-12 rounded-full flex items-center justify-center"
          :class="results.success ? 'bg-success/10' : 'bg-danger/10'"
        >
          <svg 
            class="w-6 h-6" 
            :class="results.success ? 'text-success' : 'text-danger'"
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
          >
            <path 
              v-if="results.success"
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M5 13l4 4L19 7"
            />
            <path 
              v-else
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </div>
        <div>
          <h3 class="text-lg font-semibold">{{ results.message }}</h3>
          <p v-if="results.success" class="text-cj-silver-dark">
            {{ results.results.created }} creados, {{ results.results.updated }} actualizados
          </p>
        </div>
      </div>

      <!-- Errors -->
      <div v-if="results.results?.errors?.length > 0" class="mt-4">
        <p class="text-danger font-medium mb-2">Errores encontrados:</p>
        <div class="bg-danger/5 border border-danger/20 rounded-lg p-4 max-h-40 overflow-y-auto">
          <ul class="space-y-1 text-sm">
            <li v-for="(error, index) in results.results.errors" :key="index" class="text-danger">
              Fila {{ error.row }} ({{ error.sku }}): {{ error.error }}
            </li>
          </ul>
        </div>
      </div>

      <div class="flex gap-4 mt-6">
        <button @click="reset" class="btn-secondary flex-1">
          Importar más productos
        </button>
        <router-link to="/products" class="btn-primary flex-1 text-center">
          Ver Productos
        </router-link>
      </div>
    </div>

    <!-- Instructions -->
    <div v-if="!file && !results" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="card-premium p-6">
        <h3 class="text-lg font-semibold mb-4 font-heading">Formato del archivo</h3>
        <p class="text-cj-silver-dark text-sm mb-4">
          El archivo debe tener las siguientes columnas:
        </p>
        <ul class="space-y-2 text-sm">
          <li class="flex items-center gap-2">
            <span class="w-2 h-2 bg-cj-gold rounded-full"></span>
            <code class="text-cj-gold">nombre</code> - Nombre del producto *
          </li>
          <li class="flex items-center gap-2">
            <span class="w-2 h-2 bg-cj-gold rounded-full"></span>
            <code class="text-cj-gold">sku</code> - Código único *
          </li>
          <li class="flex items-center gap-2">
            <span class="w-2 h-2 bg-cj-silver rounded-full"></span>
            <code class="text-cj-gold">categoria</code> - Nombre de categoría
          </li>
          <li class="flex items-center gap-2">
            <span class="w-2 h-2 bg-cj-silver rounded-full"></span>
            <code class="text-cj-gold">almacen</code> - Nombre del almacén
          </li>
          <li class="flex items-center gap-2">
            <span class="w-2 h-2 bg-cj-silver rounded-full"></span>
            <code class="text-cj-gold">cantidad</code> - Stock inicial
          </li>
          <li class="flex items-center gap-2">
            <span class="w-2 h-2 bg-cj-silver rounded-full"></span>
            <code class="text-cj-gold">costo</code> - Costo de compra
          </li>
          <li class="flex items-center gap-2">
            <span class="w-2 h-2 bg-cj-silver rounded-full"></span>
            <code class="text-cj-gold">precio_venta</code> - Precio de venta
          </li>
          <li class="flex items-center gap-2">
            <span class="w-2 h-2 bg-cj-silver rounded-full"></span>
            <code class="text-cj-gold">stock_minimo</code> - Stock mínimo
          </li>
        </ul>
        <p class="text-xs text-cj-silver-dark mt-4">* Campos obligatorios</p>
      </div>

      <div class="card-premium p-6">
        <h3 class="text-lg font-semibold mb-4 font-heading">Notas importantes</h3>
        <ul class="space-y-3 text-sm text-cj-silver-dark">
          <li class="flex gap-3">
            <span class="text-cj-gold">•</span>
            <span>Si el SKU ya existe, el producto se actualizará con los nuevos datos</span>
          </li>
          <li class="flex gap-3">
            <span class="text-cj-gold">•</span>
            <span>Las categorías y almacenes se crearán automáticamente si no existen</span>
          </li>
          <li class="flex gap-3">
            <span class="text-cj-gold">•</span>
            <span>Se puede subir un máximo de 1000 productos por archivo</span>
          </li>
          <li class="flex gap-3">
            <span class="text-cj-gold">•</span>
            <span>El formato CSV debe usar comas (,) como separador</span>
          </li>
          <li class="flex gap-3">
            <span class="text-cj-gold">•</span>
            <span>Para Excel, use formato .xlsx para mejor compatibilidad</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import apiClient from '../../services/api'

const fileInput = ref(null)
const file = ref(null)
const dragOver = ref(false)
const preview = ref([])
const importing = ref(false)
const results = ref(null)

function formatFileSize(bytes) {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i]
}

function handleFileSelect(event) {
  const selectedFile = event.target.files[0]
  if (selectedFile) {
    processFile(selectedFile)
  }
}

function handleDrop(event) {
  dragOver.value = false
  const droppedFile = event.dataTransfer.files[0]
  if (droppedFile) {
    processFile(droppedFile)
  }
}

function processFile(selectedFile) {
  // Validar extensión
  const allowedTypes = ['.csv', '.xlsx', '.xls']
  const extension = selectedFile.name.substring(selectedFile.name.lastIndexOf('.')).toLowerCase()
  
  if (!allowedTypes.includes(extension)) {
    alert('Formato no soportado. Use CSV o Excel (.xlsx, .xls)')
    return
  }

  file.value = selectedFile
  parseCSV(selectedFile)
}

function parseCSV(file) {
  const reader = new FileReader()
  reader.onload = (e) => {
    const text = e.target.result
    const lines = text.split('\n').filter(line => line.trim() !== '')
    
    if (lines.length < 2) {
      alert('El archivo está vacío o no tiene el formato correcto')
      return
    }

    // Parse headers
    const headers = lines[0].split(',').map(h => h.trim().toLowerCase().replace(/\r/g, ''))
    
    // Validate required columns
    if (!headers.includes('nombre') || !headers.includes('sku')) {
      alert('El archivo debe tener al menos las columnas "nombre" y "sku"')
      return
    }

    // Parse rows
    const rows = []
    for (let i = 1; i < lines.length; i++) {
      const values = lines[i].split(',').map(v => v.trim().replace(/\r/g, ''))
      const row = {}
      headers.forEach((header, index) => {
        row[header] = values[index] || ''
      })
      
      // Only add if has name and sku
      if (row.nombre && row.sku) {
        rows.push({
          nombre: row.nombre,
          sku: row.sku,
          categoria: row.categoria || row.category || '',
          almacen: row.almacen || row.warehouse || '',
          cantidad: parseFloat(row.cantidad || row.quantity || 0) || 0,
          costo: parseFloat(row.costo || row.cost || 0) || 0,
          precio_venta: parseFloat(row.precio_venta || row.price || 0) || 0,
          min_stock: parseFloat(row.stock_minimo || row.min_stock || 0) || 0,
        })
      }
    }

    preview.value = rows
  }
  reader.readAsText(file)
}

async function importProducts() {
  if (preview.value.length === 0) return

  importing.value = true
  try {
    const response = await apiClient.post('/import/products', {
      products: preview.value
    })
    results.value = response.data
  } catch (err) {
    results.value = {
      success: false,
      message: err.response?.data?.message || 'Error al importar',
      results: { errors: [] }
    }
  } finally {
    importing.value = false
  }
}

function downloadTemplate() {
  window.open(`${import.meta.env.VITE_API_URL || 'https://inventory-pro-api-v2.onrender.com/api'}/import/template`, '_blank')
}

function reset() {
  file.value = null
  preview.value = []
  results.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}
</script>