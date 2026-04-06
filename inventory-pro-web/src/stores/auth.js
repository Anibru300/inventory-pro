import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

// Create axios instance
const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
})

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref(null)
  const token = ref(localStorage.getItem('token'))
  const loading = ref(false)
  const error = ref(null)

  // Setup axios interceptor for token
  if (token.value) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }

  // Response interceptor for error handling
  api.interceptors.response.use(
    (response) => response,
    (error) => {
      if (error.response?.status === 401) {
        clearAuth()
        window.location.href = '/login'
      }
      return Promise.reject(error)
    }
  )

  // Getters
  const isAuthenticated = computed(() => !!token.value && !!user.value)
  const isAdmin = computed(() => user.value?.role === 'admin' || user.value?.role === 'super_admin')
  const tenant = computed(() => user.value?.tenant)
  const userFullName = computed(() => {
    if (!user.value) return ''
    return `${user.value.first_name || ''} ${user.value.last_name || ''}`.trim()
  })

  // Actions
  async function login(credentials) {
    loading.value = true
    error.value = null

    try {
      const response = await api.post('/login', credentials)
      
      token.value = response.data.token
      user.value = response.data.user
      
      localStorage.setItem('token', token.value)
      api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || err.response?.data?.errors?.email?.[0] || 'Error al iniciar sesión'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function register(data) {
    loading.value = true
    error.value = null

    try {
      const response = await api.post('/register', data)
      
      token.value = response.data.token
      user.value = response.data.user
      
      localStorage.setItem('token', token.value)
      api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
      
      return response.data
    } catch (err) {
      const message = err.response?.data?.message
      const errors = err.response?.data?.errors
      
      if (errors) {
        const firstError = Object.values(errors)[0]
        error.value = Array.isArray(firstError) ? firstError[0] : firstError
      } else {
        error.value = message || 'Error al registrar'
      }
      throw err
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      await api.post('/logout')
    } catch (err) {
      console.error('Logout error:', err)
    } finally {
      clearAuth()
    }
  }

  async function fetchUser() {
    try {
      const response = await api.get('/me')
      user.value = response.data.user
      return response.data.user
    } catch (err) {
      clearAuth()
      throw err
    }
  }

  function initializeAuth() {
    const savedToken = localStorage.getItem('token')
    if (savedToken) {
      token.value = savedToken
      api.defaults.headers.common['Authorization'] = `Bearer ${savedToken}`
      fetchUser().catch(() => clearAuth())
    }
  }

  function clearAuth() {
    user.value = null
    token.value = null
    localStorage.removeItem('token')
    delete api.defaults.headers.common['Authorization']
  }

  function clearError() {
    error.value = null
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    isAdmin,
    tenant,
    userFullName,
    login,
    register,
    logout,
    fetchUser,
    initializeAuth,
    clearAuth,
    clearError,
  }
})