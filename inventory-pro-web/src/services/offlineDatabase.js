/**
 * IndexedDB Service for Offline Support
 * Stores products, movements, and sync queue
 */

const DB_NAME = 'StockWolfDB'
const DB_VERSION = 1

const STORES = {
  PRODUCTS: 'products',
  CATEGORIES: 'categories',
  WAREHOUSES: 'warehouses',
  STOCK_LEVELS: 'stock_levels',
  MOVEMENTS: 'movements',
  SYNC_QUEUE: 'sync_queue',
  LAST_SYNC: 'last_sync',
}

class OfflineDatabase {
  constructor() {
    this.db = null
    this.isInitialized = false
  }

  async init() {
    if (this.isInitialized) return

    return new Promise((resolve, reject) => {
      const request = indexedDB.open(DB_NAME, DB_VERSION)

      request.onerror = () => reject(request.error)
      request.onsuccess = () => {
        this.db = request.result
        this.isInitialized = true
        resolve(this.db)
      }

      request.onupgradeneeded = (event) => {
        const db = event.target.result

        // Products store
        if (!db.objectStoreNames.contains(STORES.PRODUCTS)) {
          const productsStore = db.createObjectStore(STORES.PRODUCTS, { keyPath: 'id' })
          productsStore.createIndex('sku', 'sku', { unique: false })
          productsStore.createIndex('category_id', 'category_id', { unique: false })
          productsStore.createIndex('name', 'name', { unique: false })
        }

        // Categories store
        if (!db.objectStoreNames.contains(STORES.CATEGORIES)) {
          db.createObjectStore(STORES.CATEGORIES, { keyPath: 'id' })
        }

        // Warehouses store
        if (!db.objectStoreNames.contains(STORES.WAREHOUSES)) {
          db.createObjectStore(STORES.WAREHOUSES, { keyPath: 'id' })
        }

        // Stock levels store
        if (!db.objectStoreNames.contains(STORES.STOCK_LEVELS)) {
          const stockStore = db.createObjectStore(STORES.STOCK_LEVELS, { keyPath: 'id' })
          stockStore.createIndex('product_id', 'product_id', { unique: false })
          stockStore.createIndex('warehouse_id', 'warehouse_id', { unique: false })
        }

        // Movements store (local cache)
        if (!db.objectStoreNames.contains(STORES.MOVEMENTS)) {
          const movStore = db.createObjectStore(STORES.MOVEMENTS, { keyPath: 'id' })
          movStore.createIndex('product_id', 'product_id', { unique: false })
          movStore.createIndex('created_at', 'created_at', { unique: false })
        }

        // Sync queue store
        if (!db.objectStoreNames.contains(STORES.SYNC_QUEUE)) {
          const queueStore = db.createObjectStore(STORES.SYNC_QUEUE, { 
            keyPath: 'id',
            autoIncrement: true 
          })
          queueStore.createIndex('status', 'status', { unique: false })
          queueStore.createIndex('created_at', 'created_at', { unique: false })
          queueStore.createIndex('entity_type', 'entity_type', { unique: false })
        }

        // Last sync timestamp
        if (!db.objectStoreNames.contains(STORES.LAST_SYNC)) {
          db.createObjectStore(STORES.LAST_SYNC, { keyPath: 'store' })
        }
      }
    })
  }

