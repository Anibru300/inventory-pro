<template>
  <div class="min-h-screen" :class="isDark ? 'bg-[#0B1F3A]' : 'bg-gray-50'">
    <!-- Header Profesional -->
    <div class="border-b" :class="isDark ? 'bg-[#0B1F3A]/90 border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
      <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
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
              <h1 class="text-3xl font-bold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
                Centro de Reportes
              </h1>
              <p class="mt-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
                Análisis detallado y estadísticas de inventario
              </p>
            </div>
          </div>
          
          <!-- Selector de Reporte -->
          <div class="flex flex-wrap gap-2">
            <button 
              v-for="report in reportTypes" 
              :key="report.id"
              @click="changeReport(report.id)"
              :class="[
                'px-4 py-2.5 rounded-xl font-medium text-sm transition-all flex items-center gap-2',
                currentReport === report.id 
                  ? 'bg-[#2E7DE8] text-white shadow-lg shadow-[#2E7DE8]/30' 
                  : isDark ? 'bg-[#1a3050] text-gray-300 hover:bg-[#2a4060]' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'
              ]"
            >
              <component :is="report.icon" class="w-4 h-4" />
              {{ report.name }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">
      <!-- Filtros y Acciones -->
      <div class="rounded-2xl p-5 mb-8 border"
        :class="isDark ? 'bg-[#0B1F3A]/60 border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
        <div class="flex flex-wrap items-end gap-4">
          <div>
            <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
              Fecha Desde
            </label>
            <input 
              v-model="filters.dateFrom" 
              type="date" 
              class="px-4 py-2.5 rounded-xl border text-sm focus:ring-2 focus:ring-[#2E7DE8] focus:border-transparent transition-all"
              :class="isDark ? 'bg-[#1a3050] border-gray-700 text-white' : 'bg-white border-gray-300'"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
              Fecha Hasta
            </label>
            <input 
              v-model="filters.dateTo" 
              type="date" 
              class="px-4 py-2.5 rounded-xl border text-sm focus:ring-2 focus:ring-[#2E7DE8] focus:border-transparent transition-all"
              :class="isDark ? 'bg-[#1a3050] border-gray-700 text-white' : 'bg-white border-gray-300'"
            />
          </div>
          
          <div class="flex-1"></div>
          
          <button @click="loadReport" :disabled="loading" 
            class="px-6 py-2.5 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] hover:from-[#1e6ad1] hover:to-[#2E7DE8] text-white rounded-xl font-medium transition-all shadow-lg shadow-[#2E7DE8]/30 disabled:opacity-50 flex items-center gap-2">
            <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            {{ loading ? 'Generando...' : 'Generar Reporte' }}
          </button>
          
          <button v-if="reportData" @click="showExportModal = true"
            class="px-6 py-2.5 border-2 rounded-xl font-medium transition-all flex items-center gap-2"
            :class="isDark ? 'border-[#2E7DE8] text-[#2E7DE8] hover:bg-[#2E7DE8]/10' : 'border-[#2E7DE8] text-[#2E7DE8] hover:bg-[#2E7DE8]/5'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Exportar
          </button>
        </div>
      </div>

      <!-- Reporte Generado -->
      <div v-if="reportData" class="space-y-6">
        <!-- Pestañas -->
        <div class="border-b" :class="isDark ? 'border-gray-700' : 'border-gray-200'">
          <div class="flex gap-1">
            <button 
              v-for="tab in tabs" 
              :key="tab.id"
              @click="currentTab = tab.id"
              :class="[
                'px-6 py-3 font-medium text-sm transition-all border-b-2',
                currentTab === tab.id 
                  ? 'border-[#2E7DE8] text-[#2E7DE8]' 
                  : isDark ? 'border-transparent text-gray-400 hover:text-gray-300' : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              <span class="flex items-center gap-2">
                <component :is="tab.icon" class="w-4 h-4" />
                {{ tab.name }}
              </span>
            </button>
          </div>
        </div>

        <!-- TAB: RESUMEN EJECUTIVO -->
        <div v-if="currentTab === 'summary'" class="space-y-6">
          <!-- Tarjeta de Reporte Profesional -->
          <div class="rounded-2xl overflow-hidden border"
            :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
            <!-- Encabezado de Reporte -->
            <div class="p-8 border-b" :class="isDark ? 'border-gray-700 bg-[#0B1F3A]/80' : 'border-gray-100 bg-gray-50/50'">
              <div class="flex justify-between items-start">
                <div>
                  <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#2E7DE8] to-[#1e6ad1] flex items-center justify-center">
                      <span class="text-white font-bold text-lg">CJ</span>
                    </div>
                    <div>
                      <h2 class="text-xl font-bold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
                        CJ Consultoría
                      </h2>
                      <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Inventory Pro</p>
                    </div>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-sm font-medium" :class="isDark ? 'text-gray-300' : 'text-gray-700'">{{ currentReportName }}</p>
                  <p class="text-xs mt-1" :class="isDark ? 'text-gray-500' : 'text-gray-400'">
                    Generado: {{ new Date().toLocaleDateString('es-MX', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                  </p>
                  <p class="text-xs" :class="isDark ? 'text-gray-500' : 'text-gray-400'">
                    Período: {{ formatDate(filters.dateFrom) }} - {{ formatDate(filters.dateTo) }}
                  </p>
                </div>
              </div>
            </div>

            <!-- KPIs Principales -->
            <div class="p-8">
              <h3 class="text-lg font-semibold mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Resumen Ejecutivo</h3>
              
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="(kpi, index) in currentKPIs" :key="index"
                  class="p-6 rounded-2xl border transition-all hover:shadow-lg"
                  :class="isDark ? 'bg-[#1a3050]/50 border-[#2E7DE8]/20 hover:border-[#2E7DE8]/40' : 'bg-gray-50 border-gray-200 hover:border-[#2E7DE8]/30'">
                  <div class="flex items-center justify-between mb-4">
                    <div :class="['w-12 h-12 rounded-xl flex items-center justify-center', kpi.bgColor]">
                      <component :is="kpi.icon" class="w-6 h-6" :class="kpi.iconColor" />
                    </div>
                    <span v-if="kpi.trend" :class="['text-xs font-medium px-2 py-1 rounded-full', kpi.trendColor]">
                      {{ kpi.trend }}
                    </span>
                  </div>
                  <p class="text-sm mb-1" :class="isDark ? 'text-gray-400' : 'text-gray-500'">{{ kpi.label }}</p>
                  <p class="text-2xl font-bold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">{{ kpi.value }}</p>
                  <p v-if="kpi.subtext" class="text-xs mt-1" :class="isDark ? 'text-gray-500' : 'text-gray-400'">{{ kpi.subtext }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Insights y Recomendaciones -->
          <div v-if="insights.length > 0" class="rounded-2xl p-6 border"
            :class="isDark ? 'bg-amber-500/5 border-amber-500/20' : 'bg-amber-50 border-amber-200'">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h3 class="font-semibold" :class="isDark ? 'text-amber-400' : 'text-amber-700'">Insights y Recomendaciones</h3>
            </div>
            <ul class="space-y-2">
              <li v-for="(insight, idx) in insights" :key="idx" 
                class="flex items-start gap-3 text-sm"
                :class="isDark ? 'text-gray-300' : 'text-gray-700'">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mt-2 flex-shrink-0"></span>
                {{ insight }}
              </li>
            </ul>
          </div>
        </div>

        <!-- TAB: TABLAS DETALLADAS -->
        <div v-if="currentTab === 'tables'" class="space-y-6">
          <!-- Reporte: Valoración -->
          <template v-if="currentReport === 'inventory'">
            <!-- Por Categoría -->
            <div class="rounded-2xl overflow-hidden border"
              :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
              <div class="px-6 py-4 border-b flex items-center justify-between"
                :class="isDark ? 'border-gray-700 bg-[#1a3050]/30' : 'border-gray-100 bg-gray-50'">
                <h3 class="font-semibold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Valoración por Categoría</h3>
                <span class="text-xs px-3 py-1 rounded-full" :class="isDark ? 'bg-[#2E7DE8]/20 text-[#2E7DE8]' : 'bg-blue-100 text-blue-700'">
                  {{ Object.keys(reportData.by_category || {}).length }} categorías
                </span>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr :class="isDark ? 'bg-[#1a3050]/50' : 'bg-gray-50'">
                      <th class="text-left py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Categoría</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Cantidad</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Valor al Costo</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Valor de Venta</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Margen</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">% del Total</th>
                    </tr>
                  </thead>
                  <tbody :class="isDark ? 'divide-y divide-gray-700' : 'divide-y divide-gray-100'">
                    <tr v-for="(data, category) in reportData.by_category" :key="category" 
                      class="hover:bg-[#2E7DE8]/5 transition-colors">
                      <td class="py-4 px-6">
                        <span class="font-medium" :class="isDark ? 'text-white' : 'text-gray-900'">{{ category }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span class="font-medium" :class="isDark ? 'text-gray-300' : 'text-gray-700'">{{ data.quantity.toLocaleString() }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span class="font-medium text-emerald-400">${{ formatNumber(data.cost_value) }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span class="font-medium text-blue-400">${{ formatNumber(data.price_value) }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span class="font-medium text-amber-400">${{ formatNumber(data.price_value - data.cost_value) }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <div class="flex items-center gap-2">
                          <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden" :class="isDark ? 'bg-gray-700' : 'bg-gray-200'">
                            <div class="h-full bg-[#2E7DE8] rounded-full" 
                              :style="{ width: ((data.cost_value / reportData.summary.total_cost_value) * 100) + '%' }"></div>
                          </div>
                          <span class="text-xs w-10" :class="isDark ? 'text-gray-400' : 'text-gray-500'">
                            {{ ((data.cost_value / reportData.summary.total_cost_value) * 100).toFixed(1) }}%
                          </span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot :class="isDark ? 'bg-[#1a3050]/30 border-t border-gray-700' : 'bg-gray-50 border-t border-gray-200'">
                    <tr>
                      <td class="py-4 px-6 font-bold" :class="isDark ? 'text-white' : 'text-gray-900'">TOTAL</td>
                      <td class="py-4 px-6 text-right font-bold" :class="isDark ? 'text-white' : 'text-gray-900'">
                        {{ reportData.summary.total_items.toLocaleString() }}
                      </td>
                      <td class="py-4 px-6 text-right font-bold text-emerald-400">${{ formatNumber(reportData.summary.total_cost_value) }}</td>
                      <td class="py-4 px-6 text-right font-bold text-blue-400">${{ formatNumber(reportData.summary.total_price_value) }}</td>
                      <td class="py-4 px-6 text-right font-bold text-amber-400">${{ formatNumber(reportData.summary.potential_profit) }}</td>
                      <td class="py-4 px-6 text-right font-bold" :class="isDark ? 'text-white' : 'text-gray-900'">100%</td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

            <!-- Por Almacén -->
            <div class="rounded-2xl overflow-hidden border"
              :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
              <div class="px-6 py-4 border-b flex items-center justify-between"
                :class="isDark ? 'border-gray-700 bg-[#1a3050]/30' : 'border-gray-100 bg-gray-50'">
                <h3 class="font-semibold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Valoración por Almacén</h3>
                <span class="text-xs px-3 py-1 rounded-full" :class="isDark ? 'bg-[#2E7DE8]/20 text-[#2E7DE8]' : 'bg-blue-100 text-blue-700'">
                  {{ Object.keys(reportData.by_warehouse || {}).length }} almacenes
                </span>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr :class="isDark ? 'bg-[#1a3050]/50' : 'bg-gray-50'">
                      <th class="text-left py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Almacén</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Cantidad</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Valor al Costo</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Valor de Venta</th>
                    </tr>
                  </thead>
                  <tbody :class="isDark ? 'divide-y divide-gray-700' : 'divide-y divide-gray-100'">
                    <tr v-for="(data, warehouse) in reportData.by_warehouse" :key="warehouse" 
                      class="hover:bg-[#2E7DE8]/5 transition-colors">
                      <td class="py-4 px-6">
                        <span class="font-medium" :class="isDark ? 'text-white' : 'text-gray-900'">{{ warehouse }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span class="font-medium" :class="isDark ? 'text-gray-300' : 'text-gray-700'">{{ data.quantity.toLocaleString() }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span class="font-medium text-emerald-400">${{ formatNumber(data.cost_value) }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span class="font-medium text-blue-400">${{ formatNumber(data.price_value) }}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Detalle de Productos -->
            <div class="rounded-2xl overflow-hidden border"
              :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
              <div class="px-6 py-4 border-b flex items-center justify-between"
                :class="isDark ? 'border-gray-700 bg-[#1a3050]/30' : 'border-gray-100 bg-gray-50'">
                <h3 class="font-semibold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Detalle de Productos en Inventario</h3>
                <div class="flex items-center gap-2">
                  <input 
                    v-model="productSearch" 
                    type="text" 
                    placeholder="Buscar producto..."
                    class="px-4 py-2 rounded-lg border text-sm focus:ring-2 focus:ring-[#2E7DE8] focus:border-transparent"
                    :class="isDark ? 'bg-[#1a3050] border-gray-700 text-white' : 'bg-white border-gray-300'"
                  />
                </div>
              </div>
              <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                <table class="w-full">
                  <thead class="sticky top-0">
                    <tr :class="isDark ? 'bg-[#1a3050]' : 'bg-gray-50'">
                      <th class="text-left py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Producto</th>
                      <th class="text-left py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">SKU</th>
                      <th class="text-left py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Almacén</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Stock</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Costo Unit.</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Precio Venta</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Valor Total</th>
                    </tr>
                  </thead>
                  <tbody :class="isDark ? 'divide-y divide-gray-700' : 'divide-y divide-gray-100'">
                    <tr v-for="item in filteredStockLevels" :key="item.product.id + item.warehouse" 
                      class="hover:bg-[#2E7DE8]/5 transition-colors">
                      <td class="py-4 px-6">
                        <span class="font-medium" :class="isDark ? 'text-white' : 'text-gray-900'">{{ item.product.name }}</span>
                      </td>
                      <td class="py-4 px-6">
                        <span class="font-mono text-sm" :class="isDark ? 'text-[#2E7DE8]' : 'text-blue-600'">{{ item.product.sku }}</span>
                      </td>
                      <td class="py-4 px-6">
                        <span :class="isDark ? 'text-gray-400' : 'text-gray-500'">{{ item.warehouse }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span class="font-medium" :class="isDark ? 'text-gray-300' : 'text-gray-700'">{{ item.quantity }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span :class="isDark ? 'text-gray-400' : 'text-gray-500'">${{ formatNumber(item.unit_cost) }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span :class="isDark ? 'text-gray-400' : 'text-gray-500'">${{ formatNumber(item.selling_price) }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span class="font-semibold" :class="isDark ? 'text-white' : 'text-gray-900'">${{ formatNumber(item.total_cost) }}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </template>

          <!-- Reporte: Movimientos -->
          <template v-if="currentReport === 'movements'">
            <div class="rounded-2xl overflow-hidden border"
              :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
              <div class="px-6 py-4 border-b" :class="isDark ? 'border-gray-700 bg-[#1a3050]/30' : 'border-gray-100 bg-gray-50'">
                <h3 class="font-semibold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Movimientos por Producto</h3>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr :class="isDark ? 'bg-[#1a3050]/50' : 'bg-gray-50'">
                      <th class="text-left py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Producto</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Entradas</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Salidas</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Balance</th>
                      <th class="text-center py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Eficiencia</th>
                    </tr>
                  </thead>
                  <tbody :class="isDark ? 'divide-y divide-gray-700' : 'divide-y divide-gray-100'">
                    <tr v-for="(data, product) in reportData.by_product" :key="product" 
                      class="hover:bg-[#2E7DE8]/5 transition-colors">
                      <td class="py-4 px-6">
                        <span class="font-medium" :class="isDark ? 'text-white' : 'text-gray-900'">{{ product }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span class="font-medium text-emerald-400">+{{ data.entries.toLocaleString() }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span class="font-medium text-rose-400">-{{ data.exits.toLocaleString() }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span class="font-bold" :class="data.balance >= 0 ? 'text-emerald-400' : 'text-rose-400'">
                          {{ data.balance >= 0 ? '+' : '' }}{{ data.balance.toLocaleString() }}
                        </span>
                      </td>
                      <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                          <div class="flex-1 h-2 rounded-full overflow-hidden" :class="isDark ? 'bg-gray-700' : 'bg-gray-200'">
                            <div class="h-full rounded-full transition-all"
                              :class="getEfficiencyColor(data.entries, data.exits)"
                              :style="{ width: getEfficiency(data.entries, data.exits) + '%' }"></div>
                          </div>
                          <span class="text-xs w-10" :class="isDark ? 'text-gray-400' : 'text-gray-500'">
                            {{ getEfficiency(data.entries, data.exits) }}%
                          </span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </template>

          <!-- Reporte: Stock Bajo -->
          <template v-if="currentReport === 'low-stock'">
            <!-- Stock Bajo -->
            <div class="rounded-2xl overflow-hidden border"
              :class="isDark ? 'bg-[#0B1F3A] border-amber-500/30' : 'bg-white border-amber-200'">
              <div class="px-6 py-4 border-b" :class="isDark ? 'border-amber-500/30 bg-amber-500/10' : 'border-amber-200 bg-amber-50'">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="font-semibold" :class="isDark ? 'text-amber-400' : 'text-amber-700'">Productos con Stock Bajo</h3>
                    <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-500'">{{ reportData.low_stock?.count || 0 }} productos requieren atención</p>
                  </div>
                </div>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr :class="isDark ? 'bg-[#1a3050]/50' : 'bg-gray-50'">
                      <th class="text-left py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Producto</th>
                      <th class="text-left py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">SKU</th>
                      <th class="text-left py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Categoría</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Stock Actual</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Mínimo</th>
                      <th class="text-right py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Faltante</th>
                      <th class="text-center py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Estado</th>
                    </tr>
                  </thead>
                  <tbody :class="isDark ? 'divide-y divide-gray-700' : 'divide-y divide-gray-100'">
                    <tr v-for="product in reportData.low_stock?.products" :key="product.id" 
                      class="hover:bg-amber-500/5 transition-colors">
                      <td class="py-4 px-6">
                        <span class="font-medium" :class="isDark ? 'text-white' : 'text-gray-900'">{{ product.name }}</span>
                      </td>
                      <td class="py-4 px-6">
                        <span class="font-mono text-sm" :class="isDark ? 'text-[#2E7DE8]' : 'text-blue-600'">{{ product.sku }}</span>
                      </td>
                      <td class="py-4 px-6">
                        <span :class="isDark ? 'text-gray-400' : 'text-gray-500'">{{ product.category || '-' }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span class="font-bold text-amber-400">{{ product.current_stock }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span :class="isDark ? 'text-gray-400' : 'text-gray-500'">{{ product.min_stock }}</span>
                      </td>
                      <td class="py-4 px-6 text-right">
                        <span class="font-bold text-rose-400">{{ product.needed }}</span>
                      </td>
                      <td class="py-4 px-6 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-amber-500/20 text-amber-400">
                          Crítico
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Sin Stock -->
            <div v-if="reportData.out_of_stock?.count > 0" class="rounded-2xl overflow-hidden border"
              :class="isDark ? 'bg-[#0B1F3A] border-rose-500/30' : 'bg-white border-rose-200'">
              <div class="px-6 py-4 border-b" :class="isDark ? 'border-rose-500/30 bg-rose-500/10' : 'border-rose-200 bg-rose-50'">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="font-semibold" :class="isDark ? 'text-rose-400' : 'text-rose-700'">Productos Sin Stock</h3>
                    <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-500'">{{ reportData.out_of_stock?.count || 0 }} productos agotados</p>
                  </div>
                </div>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr :class="isDark ? 'bg-[#1a3050]/50' : 'bg-gray-50'">
                      <th class="text-left py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Producto</th>
                      <th class="text-left py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">SKU</th>
                      <th class="text-left py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Categoría</th>
                      <th class="text-left py-4 px-6 text-xs font-semibold uppercase tracking-wider" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Último Movimiento</th>
                    </tr>
                  </thead>
                  <tbody :class="isDark ? 'divide-y divide-gray-700' : 'divide-y divide-gray-100'">
                    <tr v-for="product in reportData.out_of_stock?.products" :key="product.id" 
                      class="hover:bg-rose-500/5 transition-colors">
                      <td class="py-4 px-6">
                        <span class="font-medium text-rose-400">{{ product.name }}</span>
                      </td>
                      <td class="py-4 px-6">
                        <span class="font-mono text-sm" :class="isDark ? 'text-[#2E7DE8]' : 'text-blue-600'">{{ product.sku }}</span>
                      </td>
                      <td class="py-4 px-6">
                        <span :class="isDark ? 'text-gray-400' : 'text-gray-500'">{{ product.category || '-' }}</span>
                      </td>
                      <td class="py-4 px-6">
                        <span :class="isDark ? 'text-gray-400' : 'text-gray-500'">
                          {{ product.last_movement ? formatDate(product.last_movement) : 'Nunca' }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </template>

          <!-- Reporte: Top Productos -->
          <template v-if="currentReport === 'top-products'">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Más Salidas -->
              <div class="rounded-2xl overflow-hidden border"
                :class="isDark ? 'bg-[#0B1F3A] border-rose-500/30' : 'bg-white border-rose-200'">
                <div class="px-6 py-4 border-b" :class="isDark ? 'border-rose-500/30 bg-rose-500/10' : 'border-rose-200 bg-rose-50'">
                  <h3 class="font-semibold" :class="isDark ? 'text-rose-400' : 'text-rose-700'">Productos con Más Salidas</h3>
                </div>
                <div class="p-6 space-y-4">
                  <div v-for="(item, index) in reportData.top_exits" :key="index" 
                    class="flex items-center gap-4 p-4 rounded-xl" :class="isDark ? 'bg-[#1a3050]/30' : 'bg-gray-50'">
                    <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center text-rose-400 font-bold">
                      {{ index + 1 }}
                    </div>
                    <div class="flex-1">
                      <p class="font-medium" :class="isDark ? 'text-white' : 'text-gray-900'">{{ item.product_name }}</p>
                      <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-500'">{{ item.product_sku }}</p>
                    </div>
                    <div class="text-right">
                      <p class="font-bold text-rose-400">{{ item.total_quantity.toLocaleString() }}</p>
                      <p class="text-xs" :class="isDark ? 'text-gray-500' : 'text-gray-400'">unidades</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Más Entradas -->
              <div class="rounded-2xl overflow-hidden border"
                :class="isDark ? 'bg-[#0B1F3A] border-emerald-500/30' : 'bg-white border-emerald-200'">
                <div class="px-6 py-4 border-b" :class="isDark ? 'border-emerald-500/30 bg-emerald-500/10' : 'border-emerald-200 bg-emerald-50'">
                  <h3 class="font-semibold" :class="isDark ? 'text-emerald-400' : 'text-emerald-700'">Productos con Más Entradas</h3>
                </div>
                <div class="p-6 space-y-4">
                  <div v-for="(item, index) in reportData.top_entries" :key="index" 
                    class="flex items-center gap-4 p-4 rounded-xl" :class="isDark ? 'bg-[#1a3050]/30' : 'bg-gray-50'">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 font-bold">
                      {{ index + 1 }}
                    </div>
                    <div class="flex-1">
                      <p class="font-medium" :class="isDark ? 'text-white' : 'text-gray-900'">{{ item.product_name }}</p>
                      <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-500'">{{ item.product_sku }}</p>
                    </div>
                    <div class="text-right">
                      <p class="font-bold text-emerald-400">{{ item.total_quantity.toLocaleString() }}</p>
                      <p class="text-xs" :class="isDark ? 'text-gray-500' : 'text-gray-400'">unidades</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </div>

        <!-- TAB: GRÁFICAS -->
        <div v-if="currentTab === 'charts'" class="space-y-6">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Gráfica según el tipo de reporte -->
            <template v-if="currentReport === 'inventory'">
              <!-- Valor por Categoría -->
              <div class="rounded-2xl p-6 border lg:col-span-2"
                :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
                <h3 class="font-semibold mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Valor del Inventario por Categoría</h3>
                <div class="h-80">
                  <Bar :data="inventoryByCategoryChart" :options="chartOptions" />
                </div>
              </div>

              <!-- Distribución por Almacén -->
              <div class="rounded-2xl p-6 border"
                :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
                <h3 class="font-semibold mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Distribución por Almacén</h3>
                <div class="h-64">
                  <Doughnut :data="inventoryByWarehouseChart" :options="chartOptions" />
                </div>
              </div>

              <!-- Comparativo Costo vs Venta -->
              <div class="rounded-2xl p-6 border"
                :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
                <h3 class="font-semibold mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Costo vs Valor de Venta</h3>
                <div class="h-64">
                  <Bar :data="costVsPriceChart" :options="chartOptions" />
                </div>
              </div>
            </template>

            <template v-if="currentReport === 'movements'">
              <!-- Balance de Movimientos -->
              <div class="rounded-2xl p-6 border"
                :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
                <h3 class="font-semibold mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Entradas vs Salidas</h3>
                <div class="h-64">
                  <Bar :data="movementsChart" :options="chartOptions" />
                </div>
              </div>

              <!-- Distribución -->
              <div class="rounded-2xl p-6 border"
                :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
                <h3 class="font-semibold mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Distribución de Movimientos</h3>
                <div class="h-64">
                  <Pie :data="movementsPieChart" :options="chartOptions" />
                </div>
              </div>
            </template>

            <template v-if="currentReport === 'low-stock'">
              <!-- Estado del Stock -->
              <div class="rounded-2xl p-6 border lg:col-span-2"
                :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
                <h3 class="font-semibold mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Estado del Inventario</h3>
                <div class="h-64">
                  <Doughnut :data="stockStatusChart" :options="chartOptions" />
                </div>
              </div>
            </template>

            <template v-if="currentReport === 'top-products'">
              <!-- Top Productos -->
              <div class="rounded-2xl p-6 border lg:col-span-2"
                :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
                <h3 class="font-semibold mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Top 10 Productos por Movimiento</h3>
                <div class="h-80">
                  <Bar :data="topProductsChart" :options="horizontalChartOptions" />
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>

      <!-- Estado Vacío -->
      <div v-if="!loading && !reportData && !error" class="rounded-2xl p-16 text-center border"
        :class="isDark ? 'bg-[#0B1F3A]/60 border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
        <div class="w-24 h-24 rounded-full mx-auto mb-6 flex items-center justify-center"
          :class="isDark ? 'bg-[#2E7DE8]/10' : 'bg-blue-50'">
          <svg class="w-12 h-12" :class="isDark ? 'text-[#2E7DE8]' : 'text-blue-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold mb-2" :class="isDark ? 'text-white' : 'text-gray-900'">Genera tu primer reporte</h3>
        <p class="mb-6 max-w-md mx-auto" :class="isDark ? 'text-gray-400' : 'text-gray-500'">
          Selecciona el tipo de reporte, el rango de fechas y haz clic en "Generar Reporte" para visualizar el análisis.
        </p>
        <button @click="loadReport" 
          class="px-8 py-3 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] hover:from-[#1e6ad1] hover:to-[#2E7DE8] text-white rounded-xl font-medium transition-all shadow-lg shadow-[#2E7DE8]/30">
          Generar Reporte
        </button>
      </div>

      <!-- Error -->
      <div v-if="error" class="rounded-2xl p-6 border border-rose-500/30 bg-rose-500/5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-rose-500/20 flex items-center justify-center">
            <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <h3 class="font-semibold text-rose-400">Error al generar el reporte</h3>
            <p class="text-sm text-rose-300/70">{{ error }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Exportación -->
    <div v-if="showExportModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
      <div class="rounded-2xl p-6 max-w-md w-full shadow-2xl"
        :class="isDark ? 'bg-[#0B1F3A] border border-[#2E7DE8]/20' : 'bg-white'">
        <h3 class="text-xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-gray-900'">Exportar Reporte</h3>
        <p class="text-sm mb-6" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
          Selecciona el formato en el que deseas exportar el reporte.
        </p>
        
        <div class="space-y-3">
          <button @click="exportToPDF" class="w-full p-4 rounded-xl border flex items-center gap-4 transition-all hover:shadow-lg"
            :class="isDark ? 'border-rose-500/30 hover:bg-rose-500/5 bg-[#1a3050]/50' : 'border-rose-200 hover:bg-rose-50'">
            <div class="w-12 h-12 rounded-xl bg-rose-500/20 flex items-center justify-center">
              <svg class="w-6 h-6 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                <path fill="white" d="M14 3v5h5M16.5 15.5l-2.5-2.5-2.5 2.5M14 12.5v6"/>
              </svg>
            </div>
            <div class="text-left">
              <p class="font-semibold" :class="isDark ? 'text-white' : 'text-gray-900'">Exportar como PDF</p>
              <p class="text-xs" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Formato profesional para imprimir</p>
            </div>
          </button>
          
          <button @click="exportToExcel" class="w-full p-4 rounded-xl border flex items-center gap-4 transition-all hover:shadow-lg"
            :class="isDark ? 'border-emerald-500/30 hover:bg-emerald-500/5 bg-[#1a3050]/50' : 'border-emerald-200 hover:bg-emerald-50'">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center">
              <svg class="w-6 h-6 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                <path fill="white" d="M8 12h8v2H8zm0 4h8v2H8zm0-8h3v2H8z"/>
              </svg>
            </div>
            <div class="text-left">
              <p class="font-semibold" :class="isDark ? 'text-white' : 'text-gray-900'">Exportar como Excel</p>
              <p class="text-xs" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Para análisis en hoja de cálculo</p>
            </div>
          </button>
          
          <button @click="exportToCSV" class="w-full p-4 rounded-xl border flex items-center gap-4 transition-all hover:shadow-lg"
            :class="isDark ? 'border-blue-500/30 hover:bg-blue-500/5 bg-[#1a3050]/50' : 'border-blue-200 hover:bg-blue-50'">
            <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
            </div>
            <div class="text-left">
              <p class="font-semibold" :class="isDark ? 'text-white' : 'text-gray-900'">Exportar como CSV</p>
              <p class="text-xs" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Datos separados por comas</p>
            </div>
          </button>
        </div>
        
        <button @click="showExportModal = false" 
          class="w-full mt-4 py-3 border rounded-xl font-medium transition-all"
          :class="isDark ? 'border-gray-700 text-gray-400 hover:bg-gray-800' : 'border-gray-300 text-gray-600 hover:bg-gray-50'">
          Cancelar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, h, computed, watch } from 'vue'
import { useDarkMode } from '../../composables/useDarkMode'
import { Bar, Doughnut, Pie } from 'vue-chartjs'
import apiClient from '../../services/api'

const { isDark } = useDarkMode()

const currentReport = ref('inventory')
const currentTab = ref('summary')
const loading = ref(false)
const reportData = ref(null)
const error = ref(null)
const showExportModal = ref(false)
const productSearch = ref('')

const filters = ref({
  dateFrom: new Date(new Date().setDate(1)).toISOString().split('T')[0],
  dateTo: new Date().toISOString().split('T')[0],
})

// Tabs
const tabs = [
  { id: 'summary', name: 'Resumen Ejecutivo', icon: { render: () => h('svg', { class: 'w-4 h-4', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' })]) } },
  { id: 'tables', name: 'Tablas Detalladas', icon: { render: () => h('svg', { class: 'w-4 h-4', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M3 10h18M3 14h18m-9-4v8m-7-4h14M4 6h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z' })]) } },
  { id: 'charts', name: 'Gráficas', icon: { render: () => h('svg', { class: 'w-4 h-4', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z' }), h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z' })]) } },
]

// Icons
const InventoryIcon = { render: () => h('svg', { class: 'w-4 h-4', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' })]) }
const MovementsIcon = { render: () => h('svg', { class: 'w-4 h-4', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4' })]) }
const AlertIcon = { render: () => h('svg', { class: 'w-4 h-4', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' })]) }
const TopIcon = { render: () => h('svg', { class: 'w-4 h-4', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' })]) }

const reportTypes = [
  { id: 'inventory', name: 'Valoración', description: 'Valor del inventario actual', icon: InventoryIcon },
  { id: 'movements', name: 'Movimientos', description: 'Entradas y salidas', icon: MovementsIcon },
  { id: 'low-stock', name: 'Stock Bajo', description: 'Alertas de inventario', icon: AlertIcon },
  { id: 'top-products', name: 'Top Productos', description: 'Más movidos', icon: TopIcon },
]

const currentReportName = computed(() => {
  return reportTypes.find(r => r.id === currentReport.value)?.name || ''
})

const endpoints = {
  'inventory': '/reports/inventory-valuation',
  'movements': '/reports/movements',
  'low-stock': '/reports/low-stock',
  'top-products': '/reports/top-products',
}

// KPIs dinámicos según el reporte
const currentKPIs = computed(() => {
  if (!reportData.value) return []
  
  switch (currentReport.value) {
    case 'inventory':
      return [
        { 
          label: 'Total Productos', 
          value: reportData.value.summary?.total_products?.toLocaleString() || '0', 
          subtext: `${reportData.value.summary?.total_items?.toLocaleString() || 0} unidades`,
          icon: { render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' })]) },
          bgColor: 'bg-blue-500/20',
          iconColor: 'text-blue-400'
        },
        { 
          label: 'Valor al Costo', 
          value: `$${formatNumber(reportData.value.summary?.total_cost_value)}`,
          icon: { render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' })]) },
          bgColor: 'bg-emerald-500/20',
          iconColor: 'text-emerald-400'
        },
        { 
          label: 'Valor de Venta', 
          value: `$${formatNumber(reportData.value.summary?.total_price_value)}`,
          icon: { render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z' })]) },
          bgColor: 'bg-blue-500/20',
          iconColor: 'text-blue-400'
        },
        { 
          label: 'Ganancia Potencial', 
          value: `$${formatNumber(reportData.value.summary?.potential_profit)}`,
          icon: { render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' })]) },
          bgColor: 'bg-amber-500/20',
          iconColor: 'text-amber-400'
        },
      ]
    case 'movements':
      return [
        { 
          label: 'Total Entradas', 
          value: reportData.value.summary?.entry_units?.toLocaleString() || '0',
          subtext: `${reportData.value.summary?.total_entries || 0} movimientos`,
          icon: { render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M7 16l-4-4m0 0l4-4m-4 4h18' })]) },
          bgColor: 'bg-emerald-500/20',
          iconColor: 'text-emerald-400'
        },
        { 
          label: 'Total Salidas', 
          value: reportData.value.summary?.exit_units?.toLocaleString() || '0',
          subtext: `${reportData.value.summary?.total_exits || 0} movimientos`,
          icon: { render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M17 8l4 4m0 0l-4 4m4-4H3' })]) },
          bgColor: 'bg-rose-500/20',
          iconColor: 'text-rose-400'
        },
        { 
          label: 'Balance Neto', 
          value: `${reportData.value.summary?.balance >= 0 ? '+' : ''}${reportData.value.summary?.balance?.toLocaleString() || 0}`,
          icon: { render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' })]) },
          bgColor: 'bg-blue-500/20',
          iconColor: 'text-blue-400'
        },
        { 
          label: 'Eficiencia', 
          value: `${calculateEfficiency(reportData.value)}%`,
          icon: { render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M13 10V3L4 14h7v7l9-11h-7z' })]) },
          bgColor: 'bg-amber-500/20',
          iconColor: 'text-amber-400'
        },
      ]
    case 'low-stock':
      return [
        { 
          label: 'Stock Bajo', 
          value: reportData.value.low_stock?.count?.toString() || '0',
          icon: { render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' })]) },
          bgColor: 'bg-amber-500/20',
          iconColor: 'text-amber-400'
        },
        { 
          label: 'Sin Stock', 
          value: reportData.value.out_of_stock?.count?.toString() || '0',
          icon: { render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' })]) },
          bgColor: 'bg-rose-500/20',
          iconColor: 'text-rose-400'
        },
        { 
          label: 'Total Críticos', 
          value: ((reportData.value.low_stock?.count || 0) + (reportData.value.out_of_stock?.count || 0)).toString(),
          icon: { render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' })]) },
          bgColor: 'bg-orange-500/20',
          iconColor: 'text-orange-400'
        },
      ]
    case 'top-products':
      return [
        { 
          label: 'Producto Más Vendido', 
          value: reportData.value.top_exits?.[0]?.product_name?.substring(0, 15) + '...' || 'N/A',
          subtext: `${reportData.value.top_exits?.[0]?.total_quantity?.toLocaleString() || 0} unidades`,
          icon: { render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z' })]) },
          bgColor: 'bg-yellow-500/20',
          iconColor: 'text-yellow-400'
        },
        { 
          label: 'Total Salidas Top 10', 
          value: reportData.value.top_exits?.reduce((a, b) => a + (b.total_quantity || 0), 0).toLocaleString() || '0',
          icon: { render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M17 8l4 4m0 0l-4 4m4-4H3' })]) },
          bgColor: 'bg-rose-500/20',
          iconColor: 'text-rose-400'
        },
        { 
          label: 'Total Entradas Top 10', 
          value: reportData.value.top_entries?.reduce((a, b) => a + (b.total_quantity || 0), 0).toLocaleString() || '0',
          icon: { render: () => h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M7 16l-4-4m0 0l4-4m-4 4h18' })]) },
          bgColor: 'bg-emerald-500/20',
          iconColor: 'text-emerald-400'
        },
      ]
    default:
      return []
  }
})

// Insights dinámicos
const insights = computed(() => {
  if (!reportData.value) return []
  const list = []
  
  switch (currentReport.value) {
    case 'inventory':
      const profit = reportData.value.summary?.potential_profit || 0
      const cost = reportData.value.summary?.total_cost_value || 1
      const margin = ((profit / cost) * 100).toFixed(1)
      list.push(`El margen de ganancia potencial es del ${margin}% sobre el costo total del inventario.`)
      
      const categories = Object.keys(reportData.value.by_category || {}).length
      list.push(`Tu inventario está distribuido en ${categories} categorías diferentes.`)
      break
    case 'movements':
      const balance = reportData.value.summary?.balance || 0
      if (balance > 0) {
        list.push(`El balance es positivo (+${balance} unidades), lo que indica más entradas que salidas.`)
      } else if (balance < 0) {
        list.push(`El balance es negativo (${balance} unidades), considera reabastecer el inventario.`)
      }
      break
    case 'low-stock':
      const critical = (reportData.value.low_stock?.count || 0) + (reportData.value.out_of_stock?.count || 0)
      if (critical > 0) {
        list.push(`Tienes ${critical} productos que requieren atención inmediata para reabastecimiento.`)
      }
      break
  }
  
  return list
})

// Chart Data
const chartOptions = computed(() => ({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        color: isDark.value ? '#94a3b8' : '#475569',
        padding: 15,
        font: { size: 12 }
      }
    }
  },
  scales: isDark.value ? {
    x: { ticks: { color: '#94a3b8' }, grid: { color: '#1e293b' } },
    y: { ticks: { color: '#94a3b8' }, grid: { color: '#1e293b' } }
  } : {}
}))

const horizontalChartOptions = computed(() => ({
  ...chartOptions.value,
  indexAxis: 'y',
  scales: isDark.value ? {
    x: { ticks: { color: '#94a3b8' }, grid: { color: '#1e293b' } },
    y: { ticks: { color: '#94a3b8' }, grid: { display: false } }
  } : { x: {}, y: { grid: { display: false } } }
}))

// Chart Data Computed
const inventoryByCategoryChart = computed(() => {
  if (!reportData.value?.by_category) return { labels: [], datasets: [] }
  const categories = Object.keys(reportData.value.by_category)
  const values = Object.values(reportData.value.by_category).map(v => v.cost_value)
  return {
    labels: categories,
    datasets: [{
      label: 'Valor al Costo',
      data: values,
      backgroundColor: ['#2E7DE8', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'],
      borderRadius: 8
    }]
  }
})

const inventoryByWarehouseChart = computed(() => {
  if (!reportData.value?.by_warehouse) return { labels: [], datasets: [] }
  const warehouses = Object.keys(reportData.value.by_warehouse)
  const values = Object.values(reportData.value.by_warehouse).map(v => v.cost_value)
  return {
    labels: warehouses,
    datasets: [{
      data: values,
      backgroundColor: ['#2E7DE8', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
      borderWidth: 0
    }]
  }
})

const costVsPriceChart = computed(() => {
  if (!reportData.value?.by_category) return { labels: [], datasets: [] }
  const categories = Object.keys(reportData.value.by_category)
  const costs = Object.values(reportData.value.by_category).map(v => v.cost_value)
  const prices = Object.values(reportData.value.by_category).map(v => v.price_value)
  return {
    labels: categories,
    datasets: [
      { label: 'Costo', data: costs, backgroundColor: '#10b981', borderRadius: 4 },
      { label: 'Venta', data: prices, backgroundColor: '#2E7DE8', borderRadius: 4 }
    ]
  }
})

const movementsChart = computed(() => {
  if (!reportData.value?.by_product) return { labels: [], datasets: [] }
  const products = Object.keys(reportData.value.by_product).slice(0, 10)
  const entries = products.map(p => reportData.value.by_product[p].entries)
  const exits = products.map(p => reportData.value.by_product[p].exits)
  return {
    labels: products,
    datasets: [
      { label: 'Entradas', data: entries, backgroundColor: '#10b981', borderRadius: 4 },
      { label: 'Salidas', data: exits, backgroundColor: '#ef4444', borderRadius: 4 }
    ]
  }
})

const movementsPieChart = computed(() => ({
  labels: ['Entradas', 'Salidas'],
  datasets: [{
    data: [
      reportData.value?.summary?.entry_units || 0,
      reportData.value?.summary?.exit_units || 0
    ],
    backgroundColor: ['#10b981', '#ef4444'],
    borderWidth: 0
  }]
}))

const stockStatusChart = computed(() => ({
  labels: ['Stock Normal', 'Stock Bajo', 'Sin Stock'],
  datasets: [{
    data: [
      (reportData.value?.summary?.total_products || 0) - (reportData.value?.low_stock?.count || 0) - (reportData.value?.out_of_stock?.count || 0),
      reportData.value?.low_stock?.count || 0,
      reportData.value?.out_of_stock?.count || 0
    ],
    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
    borderWidth: 0
  }]
}))

const topProductsChart = computed(() => {
  if (!reportData.value?.top_exits) return { labels: [], datasets: [] }
  const products = reportData.value.top_exits.slice(0, 10).map(p => p.product_name.substring(0, 20))
  const values = reportData.value.top_exits.slice(0, 10).map(p => p.total_quantity)
  return {
    labels: products,
    datasets: [{
      label: 'Unidades vendidas',
      data: values,
      backgroundColor: '#ef4444',
      borderRadius: 4
    }]
  }
})

const filteredStockLevels = computed(() => {
  if (!reportData.value?.stock_levels) return []
  if (!productSearch.value) return reportData.value.stock_levels
  return reportData.value.stock_levels.filter(item => 
    item.product.name.toLowerCase().includes(productSearch.value.toLowerCase()) ||
    item.product.sku.toLowerCase().includes(productSearch.value.toLowerCase())
  )
})

// Methods
function formatNumber(num) {
  if (num === null || num === undefined) return '0.00'
  return num.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

function formatDate(date) {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('es-MX')
}

function changeReport(reportId) {
  currentReport.value = reportId
  reportData.value = null
  currentTab.value = 'summary'
}

function getEfficiency(entries, exits) {
  if (!entries && !exits) return 0
  const total = entries + exits
  if (!total) return 0
  return Math.round((Math.min(entries, exits) / total) * 100)
}

function getEfficiencyColor(entries, exits) {
  const eff = getEfficiency(entries, exits)
  if (eff >= 80) return 'bg-emerald-500'
  if (eff >= 50) return 'bg-amber-500'
  return 'bg-rose-500'
}

function calculateEfficiency(data) {
  const entries = data.summary?.entry_units || 0
  const exits = data.summary?.exit_units || 0
  if (!entries && !exits) return 0
  const total = entries + exits
  if (!total) return 0
  return Math.round((Math.min(entries, exits) / total) * 100)
}

async function loadReport() {
  loading.value = true
  error.value = null
  reportData.value = null

  try {
    const params = {}
    if (filters.value.dateFrom) params.date_from = filters.value.dateFrom
    if (filters.value.dateTo) params.date_to = filters.value.dateTo

    const response = await apiClient.get(endpoints[currentReport.value], { params })
    reportData.value = response.data
  } catch (err) {
    error.value = err.response?.data?.message || 'Error al cargar el reporte'
    console.error('Error loading report:', err)
  } finally {
    loading.value = false
  }
}

// Export functions using backend API
async function exportToCSV() {
  try {
    const params = {
      type: currentReport.value,
      date_from: filters.value.dateFrom,
      date_to: filters.value.dateTo,
    }
    
    const response = await apiClient.get('/reports/export/csv', { 
      params,
      responseType: 'blob'
    })
    
    const blob = new Blob([response.data], { type: 'text/csv' })
    const url = URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.download = `reporte_${currentReport.value}_${new Date().toISOString().split('T')[0]}.csv`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    URL.revokeObjectURL(url)
    showExportModal.value = false
  } catch (err) {
    alert('Error al exportar: ' + (err.response?.data?.message || err.message))
  }
}

async function exportToExcel() {
  try {
    const params = {
      type: currentReport.value,
      date_from: filters.value.dateFrom,
      date_to: filters.value.dateTo,
    }
    
    const response = await apiClient.get('/reports/export/csv', { 
      params,
      responseType: 'blob'
    })
    
    const blob = new Blob([response.data], { type: 'application/vnd.ms-excel' })
    const url = URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.download = `reporte_${currentReport.value}_${new Date().toISOString().split('T')[0]}.xls`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    URL.revokeObjectURL(url)
    showExportModal.value = false
  } catch (err) {
    alert('Error al exportar: ' + (err.response?.data?.message || err.message))
  }
}

async function exportToPDF() {
  try {
    const params = {
      type: currentReport.value,
      date_from: filters.value.dateFrom,
      date_to: filters.value.dateTo,
    }
    
    const response = await apiClient.get('/reports/export/pdf', { 
      params,
      responseType: 'blob'
    })
    
    const blob = new Blob([response.data], { type: 'text/html' })
    const url = URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.download = `reporte_${currentReport.value}_${new Date().toISOString().split('T')[0]}.html`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    URL.revokeObjectURL(url)
    showExportModal.value = false
  } catch (err) {
    alert('Error al exportar: ' + (err.response?.data?.message || err.message))
  }
}

// Load initial report
loadReport()
</script>

<style scoped>
@media print {
  .no-print {
    display: none !important;
  }
}
</style>
