import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const API_URL = import.meta.env.VITE_API_URL || 'https://inventory-pro-api-v3.onrender.com/api'

// Create configured axios instance
const apiClient = axios.create({
  baseURL: API_URL,
  withCredentials: false,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
})

// Request interceptor to add auth token
// Usando X-Auth-Token para evitar problema de Apache 2.4 + mod_php que filtra Authorization header
apiClient.interceptors.request.use((config) => {
  // Intentar obtener token del authStore primero, luego de localStorage
  let token = null
  try {
    const authStore = useAuthStore()
    token = authStore.token
  } catch (e) {
    // Si no podemos acceder al store (ej: fuera de componente), usar localStorage
    token = localStorage.getItem('token')
  }
  
  // Fallback a localStorage si el store no tiene token
  if (!token) {
    token = localStorage.getItem('token')
  }
  
  console.log('API Interceptor - Token:', token ? 'Presente' : 'No encontrado')
  if (token) {
    // Usar X-Auth-Token en lugar de Authorization para evitar filtrado de Apache
    config.headers['X-Auth-Token'] = token
    console.log('API Interceptor - X-Auth-Token set:', `${token.substring(0, 20)}...`)
  }
  return config
})

// Response interceptor for error handling
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token')
      window.location.href = '/#/login'
    }
    return Promise.reject(error)
  }
)

export default apiClient
