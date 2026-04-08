<template>
  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
      <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
      </svg>
      Imprimir Etiquetas
    </h3>

    <!-- Label Size Selection -->
    <div class="mb-4">
      <label class="block text-sm font-medium mb-2 text-slate-700">Tamaño de Etiqueta</label>
      <select v-model="labelSize" @change="updatePreview" 
        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500">
        <option v-for="template in templates" :key="template.id" :value="template.size">
          {{ template.name }}
        </option>
      </select>
    </div>

    <!-- Warehouse Selection (for stock info) -->
    <div v-if="warehouses.length > 0" class="mb-4">
      <label class="block text-sm font-medium mb-2 text-slate-700">Almacén (opcional)</label>
      <select v-model="selectedWarehouse" @change="updatePreview"
        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500">
        <option value="">Todos los almacenes</option>
        <option v-for="wh in warehouses" :key="wh.id" :value="wh.id">{{ wh.name }}</option>
      </select>
    </div>

    <!-- Quantity -->
    <div class="mb-4">
      <label class="block text-sm font-medium mb-2 text-slate-700">Cantidad de Etiquetas</label>
      <div class="flex items-center gap-2">
        <button @click="quantity > 1 && quantity--" class="w-10 h-10 bg-slate-100 rounded-lg hover:bg-slate-200 flex items-center justify-center">-</button>
        <input v-model.number="quantity" type="number" min="1" max="100" 
          class="w-20 text-center px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg" />
        <button @click="quantity < 100 && quantity++" class="w-10 h-10 bg-slate-100 rounded-lg hover:bg-slate-200 flex items-center justify-center">+</button>
      </div>
    </div>

    <!-- Preview -->
    <div class="mb-4">
      <label class="block text-sm font-medium mb-2 text-slate-700">Vista Previa</label>
      <div class="bg-slate-100 p-4 rounded-lg flex justify-center">
        <div v-if="previewHtml" v-html="previewHtml" class="transform scale-100 origin-center"></div>
        <div v-else class="text-slate-400 text-sm">Cargando vista previa...</div>
      </div>
    </div>

    <!-- Print Buttons -->
    <div class="flex flex-wrap gap-3">
      <button @click="printLabels" 
        class="flex-1 px-4 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        Imprimir Etiquetas
      </button>
      
      <button v-if="dymoXml" @click="printDymo" 
        class="px-4 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-all flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        DYMO
      </button>

      <button @click="downloadPDF" 
        class="px-4 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-all flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        PDF
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import apiClient from '../services/api'

const props = defineProps({
  product: { type: Object, required: true }
})

const templates = ref([])
const warehouses = ref([])
const labelSize = ref('medium')
const selectedWarehouse = ref('')
const quantity = ref(1)
const previewHtml = ref('')
const dymoXml = ref('')

async function loadTemplates() {
  try {
    const response = await apiClient.get('/labels/templates')
    templates.value = response.data.templates
  } catch (err) {
    console.error('Error loading templates:', err)
  }
}

async function loadWarehouses() {
  try {
    const response = await apiClient.get('/warehouses')
    warehouses.value = response.data
  } catch (err) {
    console.error('Error loading warehouses:', err)
  }
}

async function updatePreview() {
  try {
    const response = await apiClient.get(`/labels/preview`, {
      params: {
        product_id: props.product.id,
        label_size: labelSize.value,
      }
    })
    previewHtml.value = response.data.html
  } catch (err) {
    console.error('Error loading preview:', err)
  }
}

async function generateLabels() {
  try {
    const response = await apiClient.get(`/products/${props.product.id}/labels`, {
      params: {
        warehouse_id: selectedWarehouse.value || null,
        quantity: quantity.value,
        label_size: labelSize.value,
      }
    })
    dymoXml.value = response.data.dymo_xml
    return response.data.labels
  } catch (err) {
    console.error('Error generating labels:', err)
    return []
  }
}

async function printLabels() {
  const labels = await generateLabels()
  if (labels.length === 0) return

  const printWindow = window.open('', '_blank')
  const html = `
    <!DOCTYPE html>
    <html>
    <head>
      <title>Etiquetas - ${props.product.name}</title>
      <style>
        @media print {
          @page { margin: 0; size: auto; }
          body { margin: 0; }
          .no-print { display: none; }
        }
        body { 
          font-family: Arial, sans-serif; 
          padding: 20px;
          background: #f5f5f5;
        }
        .label {
          background: white;
          margin-bottom: 20px;
          page-break-inside: avoid;
        }
        .controls {
          margin-bottom: 20px;
          padding: 15px;
          background: white;
          border-radius: 8px;
        }
        button {
          padding: 10px 20px;
          background: #3b82f6;
          color: white;
          border: none;
          border-radius: 6px;
          cursor: pointer;
          font-size: 16px;
        }
        button:hover { background: #2563eb; }
      </style>
    </head>
    <body>
      <div class="controls no-print">
        <button onclick="window.print()">🖨️ Imprimir</button>
        <span style="margin-left: 15px; color: #666;">
          Se imprimirán ${labels.length} etiqueta(s)
        </span>
      </div>
      ${labels.map(() => previewHtml.value).join('<div style="margin: 10px 0;"></div>')}
    </body>
    </html>
  `
  printWindow.document.write(html)
  printWindow.document.close()
}

async function printDymo() {
  await generateLabels()
  if (!dymoXml.value) return

  // Check if DYMO Label Framework is available
  if (typeof dymo !== 'undefined' && dymo.label) {
    try {
      const printers = await dymo.label.getPrinters()
      if (printers.length === 0) {
        alert('No se encontró ninguna impresora DYMO')
        return
      }
      
      const label = dymo.label.openLabelXml(dymoXml.value)
      label.print(printers[0].name)
    } catch (err) {
      console.error('DYMO print error:', err)
      alert('Error al imprimir con DYMO: ' + err.message)
    }
  } else {
    // Fallback: download XML
    const blob = new Blob([dymoXml.value], { type: 'application/xml' })
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `etiqueta_${props.product.sku}.label`
    a.click()
    window.URL.revokeObjectURL(url)
    alert('Archivo DYMO descargado. Ábrelo con DYMO Label Software para imprimir.')
  }
}

async function downloadPDF() {
  const labels = await generateLabels()
  if (labels.length === 0) return

  // For now, print to PDF using browser
  const printWindow = window.open('', '_blank')
  const html = `
    <!DOCTYPE html>
    <html>
    <head>
      <title>Etiquetas PDF - ${props.product.name}</title>
      <style>
        @media print {
          @page { margin: 10mm; size: A4; }
          body { margin: 0; }
        }
        body { 
          font-family: Arial, sans-serif; 
          padding: 20px;
        }
        .label-grid {
          display: grid;
          grid-template-columns: repeat(2, 1fr);
          gap: 10px;
        }
        .label {
          border: 1px solid #ddd;
          padding: 10px;
          background: white;
        }
      </style>
    </head>
    <body>
      <div class="label-grid">
        ${Array(quantity.value).fill(previewHtml.value).join('')}
      </div>
      <script>setTimeout(() => window.print(), 500)</script>
    </body>
    </html>
  `
  printWindow.document.write(html)
  printWindow.document.close()
}

onMounted(() => {
  loadTemplates()
  loadWarehouses()
  updatePreview()
})
</script>
