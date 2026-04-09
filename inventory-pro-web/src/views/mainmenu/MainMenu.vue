<template>
  <div class="min-h-screen p-6 transition-colors duration-300"
    :class="isDark ? 'bg-slate-900' : 'bg-slate-50'">
    
    <!-- Header -->
    <div class="max-w-7xl mx-auto mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h1 class="text-3xl md:text-4xl font-bold mb-2 transition-colors"
            :class="isDark ? 'text-white' : 'text-slate-800'">
            Bienvenido, {{ authStore.user?.name }}
          </h1>
          <p class="transition-colors"
            :class="isDark ? 'text-slate-400' : 'text-slate-500'">
            {{ authStore.tenant?.name }} • 
            <span :class="['px-2 py-1 rounded-full text-xs font-medium', roleBadgeClass]">
              {{ roleLabel }}
            </span>
          </p>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center gap-3">
          <!-- Dashboard Button -->
          <button @click="$router.push('/dashboard')" 
            class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-sm border transition-all font-medium"
            :class="isDark 
              ? 'bg-slate-800 border-slate-600 text-slate-200 hover:bg-slate-700' 
              : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="hidden md:block">Dashboard</span>
          </button>

          <!-- Dark Mode Toggle -->
          <button @click="toggleDarkMode" 
            class="p-3 rounded-xl shadow-sm border transition-all"
            :class="isDark 
              ? 'bg-slate-800 border-slate-600 text-amber-400 hover:bg-slate-700' 
              : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'"
            :title="isDark ? 'Modo Claro' : 'Modo Oscuro'">
            <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
          </button>

          <!-- Fullscreen Toggle -->
          <button @click="toggleFullscreen"
            class="p-3 rounded-xl shadow-sm border transition-all"
            :class="isDark 
              ? 'bg-slate-800 border-slate-600 text-slate-300 hover:bg-slate-700' 
              : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'"
            :title="isFullscreen ? 'Salir de pantalla completa' : 'Pantalla completa'">
            <svg v-if="!isFullscreen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <!-- User Menu -->
          <div class="relative">
            <button @click="showUserMenu = !showUserMenu" 
              class="flex items-center gap-3 p-2 pr-4 rounded-xl shadow-sm border transition-all"
              :class="isDark 
                ? 'bg-slate-800 border-slate-600 hover:bg-slate-700' 
                : 'bg-white border-slate-200 hover:bg-slate-50'">
              <div class="w-10 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold overflow-hidden">
                <img v-if="authStore.user?.avatar" :src="authStore.user.avatar" class="w-full h-full object-cover" />
                <span v-else>{{ userInitials }}</span>
              </div>
              <span class="hidden md:block font-medium" :class="isDark ? 'text-slate-200' : 'text-slate-700'">Mi Cuenta</span>
              <svg class="w-4 h-4" :class="isDark ? 'text-slate-400' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- Dropdown -->
            <div v-if="showUserMenu" 
              class="absolute right-0 mt-2 w-80 rounded-2xl shadow-xl border z-50 overflow-hidden"
              :class="isDark ? 'bg-slate-800 border-slate-600' : 'bg-white border-slate-200'">
              
              <!-- User Info with Photo Upload -->
              <div class="p-4 border-b" :class="isDark ? 'border-slate-700' : 'border-slate-100'">
                <div class="flex items-center gap-4">
                  <div class="relative">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-xl font-bold overflow-hidden"
                      :class="!authStore.user?.avatar ? 'bg-blue-600' : ''">
                      <img v-if="authStore.user?.avatar" :src="authStore.user.avatar" class="w-full h-full object-cover" />
                      <span v-else>{{ userInitials }}</span>
                    </div>
                    <button @click="triggerFileInput" 
                      class="absolute -bottom-1 -right-1 w-6 h-6 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center text-white shadow-lg"
                      title="Cambiar foto">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                    </button>
                    <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="handleFileUpload" />
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="font-semibold truncate" :class="isDark ? 'text-white' : 'text-slate-800'">{{ authStore.user?.name }}</p>
                    <p class="text-sm truncate" :class="isDark ? 'text-slate-400' : 'text-slate-500'">{{ authStore.user?.email }}</p>
                  </div>
                </div>
              </div>
              
              <!-- Permissions -->
              <div class="p-4 border-b" :class="isDark ? 'border-slate-700' : 'border-slate-100'">
                <p class="text-xs font-semibold uppercase mb-2" :class="isDark ? 'text-slate-500' : 'text-slate-500'">Mis Permisos</p>
                <div class="flex flex-wrap gap-1">
                  <span v-for="perm in userPermissions.slice(0, 5)" :key="perm" 
                    class="px-2 py-1 rounded text-xs"
                    :class="isDark ? 'bg-blue-500/20 text-blue-300' : 'bg-blue-100 text-blue-700'">
                    {{ formatPermission(perm) }}
                  </span>
                  <span v-if="userPermissions.length > 5" class="px-2 py-1 text-xs" :class="isDark ? 'text-slate-400' : 'text-slate-400'">
                    +{{ userPermissions.length - 5 }} más
                  </span>
                </div>
                <button v-if="!isAdmin" @click="requestPermission" 
                  class="mt-3 w-full px-3 py-2 rounded-lg text-sm transition-colors"
                  :class="isDark ? 'bg-slate-700 text-slate-300 hover:bg-slate-600' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                  Solicitar más permisos
                </button>
              </div>

              <!-- Menu Items -->
              <div class="p-2">
                <button @click="goToSettings" 
                  class="w-full text-left px-3 py-2 rounded-lg flex items-center gap-2 transition-colors"
                  :class="isDark ? 'hover:bg-slate-700 text-slate-300' : 'hover:bg-slate-100 text-slate-700'">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  Configuración
                </button>
                <button @click="handleLogout" 
                  class="w-full text-left px-3 py-2 rounded-lg flex items-center gap-2 transition-colors text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>
                  Cerrar Sesión
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="max-w-7xl mx-auto">
      <h2 class="text-xl font-bold mb-4 transition-colors"
        :class="isDark ? 'text-white' : 'text-slate-800'">
        Acciones Rápidas
      </h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <QuickActionCard 
          v-for="action in quickActions" 
          :key="action.label"
          v-bind="action"
          @click="$router.push(action.to)"
        />
      </div>

      <!-- Main Modules Grid -->
      <h2 class="text-xl font-bold mb-4 transition-colors"
        :class="isDark ? 'text-white' : 'text-slate-800'">
        Módulos
      </h2>
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <ModuleCard 
          v-for="module in modules" 
          :key="module.label"
          v-bind="module"
          :alert-count="module.alert ? alertCount : 0"
          @click="$router.push(module.to)"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import { useDarkMode } from '../../composables/useDarkMode'
