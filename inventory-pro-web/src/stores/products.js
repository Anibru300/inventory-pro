import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'

const API_URL = import.meta.env.VITE_API_URL || 'https://inventory-pro-api-v3.onrender.com/api'

// Create axios instance with auth header
function getAuthHeaders() {
  const token = localStorage.getItem('token')
  return {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    ...(token ? { 'Authorization': `Bearer ${token}` } : {})
  }
}

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
      const response = await axios.get(`${API_URL}/products`, { 
        params,
        headers: getAuthHeaders()
      })
      products.value = response.data.data
      pagination.value = {
        current_page: response.data.current_page,
        last_page: response.data.last_page,
        per_page: response.data.per_page,
        total: response.data.total,
      }
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Error al cargar productos'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchProduct(id) {
    loading.value = true
    error.value = null

    try {
      const response = await axios.get(`${API_URL}/products/${id}`, {
        headers: getAuthHeaders()
      })
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
      const response = await axios.post(`${API_URL}/products`, data, {
        headers: getAuthHeaders()
      })
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
      const response = await axios.put(`${API_URL}/products/${id}`, data, {
        headers: getAuthHeaders()
      })
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
      await axios.delete(`${API_URL}/products/${id}`, {
        headers: getAuthHeaders()
      })
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
