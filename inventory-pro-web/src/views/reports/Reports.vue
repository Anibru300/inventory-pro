<template>
  <div>
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold gradient-text font-heading mb-2">Reportes</h1>
      <p class="text-cj-silver-dark font-tagline italic">Análisis y estadísticas de tu inventario</p>
    </div>

    <!-- Report Types -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <!-- Inventory Valuation -->
      <div class="card-premium p-6">
        <div class="w-14 h-14 bg-cj-gold/10 rounded-xl flex items-center justify-center mb-4 border border-cj-gold/20">
          <svg class="w-7 h-7 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="text-lg font-semibold font-heading mb-2">Valoración de Inventario</h3>
        <p class="text-sm text-cj-silver-dark mb-4">Valor total del inventario por categoría y almacén</p>
        <button class="btn-secondary w-full text-sm">Generar Reporte</button>
      </div>

      <!-- Stock Movement -->
      <div class="card-premium p-6">
        <div class="w-14 h-14 bg-success/10 rounded-xl flex items-center justify-center mb-4 border border-success/20">
          <svg class="w-7 h-7 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
          </svg>
        </div>
        <h3 class="text-lg font-semibold font-heading mb-2">Movimientos de Stock</h3>
        <p class="text-sm text-cj-silver-dark mb-4">Entradas y salidas en un período determinado</p>
        <button class="btn-secondary w-full text-sm">Generar Reporte</button>
      </div>

      <!-- Low Stock -->
      <div class="card-premium p-6">
        <div class="w-14 h-14 bg-warning/10 rounded-xl flex items-center justify-center mb-4 border border-warning/20">
          <svg class="w-7 h-7 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <h3 class="text-lg font-semibold font-heading mb-2">Stock Bajo</h3>
        <p class="text-sm text-cj-silver-dark mb-4">Productos por debajo del nivel mínimo</p>
        <button class="btn-secondary w-full text-sm">Generar Reporte</button>
      </div>

      <!-- Best Sellers -->
      <div class="card-premium p-6">
        <div class="w-14 h-14 bg-cj-blue/20 rounded-xl flex items-center justify-center mb-4 border border-cj-blue/30">
          <svg class="w-7 h-7 text-cj-blue-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
          </svg>
        </div>
        <h3 class="text-lg font-semibold font-heading mb-2">Más Vendidos</h3>
        <p class="text-sm text-cj-silver-dark mb-4">Productos con mayor rotación de salidas</p>
        <button class="btn-secondary w-full text-sm">Generar Reporte</button>
      </div>

      <!-- Kardex -->
      <div class="card-premium p-6">
        <div class="w-14 h-14 bg-info/10 rounded-xl flex items-center justify-center mb-4 border border-info/20">
          <svg class="w-7 h-7 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <h3 class="text-lg font-semibold font-heading mb-2">Kardex</h3>
        <p class="text-sm text-cj-silver-dark mb-4">Historial detallado de un producto específico</p>
        <button class="btn-secondary w-full text-sm">Generar Reporte</button>
      </div>

      <!-- Inventory Comparison -->
      <div class="card-premium p-6">
        <div class="w-14 h-14 bg-cj-silver/10 rounded-xl flex items-center justify-center mb-4 border border-cj-silver/20">
          <svg class="w-7 h-7 text-cj-silver" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </div>
        <h3 class="text-lg font-semibold font-heading mb-2">Comparativo</h3>
        <p class="text-sm text-cj-silver-dark mb-4">Comparación entre períodos o almacenes</p>
        <button class="btn-secondary w-full text-sm">Generar Reporte</button>
      </div>
    </div>

    <!-- Export Options -->
    <div class="card-premium p-6">
      <h3 class="text-lg font-semibold mb-4 font-heading flex items-center gap-2">
        <span class="w-1 h-5 bg-cj-gold rounded-full"></span>
        Exportar Datos
      </h3>
      <div class="flex flex-wrap gap-4">
        <button class="btn-secondary">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Exportar a Excel
        </button>
        <button class="btn-secondary">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
          Exportar a PDF
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
// Reports page - placeholder for future implementation
</script>
