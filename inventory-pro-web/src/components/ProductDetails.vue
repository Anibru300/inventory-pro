<template>
  <div class="space-y-6">
    <!-- Basic Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h4 class="text-sm font-medium text-slate-500 uppercase mb-1">Nombre</h4>
        <p class="text-lg font-semibold text-slate-800">{{ product.name }}</p>
      </div>
      <div>
        <h4 class="text-sm font-medium text-slate-500 uppercase mb-1">SKU</h4>
        <p class="font-mono text-slate-800">{{ product.sku }}</p>
      </div>
      <div>
        <h4 class="text-sm font-medium text-slate-500 uppercase mb-1">Categoría</h4>
        <p class="text-slate-800">{{ product.category?.name || 'N/A' }}</p>
      </div>
      <div>
        <h4 class="text-sm font-medium text-slate-500 uppercase mb-1">Estado</h4>
        <span :class="['px-3 py-1 rounded-full text-xs font-medium',
          product.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600']">
          {{ product.is_active ? 'Activo' : 'Inactivo' }}
        </span>
      </div>
    </div>

    <!-- Pricing -->
    <div class="border-t border-slate-100 pt-4">
      <h4 class="font-medium text-slate-800 mb-3">Precios</h4>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-slate-50 rounded-lg p-3">
          <p class="text-xs text-slate-500">Costo Unitario</p>
          <p class="font-semibold text-slate-800">${{ formatNumber(product.unit_cost) }}</p>
        </div>
        <div class="bg-slate-50 rounded-lg p-3">
          <p class="text-xs text-slate-500">Precio Venta</p>
          <p class="font-semibold text-emerald-600">${{ formatNumber(product.selling_price) }}</p>
        </div>
        <div class="bg-slate-50 rounded-lg p-3">
          <p class="text-xs text-slate-500">Margen</p>
          <p class="font-semibold text-blue-600">{{ calculateMargin }}%</p>
        </div>
        <div class="bg-slate-50 rounded-lg p-3">
          <p class="text-xs text-slate-500">Valoración</p>
          <p class="font-semibold text-slate-800">{{ product.valuation_method }}</p>
        </div>
      </div>
    </div>

    <!-- Stock -->
    <div class="border-t border-slate-100 pt-4">
      <h4 class="font-medium text-slate-800 mb-3">Inventario</h4>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-emerald-50 rounded-lg p-3">
          <p class="text-xs text-emerald-600">Stock Total</p>
          <p class="font-semibold text-emerald-800">{{ product.total_stock }} und</p>
        </div>
        <div class="bg-blue-50 rounded-lg p-3">
          <p class="text-xs text-blue-600">Disponible</p>
          <p class="font-semibold text-blue-800">{{ product.available_stock || product.total_stock }} und</p>
        </div>
        <div class="bg-amber-50 rounded-lg p-3">
          <p class="text-xs text-amber-600">Stock Mínimo</p>
          <p class="font-semibold text-amber-800">{{ product.stock_min }} und</p>
        </div>
        <div class="bg-purple-50 rounded-lg p-3">
          <p class="text-xs text-purple-600">Punto Reorden</p>
          <p class="font-semibold text-purple-800">{{ product.reorder_point }} und</p>
        </div>
      </div>
    </div>

    <!-- Description -->
    <div v-if="product.description" class="border-t border-slate-100 pt-4">
      <h4 class="font-medium text-slate-800 mb-2">Descripción</h4>
      <p class="text-slate-600">{{ product.description }}</p>
    </div>

    <!-- Attributes -->
    <div v-if="product.attributes" class="border-t border-slate-100 pt-4">
      <h4 class="font-medium text-slate-800 mb-3">Atributos</h4>
      <div class="flex flex-wrap gap-2">
        <span v-for="(value, key) in product.attributes" :key="key" 
          class="px-3 py-1 bg-slate-100 rounded-lg text-sm text-slate-600">
          {{ key }}: {{ value }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  product: { type: Object, required: true }
})

const calculateMargin = computed(() => {
  const cost = parseFloat(props.product.unit_cost) || 0
  const price = parseFloat(props.product.selling_price) || 0
  if (cost === 0) return 0
  return (((price - cost) / price) * 100).toFixed(1)
})

function formatNumber(num) {
  if (!num) return '0.00'
  return parseFloat(num).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}
</script>
