import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'
import { useAuthStore } from './auth'

const API_URL = import.meta.env.VITE_API_URL || 'https://inventory-pro-api-v3.onrender.com/api'

// Create axios instance with auth header
const api = axios.create({
  baseURL: API_URL,
  withCredentials: false,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  }
})

// Add request interceptor to add token
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  console.log('Interceptor - Token from localStorage:', token ? token.substring(0, 20) + '...' : 'null')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
    console.log('Interceptor - Authorization header set:', config.headers.Authorization.substring(0, 30) + '...')
  } else {
    console.warn('Interceptor - No token found!')
  }
  return config
})

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
      console.log('Fetching products from:', `${API_URL}/products`)
      const response = await api.get('/products', { params })
      console.log('Products response:', response.data)
      
      // Handle different response structures
      if (response.data.data) {
        // Laravel pagination format
        products.value = response.data.data
        pagination.value = {
          current_page: response.data.current_page || 1,
          last_page: response.data.last_page || 1,
          per_page: response.data.per_page || 25,
          total: response.data.total || 0,
        }
      } else if (Array.isArray(response.data)) {
        // Simple array format
        products.value = response.data
        pagination.value = {
          current_page: 1,
          last_page: 1,
          per_page: response.data.length,
          total: response.data.length,
        }
      } else {
        console.error('Unexpected response format:', response.data)
        products.value = []
      }
      
      console.log('Products loaded:', products.value.length)
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
      const response = await api.get(`/products/${id}`)
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
      const response = await api.post('/products', data)
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
      const response = await api.put(`/products/${id}`, data)
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
      await api.delete(`/products/${id}`)
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
