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
apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  console.log('API Interceptor - Token:', token ? 'Presente' : 'No encontrado')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
    console.log('API Interceptor - Header set:', `Bearer ${token.substring(0, 20)}...`)
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