  // Generic methods
  async save(storeName, data) {
    await this.init()
    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([storeName], 'readwrite')
      const store = transaction.objectStore(storeName)
      const request = store.put(data)

      request.onsuccess = () => resolve(request.result)
      request.onerror = () => reject(request.error)
    })
  }

  async saveMany(storeName, items) {
    await this.init()
    const promises = items.map(item => this.save(storeName, item))
    return Promise.all(promises)
  }

  async get(storeName, id) {
    await this.init()
    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([storeName], 'readonly')
      const store = transaction.objectStore(storeName)
      const request = store.get(id)

      request.onsuccess = () => resolve(request.result)
      request.onerror = () => reject(request.error)
    })
  }

  async getAll(storeName) {
    await this.init()
    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([storeName], 'readonly')
      const store = transaction.objectStore(storeName)
      const request = store.getAll()

      request.onsuccess = () => resolve(request.result)
      request.onerror = () => reject(request.error)
    })
  }

  async getByIndex(storeName, indexName, value) {
    await this.init()
    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([storeName], 'readonly')
      const store = transaction.objectStore(storeName)
      const index = store.index(indexName)
      const request = index.getAll(value)

      request.onsuccess = () => resolve(request.result)
      request.onerror = () => reject(request.error)
    })
  }

  async delete(storeName, id) {
    await this.init()
    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([storeName], 'readwrite')
      const store = transaction.objectStore(storeName)
      const request = store.delete(id)

      request.onsuccess = () => resolve()
      request.onerror = () => reject(request.error)
    })
  }

  async clear(storeName) {
    await this.init()
    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([storeName], 'readwrite')
      const store = transaction.objectStore(storeName)
      const request = store.clear()

      request.onsuccess = () => resolve()
      request.onerror = () => reject(request.error)
    })
  }

  // Products specific
  async saveProducts(products) {
    return this.saveMany(STORES.PRODUCTS, products)
  }

  async getProducts() {
    return this.getAll(STORES.PRODUCTS)
  }

  async getProductById(id) {
    return this.get(STORES.PRODUCTS, id)
  }

  async searchProducts(query) {
    const products = await this.getAll(STORES.PRODUCTS)
    const lowerQuery = query.toLowerCase()
    return products.filter(p => 
      p.name?.toLowerCase().includes(lowerQuery) ||
      p.sku?.toLowerCase().includes(lowerQuery) ||
      p.barcode?.toLowerCase().includes(lowerQuery)
    )
  }

  // Categories
  async saveCategories(categories) {
    return this.saveMany(STORES.CATEGORIES, categories)
  }

  async getCategories() {
    return this.getAll(STORES.CATEGORIES)
  }

  // Warehouses
  async saveWarehouses(warehouses) {
    return this.saveMany(STORES.WAREHOUSES, warehouses)
  }

  async getWarehouses() {
    return this.getAll(STORES.WAREHOUSES)
  }

  // Stock Levels
  async saveStockLevels(levels) {
    return this.saveMany(STORES.STOCK_LEVELS, levels)
  }

  async getStockLevels() {
    return this.getAll(STORES.STOCK_LEVELS)
  }

  async getStockForProduct(productId) {
    return this.getByIndex(STORES.STOCK_LEVELS, 'product_id', productId)
  }

  // SYNC QUEUE METHODS
  async addToQueue(operation) {
    await this.init()
    const queueItem = {
      ...operation,
      status: 'pending',
      created_at: new Date().toISOString(),
      retry_count: 0,
    }
    return this.save(STORES.SYNC_QUEUE, queueItem)
  }

  async getPendingOperations() {
    await this.init()
    return this.getByIndex(STORES.SYNC_QUEUE, 'status', 'pending')
  }

  async getFailedOperations() {
    await this.init()
    return this.getByIndex(STORES.SYNC_QUEUE, 'status', 'failed')
  }

  async markAsSynced(id) {
    await this.init()
    const item = await this.get(STORES.SYNC_QUEUE, id)
    if (item) {
      item.status = 'synced'
      item.synced_at = new Date().toISOString()
      await this.save(STORES.SYNC_QUEUE, item)
    }
  }

  async markAsFailed(id, error) {
    await this.init()
    const item = await this.get(STORES.SYNC_QUEUE, id)
    if (item) {
      item.status = 'failed'
      item.error = error
      item.retry_count = (item.retry_count || 0) + 1
      await this.save(STORES.SYNC_QUEUE, item)
    }
  }

  async clearSyncedOperations() {
    await this.init()
    const all = await this.getAll(STORES.SYNC_QUEUE)
    const synced = all.filter(item => item.status === 'synced')
    for (const item of synced) {
      await this.delete(STORES.SYNC_QUEUE, item.id)
    }
  }

  // LAST SYNC
  async setLastSync(store, timestamp) {
    await this.save(STORES.LAST_SYNC, { store, timestamp })
  }

  async getLastSync(store) {
    const result = await this.get(STORES.LAST_SYNC, store)
    return result?.timestamp || null
  }

  // Clear all data (logout)
  async clearAllData() {
    for (const store of Object.values(STORES)) {
      await this.clear(store)
    }
  }
}

export const offlineDB = new OfflineDatabase()
export { STORES }
