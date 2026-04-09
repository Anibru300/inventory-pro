<template>
  <div class="space-y-4">
    <button 
      @click="handleGoogleLogin"
      :disabled="loading"
      class="w-full flex items-center justify-center gap-3 px-6 py-3 bg-white border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 hover:border-slate-400 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
    >
      <svg v-if="loading" class="w-5 h-5 animate-spin text-slate-600" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <template v-else>
        <svg class="w-5 h-5" viewBox="0 0 24 24">
          <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
          <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
          <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
          <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        <span>{{ isRegister ? 'Registrarse con Google' : 'Continuar con Google' }}</span>
      </template>
    </button>

    <!-- Company name modal for new users -->
    <div v-if="showCompanyModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
      <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl">
        <h3 class="text-xl font-bold mb-2 text-slate-800">Completa tu registro</h3>
        <p class="text-slate-500 mb-4">Ingresa el nombre de tu empresa para crear tu cuenta.</p>
        
        <form @submit.prevent="completeRegistration" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Nombre de la empresa</label>
            <input 
              v-model="companyName" 
              type="text" 
              required
              placeholder="Mi Empresa S.A. de C.V."
              class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
            />
          </div>
          
          <div class="flex gap-3">
            <button 
              type="button"
              @click="showCompanyModal = false"
              class="flex-1 px-4 py-3 border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-colors"
            >
              Cancelar
            </button>
            <button 
              type="submit"
              :disabled="!companyName || loading"
              class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors disabled:opacity-50"
            >
              {{ loading ? 'Creando...' : 'Crear cuenta' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import apiClient from '../services/api'
import { useAuthStore } from '../stores/auth'

const props = defineProps({
  isRegister: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['success', 'error'])

const router = useRouter()
const authStore = useAuthStore()

const loading = ref(false)
const showCompanyModal = ref(false)
const companyName = ref('')
const googleToken = ref(null)
const googleUserData = ref(null)

// Load Google API
onMounted(() => {
  console.log('Google Client ID:', import.meta.env.VITE_GOOGLE_CLIENT_ID)
  
  if (!window.google) {
    const script = document.createElement('script')
    script.src = 'https://accounts.google.com/gsi/client'
    script.async = true
    script.defer = true
    script.onload = () => {
      console.log('Google API loaded successfully')
    }
    script.onerror = (err) => {
      console.error('Failed to load Google API:', err)
    }
    document.head.appendChild(script)
  }
})

const clientId = import.meta.env.VITE_GOOGLE_CLIENT_ID

async function handleGoogleLogin() {
  // Check if client ID is configured
  if (!clientId) {
    alert('Error: Google Client ID no configurado. Contacta al administrador.')
    console.error('VITE_GOOGLE_CLIENT_ID is not defined')
    return
  }

  loading.value = true
  
  try {
    // Wait for Google API to load
    if (!window.google) {
      loading.value = false
      throw new Error('Google API no cargada. Intenta de nuevo en unos segundos.')
    }

    const client = window.google.accounts.oauth2.initTokenClient({
      client_id: clientId,
      scope: 'email profile',
      callback: async (response) => {
        if (response.error) {
          handleError(response.error)
          return
        }
        
        googleToken.value = response.access_token
        await processGoogleToken(response.access_token)
      },
    })

    client.requestAccessToken()
  } catch (error) {
    handleError(error.message)
  }
}

async function processGoogleToken(token) {
  try {
    const response = await apiClient.post('/auth/google/token', {
      access_token: token,
      company_name: props.isRegister ? companyName.value : undefined,
    })

    // Success - user exists and logged in
    authStore.token = response.data.token
    authStore.user = response.data.user
    localStorage.setItem('token', response.data.token)
    
    emit('success', response.data)
    router.push('/dashboard')
    
  } catch (error) {
    if (error.response?.data?.requires_company) {
      // New user needs company name
      googleUserData.value = error.response.data
      showCompanyModal.value = true
      loading.value = false
    } else {
      handleError(error.response?.data?.message || error.message)
    }
  }
}

async function completeRegistration() {
  if (!companyName.value || !googleToken.value) return
  
  loading.value = true
  
  try {
    const response = await apiClient.post('/auth/google/token', {
      access_token: googleToken.value,
      company_name: companyName.value,
    })

    authStore.token = response.data.token
    authStore.user = response.data.user
    localStorage.setItem('token', response.data.token)
    
    showCompanyModal.value = false
    emit('success', response.data)
    router.push('/dashboard')
    
  } catch (error) {
    handleError(error.response?.data?.message || error.message)
  }
}

function handleError(message) {
  loading.value = false
  emit('error', message)
}
</script>
