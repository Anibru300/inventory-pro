import { ref, onMounted, onUnmounted } from 'vue'
import { syncService } from '../services/syncService'
import { offlineDB } from '../services/offlineDatabase'

export function useOffline() {
  const isOnline = ref(syncService.getOnlineStatus())
  const isSyncing = ref(false)
  const pendingCount = ref(0)
  const lastSync = ref(null)
  const isInitialized = ref(false)

  let unsubscribe = null

  onMounted(async () => {
    // Initialize sync service
    syncService.init()

    // Subscribe to status changes
    unsubscribe = syncService.onStatusChange((status) => {
      if (status === 'online') isOnline.value = true
      if (status === 'offline') isOnline.value = false
      if (status === 'syncing') isSyncing.value = true
      if (status === 'synced') {
        isSyncing.value = false
        updateStatus()
      }
    })

    // Initial status
    await updateStatus()
    isInitialized.value = true
  })

  onUnmounted(() => {
    if (unsubscribe) unsubscribe()
  })

  async function updateStatus() {
    const status = await syncService.getSyncStatus()
    pendingCount.value = status.pendingCount + status.failedCount
    lastSync.value = status.lastSync
  }

  async function fullSync() {
    return syncService.fullSync()
  }

  async function retrySync() {
    return syncService.retryFailed()
  }

  async function getProducts() {
    if (isOnline.value) {
      try {
        const response = await fetch('/api/products')
        const data = await response.json()
        // Update local cache
        await offlineDB.saveProducts(data.data || data)
        return data.data || data
      } catch (error) {
        // Fallback to offline
        return offlineDB.getProducts()
      }
    } else {
      return offlineDB.getProducts()
    }
  }

  async function searchProducts(query) {
    if (!query) return getProducts()
    
    // Always search locally for speed
    const results = await offlineDB.searchProducts(query)
    
    // If online and few results, try server
    if (isOnline.value && results.length < 5) {
      try {
        const response = await fetch(`/api/products?search=${encodeURIComponent(query)}`)
        const data = await response.json()
        return data.data || data
      } catch (error) {
        return results
      }
    }
    
    return results
  }

  return {
    isOnline,
    isSyncing,
    pendingCount,
    lastSync,
    isInitialized,
    fullSync,
    retrySync,
    getProducts,
    searchProducts,
  }
}