import QuickActionCard from '../../components/QuickActionCard.vue'
import ModuleCard from '../../components/ModuleCard.vue'
import apiClient from '../../services/api'

const router = useRouter()
const authStore = useAuthStore()
const { isDark, toggleDarkMode } = useDarkMode()

const showUserMenu = ref(false)
const alertCount = ref(0)
const isFullscreen = ref(false)
const fileInput = ref(null)

const userInitials = computed(() => {
  const name = authStore.user?.name || ''
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

const roleLabel = computed(() => {
  const roles = { admin: 'Administrador', manager: 'Gerente', operator: 'Operador', viewer: 'Visualizador' }
  return roles[authStore.user?.role] || 'Usuario'
})

const roleBadgeClass = computed(() => {
  const classes = {
    admin: isDark.value ? 'bg-purple-500/20 text-purple-300' : 'bg-purple-100 text-purple-700',
    manager: isDark.value ? 'bg-blue-500/20 text-blue-300' : 'bg-blue-100 text-blue-700',
    operator: isDark.value ? 'bg-emerald-500/20 text-emerald-300' : 'bg-emerald-100 text-emerald-700',
    viewer: isDark.value ? 'bg-slate-500/20 text-slate-300' : 'bg-slate-100 text-slate-600'
  }
  return classes[authStore.user?.role] || classes.viewer
})

const isAdmin = computed(() => authStore.user?.role === 'admin')
const userPermissions = computed(() => authStore.user?.permissions || [])

function formatPermission(perm) {
  const map = {
    'products.view': 'Ver productos', 'products.create': 'Crear productos',
    'movements.view': 'Ver movimientos', 'movements.create': 'Crear movimientos',
    'reports.view': 'Ver reportes'
  }
  return map[perm] || perm
}

// Fullscreen functions
function toggleFullscreen() {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen().then(() => isFullscreen.value = true)
  } else {
    document.exitFullscreen().then(() => isFullscreen.value = false)
  }
}

// Avatar upload
function triggerFileInput() {
  fileInput.value?.click()
}

async function handleFileUpload(event) {
  const file = event.target.files[0]
  if (!file) return
  
  const formData = new FormData()
  formData.append('avatar', file)
  
  try {
    const response = await apiClient.post('/user/avatar', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    authStore.user.avatar = response.data.avatar_url
  } catch (err) {
    console.error('Error uploading avatar:', err)
    alert('Error al subir la imagen')
  }
}

function requestPermission() {
  const message = prompt('¿Qué permiso necesitas y por qué?')
  if (message) alert('Solicitud enviada al administrador.')
}

function goToSettings() {
  showUserMenu.value = false
  router.push('/settings')
}

function handleLogout() {
  authStore.logout()
  router.push('/login')
}

// Quick Actions Data
const quickActions = [
  { label: 'Nuevo Producto', description: 'Agregar al catálogo', to: '/products/new', icon: 'package', color: 'blue' },
  { label: 'Nuevo Movimiento', description: 'Entrada o salida', to: '/movements/new', icon: 'arrows', color: 'emerald' },
  { label: 'Transferencia', description: 'Entre almacenes', to: '/transfers/new', icon: 'transfer', color: 'indigo' },
  { label: 'Buscar Producto', description: 'Por código o nombre', to: '/products/search', icon: 'search', color: 'amber' }
]

// Modules Data
const modules = [
  { label: 'Dashboard', description: 'Gráficos y alertas', to: '/dashboard', icon: 'chart', color: 'blue', alert: true },
  { label: 'Productos', description: 'Catálogo completo', to: '/products', icon: 'package', color: 'emerald' },
  { label: 'Almacenes', description: 'Gestión de ubicaciones', to: '/warehouses', icon: 'warehouse', color: 'indigo' },
  { label: 'Almacén General', description: 'Vista consolidada', to: '/warehouse/general', icon: 'grid', color: 'purple' },
  { label: 'Movimientos', description: 'Entradas y salidas', to: '/movements', icon: 'arrows', color: 'amber' },
  { label: 'Transferencias', description: 'Entre almacenes', to: '/transfers', icon: 'transfer', color: 'cyan' },
  { label: 'Kardex', description: 'Historial de movimientos', to: '/kardex', icon: 'history', color: 'orange' },
  { label: 'Reportes', description: 'Análisis y estadísticas', to: '/reports', icon: 'report', color: 'rose' },
  { label: 'Importar', description: 'Carga masiva', to: '/import', icon: 'upload', color: 'lime' },
  { label: 'Usuarios', description: 'Gestión de equipo', to: '/users', icon: 'users', color: 'violet', admin: true },
  { label: 'Configuración', description: 'Ajustes del sistema', to: '/settings', icon: 'settings', color: 'slate' }
].filter(m => !m.admin || isAdmin.value)

onMounted(async () => {
  document.addEventListener('fullscreenchange', () => {
    isFullscreen.value = !!document.fullscreenElement
  })
})
</script>
