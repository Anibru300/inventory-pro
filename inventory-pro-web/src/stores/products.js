import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import apiClient from '../services/api'
import { useAuthStore } from './auth'

export const useProductsStore = defineStore('products', () => {
  // State
  const products = ref([])
  const currentProduct = ref(null)
  const loading = ref(false)
  const error = ref(null)
  const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 25,
    total: 0,
  })

  // Getters
  const lowStockProducts = computed(() => 
    products.value.filter(p => p.stock_status === 'low_stock')
  )
  
  const outOfStockProducts = computed(() => 
    products.value.filter(p => p.stock_status === 'out_of_stock')
  )

  // Actions
  async function fetchProducts(params = {}) {
    loading.value = true
    error.value = null

    try {
      console.log('=== DEBUG: Fetching products... ===')
      console.log('Params:', params)
      const response = await apiClient.get('/products', { params })
      console.log('=== DEBUG: Raw response ===', response)
      console.log('=== DEBUG: Response data ===', response.data)
      console.log('=== DEBUG: Response data type ===', typeof response.data)
      console.log('=== DEBUG: Has data property? ===', response.data && 'data' in response.data)
      console.log('=== DEBUG: data is array? ===', Array.isArray(response.data?.data))
      console.log('=== DEBUG: data array length ===', response.data?.data?.length)
      
      // Handle different response structures
      if (response.data && response.data.data && Array.isArray(response.data.data)) {
        // Laravel pagination format
        console.log('=== DEBUG: Using Laravel pagination format ===')
        products.value = response.data.data
        pagination.value = {
          current_page: response.data.current_page || 1,
          last_page: response.data.last_page || 1,
          per_page: response.data.per_page || 25,
          total: response.data.total || 0,
        }
      } else if (Array.isArray(response.data)) {
        // Simple array format
        console.log('=== DEBUG: Using simple array format ===')
        products.value = response.data
        pagination.value = {
          current_page: 1,
          last_page: 1,
          per_page: response.data.length,
          total: response.data.length,
        }
      } else {
        console.error('=== DEBUG: Unexpected response format ===', response.data)
        products.value = []
      }
      
      console.log('=== DEBUG: Final products.value ===', products.value)
      console.log('=== DEBUG: Products loaded count ===', products.value.length)
      return response.data
    } catch (err) {
      console.error('Error fetching products:', err)
      error.value = err.response?.data?.message || 'Error al cargar productos'
      products.value = []
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchProduct(id) {
    loading.value = true
    error.value = null

    try {
      const response = await apiClient.get(`/products/${id}`)
      currentProduct.value = response.data
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Error al cargar producto'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function createProduct(data) {
    loading.value = true
    error.value = null

    try {
      const response = await apiClient.post('/products', data)
      products.value.unshift(response.data.product)
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Error al crear producto'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function updateProduct(id, data) {
    loading.value = true
    error.value = null

    try {
      const response = await apiClient.put(`/products/${id}`, data)
      const index = products.value.findIndex(p => p.id === id)
      if (index !== -1) {
        products.value[index] = response.data.product
      }
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Error al actualizar producto'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function deleteProduct(id) {
    loading.value = true
    error.value = null

    try {
      await apiClient.delete(`/products/${id}`)
      products.value = products.value.filter(p => p.id !== id)
    } catch (err) {
      error.value = err.response?.data?.message || 'Error al eliminar producto'
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    products,
    currentProduct,
    loading,
    error,
    pagination,
    lowStockProducts,
    outOfStockProducts,
    fetchProducts,
    fetchProduct,
    createProduct,
    updateProduct,
    deleteProduct,
  }
})
