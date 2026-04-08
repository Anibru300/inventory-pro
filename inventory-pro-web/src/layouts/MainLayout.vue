<template>
  <div class="min-h-screen flex bg-silver-50">
    <!-- Sidebar -->
    <aside 
      :class="[
        'fixed lg:static inset-y-0 left-0 z-50 w-64 bg-silver-900 border-r border-silver-200 transition-transform duration-300',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
      ]"
    >
      <!-- Logo -->
      <div class="h-16 flex items-center px-5 border-b border-silver-700">
        <div class="w-8 h-8 bg-electric rounded-lg flex items-center justify-center mr-3">
          <svg class="w-5 h-5 text-silver-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
        </div>
        <div>
          <h1 class="font-semibold text-base text-white tracking-tight">Inventory Pro</h1>
          <p class="text-xs text-silver-400">Sistema ERP</p>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="p-3 space-y-1">
        <router-link
          v-for="item in navigation"
          :key="item.name"
          :to="item.to"
          :class="[
            'nav-item',
            $route.path === item.to || $route.path.startsWith(item.to + '/') ? 'active' : 'text-silver-400 hover:text-silver-200'
          ]"
        >
          <component :is="item.icon" class="w-5 h-5 flex-shrink-0" />
          <span class="truncate">{{ item.name }}</span>
        </router-link>
      </nav>

      <!-- Quick Actions -->
      <div class="px-3 mt-6">
        <p class="text-xs font-medium text-silver-500 uppercase tracking-wider mb-2 px-3">Acciones Rápidas</p>
        <div class="space-y-2">
          <button @click="$router.push('/movements/new')" class="btn btn-primary btn-sm w-full justify-start">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Movimiento
          </button>
          <button @click="$router.push('/products/new')" class="btn btn-secondary btn-sm w-full justify-start border-silver-600 text-silver-300 hover:bg-silver-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Producto
          </button>
        </div>
      </div>

      <!-- User Profile -->
      <div class="absolute bottom-0 left-0 right-0 p-3 border-t border-silver-700">
        <div class="bg-silver-800/50 rounded-lg p-3">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-electric rounded-full flex items-center justify-center text-silver-900 font-semibold text-sm">
              {{ userInitials }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-white truncate">{{ authStore.user?.name }}</p>
              <p class="text-xs text-silver-400 truncate">{{ authStore.tenant?.name }}</p>
            </div>
            <button @click="handleLogout" class="p-2 text-silver-400 hover:text-semantic-error transition-colors rounded-lg hover:bg-silver-800">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </aside>

    <!-- Mobile Overlay -->
    <div v-if="sidebarOpen" class="fixed inset-0 bg-black/50 z-40 lg:hidden" @click="sidebarOpen = false"></div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Header -->
      <header class="h-16 bg-white border-b border-silver-200 flex items-center justify-between px-6 sticky top-0 z-30">
        <div class="flex items-center gap-4">
          <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-silver-600 hover:text-silver-900 hover:bg-silver-100 rounded-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          
          <!-- Breadcrumb -->
          <nav class="hidden md:flex items-center gap-2 text-sm text-silver-600">
            <span class="text-silver-900 font-medium">{{ currentPageTitle }}</span>
          </nav>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3">
          <!-- Global Search -->
          <GlobalSearch />
          
          <!-- Alert Notifications -->
          <AlertNotifications />
          
          <span class="badge" :class="planBadgeClass">
            {{ authStore.tenant?.plan?.toUpperCase() || 'STARTER' }}
          </span>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 overflow-auto p-6">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, h } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import GlobalSearch from '../components/GlobalSearch.vue'
import AlertNotifications from '../components/AlertNotifications.vue'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()
const sidebarOpen = ref(false)

// Page title based on current route
const currentPageTitle = computed(() => {
  const titles = {
    '/dashboard': 'Dashboard',
    '/products': 'Productos',
    '/products/new': 'Nuevo Producto',
    '/movements': 'Movimientos',
    '/movements/new': 'Nuevo Movimiento',
    '/transfers': 'Transferencias',
    '/transfers/new': 'Nueva Transferencia',
    '/lots': 'Lotes',
    '/lots/new': 'Nuevo Lote',
    '/warehouses': 'Almacenes',
    '/categories': 'Categorías',
    '/receipts': 'Vales',
    '/reports': 'Reportes',
    '/import': 'Importar Productos',
    '/reports/abc': 'Análisis ABC',
    '/settings': 'Configuración',
  }
  return titles[route.path] || 'Inventory Pro'
})

// Plan badge class
const planBadgeClass = computed(() => {
  const plan = authStore.tenant?.plan?.toLowerCase()
  switch (plan) {
    case 'enterprise': return 'bg-purple-100 text-purple-700 border border-purple-200'
    case 'professional': return 'bg-electric-muted text-electric-dark border border-electric'
    case 'starter': return 'bg-silver-100 text-silver-600 border border-silver-200'
    default: return 'bg-silver-100 text-silver-600 border border-silver-200'
  }
})

// Icons
const HomeIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' })] ) }
const PackageIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' })] ) }
const ArrowsIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4' })] ) }
const WarehouseIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' })] ) }
const TagIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z' })] ) }
const ChartIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' })] ) }
const SettingsIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' }), h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z' })] ) }
const ReceiptIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' })]) }
const ImportIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12' })]) }
const TransferIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4' })]) }
const LotIcon = { render: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14' })]) }

const navigation = [
  { name: 'Dashboard', to: '/dashboard', icon: HomeIcon },
  { name: 'Productos', to: '/products', icon: PackageIcon },
  { name: 'Movimientos', to: '/movements', icon: ArrowsIcon },
  { name: 'Transferencias', to: '/transfers', icon: TransferIcon },
  { name: 'Lotes', to: '/lots', icon: LotIcon },
  { name: 'Almacenes', to: '/warehouses', icon: WarehouseIcon },
  { name: 'Categorías', to: '/categories', icon: TagIcon },
  { name: 'Vales', to: '/receipts', icon: ReceiptIcon },
  { name: 'Reportes', to: '/reports', icon: ChartIcon },
  { name: 'Importar', to: '/import', icon: ImportIcon },
  { name: 'Configuración', to: '/settings', icon: SettingsIcon },
]

const userInitials = computed(() => {
  const name = authStore.user?.name || ''
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}
</script>
