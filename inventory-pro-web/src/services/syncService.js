import { offlineDB, STORES } from './offlineDatabase'
import apiClient from './api'

/**
 * Sync Service - Handles online/offline synchronization
 */
class SyncService {
  constructor() {
    this.isOnline = navigator.onLine
    this.isSyncing = false
    this.syncCallbacks = []
  }

  init() {
    // Listen for online/offline events
    window.addEventListener('online', () => {
      this.isOnline = true
      this.triggerSync()
      this.notifyCallbacks('online')
    })

    window.addEventListener('offline', () => {
      this.isOnline = false
      this.notifyCallbacks('offline')
    })

    // Try to sync on init if online
    if (this.isOnline) {
      this.triggerSync()
    }
  }

  onStatusChange(callback) {
    this.syncCallbacks.push(callback)
    // Return unsubscribe function
    return () => {
      this.syncCallbacks = this.syncCallbacks.filter(cb => cb !== callback)
    }
  }

  notifyCallbacks(status) {
    this.syncCallbacks.forEach(cb => cb(status))
  }

  getOnlineStatus() {
    return this.isOnline
  }

  // Full data sync from server
  async fullSync() {
    if (!this.isOnline) {
      throw new Error('No hay conexión a internet')
    }

    this.isSyncing = true
    this.notifyCallbacks('syncing')

    try {
      // Sync products
      const productsRes = await apiClient.get('/products?per_page=1000')
      const products = productsRes.data.data || productsRes.data
      await offlineDB.saveProducts(products)
      await offlineDB.setLastSync(STORES.PRODUCTS, new Date().toISOString())

      // Sync categories
      const categoriesRes = await apiClient.get('/categories')
      await offlineDB.saveCategories(categoriesRes.data)
      await offlineDB.setLastSync(STORES.CATEGORIES, new Date().toISOString())

      // Sync warehouses
      const warehousesRes = await apiClient.get('/warehouses')
      await offlineDB.saveWarehouses(warehousesRes.data)
      await offlineDB.setLastSync(STORES.WAREHOUSES, new Date().toISOString())

      // Sync stock levels
      const stockRes = await apiClient.get('/stock-levels')
      await offlineDB.saveStockLevels(stockRes.data)
      await offlineDB.setLastSync(STORES.STOCK_LEVELS, new Date().toISOString())

      this.isSyncing = false
      this.notifyCallbacks('synced')

      return {
        success: true,
        products: products.length,
        categories: categoriesRes.data.length,
        warehouses: warehousesRes.data.length,
      }
    } catch (error) {
      this.isSyncing = false
      this.notifyCallbacks('error')
      throw error
    }
  }

  // Process pending operations queue
  async processQueue() {
    if (!this.isOnline || this.isSyncing) return

    this.isSyncing = true
    this.notifyCallbacks('syncing')

    try {
      const pendingOps = await offlineDB.getPendingOperations()
      const failedOps = await offlineDB.getFailedOperations()
      const allOps = [...pendingOps, ...failedOps.filter(op => op.retry_count < 3)]

      const results = {
        success: 0,
        failed: 0,
        errors: [],
      }

      for (const operation of allOps) {
        try {
          await this.executeOperation(operation)
          await offlineDB.markAsSynced(operation.id)
          results.success++
        } catch (error) {
          await offlineDB.markAsFailed(operation.id, error.message)
          results.failed++
          results.errors.push({ operation: operation.id, error: error.message })
        }
      }

      // Clear old synced operations
      await offlineDB.clearSyncedOperations()

      this.isSyncing = false
      this.notifyCallbacks('synced')

      return results
    } catch (error) {
      this.isSyncing = false
      this.notifyCallbacks('error')
      throw error
    }
  }

  // Execute a single operation
  async executeOperation(operation) {
    const { entity_type, action, data, endpoint } = operation.payload

    switch (action) {
      case 'create':
        await apiClient.post(endpoint, data)
        break
      case 'update':
        await apiClient.put(`${endpoint}/${data.id}`, data)
        break
      case 'delete':
        await apiClient.delete(`${endpoint}/${data.id}`)
        break
      case 'movement':
        await apiClient.post('/stock-movements', data)
        // Update local stock level
        await this.updateLocalStockLevel(data)
        break
      default:
        throw new Error(`Unknown action: ${action}`)
    }
  }

  // Update local stock after movement is synced
  async updateLocalStockLevel(movementData) {
    const { product_id, warehouse_id, quantity, type } = movementData
    const levels = await offlineDB.getStockLevels()
    
    const existingLevel = levels.find(
      l => l.product_id === product_id && l.warehouse_id === warehouse_id
    )

    if (existingLevel) {
      const qtyChange = type === 'entry' ? quantity : -quantity
      existingLevel.quantity = (existingLevel.quantity || 0) + qtyChange
      await offlineDB.save(STORES.STOCK_LEVELS, existingLevel)
    }
  }

  // Queue an operation for later sync
  async queueOperation(operation) {
    const queueItem = {
      payload: operation,
      status: 'pending',
      created_at: new Date().toISOString(),
    }
    await offlineDB.addToQueue(queueItem)
  }

  // Create stock movement (works offline)
  async createMovement(movementData) {
    // Always save to local first
    const localMovement = {
      id: `local_${Date.now()}`,
      ...movementData,
      is_local: true,
      created_at: new Date().toISOString(),
    }
    await offlineDB.save(STORES.MOVEMENTS, localMovement)

    // Update local stock level immediately
    await this.updateLocalStockLevel(movementData)

    // Queue for sync if online
    if (this.isOnline) {
      try {
        await apiClient.post('/stock-movements', movementData)
        localMovement.is_synced = true
        await offlineDB.save(STORES.MOVEMENTS, localMovement)
        return { success: true, synced: true }
      } catch (error) {
        // Queue for later if API fails
        await this.queueOperation({
          entity_type: 'movement',
          action: 'movement',
          data: movementData,
          endpoint: '/stock-movements',
        })
        return { success: true, synced: false, queued: true }
      }
    } else {
      // Queue for later if offline
      await this.queueOperation({
        entity_type: 'movement',
        action: 'movement',
        data: movementData,
        endpoint: '/stock-movements',
      })
      return { success: true, synced: false, queued: true, offline: true }
    }
  }

  // Auto-sync trigger
  triggerSync() {
    if (this.isOnline && !this.isSyncing) {
      this.processQueue()
    }
  }

  // Get sync status
  async getSyncStatus() {
    const pendingOps = await offlineDB.getPendingOperations()
    const failedOps = await offlineDB.getFailedOperations()
    const lastProductsSync = await offlineDB.getLastSync(STORES.PRODUCTS)

    return {
      isOnline: this.isOnline,
      isSyncing: this.isSyncing,
      pendingCount: pendingOps.length,
      failedCount: failedOps.length,
      lastSync: lastProductsSync,
    }
  }

  // Force retry failed operations
  async retryFailed() {
    const failedOps = await offlineDB.getFailedOperations()
    
    for (const op of failedOps) {
      op.status = 'pending'
      op.retry_count = 0
      await offlineDB.save(STORES.SYNC_QUEUE, op)
    }

    return this.processQueue()
  }
}

export const syncService = new SyncService()
