import axios from 'axios'

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
  // Obtener token de localStorage
  const token = localStorage.getItem('token')
  
  if (token) {
    // Usar X-Auth-Token en lugar de Authorization para evitar filtrado de Apache
    config.headers['X-Auth-Token'] = token
    console.log('API Interceptor - X-Auth-Token set:', `${token.substring(0, 20)}...`)
  } else {
    console.log('API Interceptor - Token: No encontrado')
  }
  
  return config
})

// Response interceptor for error handling
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      console.log('API Interceptor - 401 Unauthorized, limpiando token')
      localStorage.removeItem('token')
      window.location.href = '/#/login'
    }
    return Promise.reject(error)
  }
)

export default apiClient
