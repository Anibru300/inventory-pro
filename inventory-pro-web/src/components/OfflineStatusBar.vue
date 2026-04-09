<template>
  <div v-if="show" class="fixed bottom-4 right-4 z-50 flex flex-col gap-2">
    <!-- Offline indicator -->
    <Transition enter-from-class="translate-y-4 opacity-0" enter-to-class="translate-y-0 opacity-100">
      <div v-if="!isOnline" class="bg-rose-600 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414" />
        </svg>
        <div>
          <p class="font-medium">Sin conexión</p>
          <p class="text-xs text-rose-200">Trabajando en modo offline</p>
        </div>
      </div>
    </Transition>

    <!-- Syncing indicator -->
    <Transition enter-from-class="translate-y-4 opacity-0" enter-to-class="translate-y-0 opacity-100">
      <div v-if="isSyncing" class="bg-blue-600 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3">
        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <div>
          <p class="font-medium">Sincronizando...</p>
          <p class="text-xs text-blue-200">Enviando cambios al servidor</p>
        </div>
      </div>
    </Transition>

    <!-- Pending operations -->
    <Transition enter-from-class="translate-y-4 opacity-0" enter-to-class="translate-y-0 opacity-100">
      <div v-if="pendingCount > 0 && !isSyncing && isOnline" 
        class="bg-amber-500 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 cursor-pointer hover:bg-amber-600 transition-colors"
        @click="retrySync">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        <div class="flex-1">
          <p class="font-medium">{{ pendingCount }} operación{{ pendingCount > 1 ? 'es' : '' }} pendiente</p>
          <p class="text-xs text-amber-100">Toca para sincronizar ahora</p>
        </div>
      </div>
    </Transition>

    <!-- Last sync info -->
    <Transition enter-from-class="translate-y-4 opacity-0" enter-to-class="translate-y-0 opacity-100">
      <div v-if="isOnline && !isSyncing && pendingCount === 0 && lastSync" 
        class="bg-emerald-600 text-white px-4 py-2 rounded-xl shadow-lg flex items-center gap-2 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>Sincronizado {{ formatLastSync(lastSync) }}</span>
      </div>
    </Transition>
  </div>

  <!-- Compact indicator for header -->
  <div v-if="compact" class="flex items-center gap-2">
    <div v-if="!isOnline" class="flex items-center gap-1 text-rose-500" title="Sin conexión">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414" />
      </svg>
      <span class="text-xs font-medium">Offline</span>
    </div>
    <div v-else-if="pendingCount > 0" class="flex items-center gap-1 text-amber-500" title="Pendientes: {{ pendingCount }}">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span class="text-xs font-medium">{{ pendingCount }}</span>
    </div>
    <div v-else class="flex items-center gap-1 text-emerald-500" title="Sincronizado">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { syncService } from '../services/syncService'

const props = defineProps({
  compact: {
    type: Boolean,
    default: false,
  },
})

const isOnline = ref(syncService.getOnlineStatus())
const isSyncing = ref(false)
const pendingCount = ref(0)
const lastSync = ref(null)
const show = ref(true)

let unsubscribe = null

onMounted(() => {
  unsubscribe = syncService.onStatusChange(async (status) => {
    if (status === 'online') isOnline.value = true
    if (status === 'offline') isOnline.value = false
    if (status === 'syncing') isSyncing.value = true
    if (status === 'synced') {
      isSyncing.value = false
      await updateStatus()
    }
  })

  updateStatus()
})

onUnmounted(() => {
  if (unsubscribe) unsubscribe()
})

async function updateStatus() {
  const status = await syncService.getSyncStatus()
  pendingCount.value = status.pendingCount + status.failedCount
  lastSync.value = status.lastSync
}

async function retrySync() {
  await syncService.retryFailed()
}

function formatLastSync(date) {
  if (!date) return ''
  const d = new Date(date)
  const now = new Date()
  const diff = now - d
  
  // Less than 1 minute
  if (diff < 60000) return 'hace un momento'
  
  // Less than 1 hour
  if (diff < 3600000) {
    const mins = Math.floor(diff / 60000)
    return `hace ${mins} min`
  }
  
  // Less than 24 hours
  if (diff < 86400000) {
    const hours = Math.floor(diff / 3600000)
    return `hace ${hours}h`
  }
  
  return d.toLocaleDateString('es-MX')
}
</script>
