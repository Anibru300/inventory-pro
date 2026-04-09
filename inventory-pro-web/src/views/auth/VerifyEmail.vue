<template>
  <div class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Decorative background elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-200/30 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-200/20 rounded-full blur-3xl"></div>
    </div>
    
    <div class="w-full max-w-md relative z-10">
      <!-- Logo -->
      <div class="text-center mb-8">
        <router-link to="/" class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl mb-4 shadow-lg shadow-blue-600/20">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
        </router-link>
        <h1 class="text-2xl font-bold text-slate-800 mb-1">StockWolf</h1>
        <p class="text-slate-500 text-sm">Sistema ERP de Gestión de Inventarios</p>
      </div>

      <!-- Verifying -->
      <div v-if="verifying" class="bg-white rounded-2xl shadow-xl p-12 text-center">
        <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-slate-500">Verificando tu correo...</p>
      </div>

      <!-- Success State -->
      <div v-else-if="success" class="bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h2 class="text-xl font-bold mb-2 text-slate-800">¡Correo verificado!</h2>
        <p class="text-slate-500 mb-6">Tu dirección de correo ha sido verificada exitosamente. Ya puedes acceder a todas las funcionalidades de StockWolf.</p>
        <router-link to="/login" 
          class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all">
          Iniciar sesión
        </router-link>
      </div>

      <!-- Error State -->
      <div v-else class="bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h2 class="text-xl font-bold mb-2 text-slate-800">Verificación fallida</h2>
        <p class="text-slate-500 mb-6">{{ errorMessage }}</p>
        
        <div class="space-y-3">
          <router-link to="/login" 
            class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all">
            Volver al inicio de sesión
          </router-link>
        </div>
      </div>

      <!-- Footer -->
      <div class="mt-8 text-center">
        <p class="text-xs text-slate-400">© 2026 StockWolf. Todos los derechos reservados.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import apiClient from '../../services/api'

const route = useRoute()

const token = ref('')
const verifying = ref(true)
const success = ref(false)
const errorMessage = ref('El enlace ha expirado o no es válido.')

async function verifyEmail() {
  try {
    await apiClient.post('/verify-email', { token: token.value })
    success.value = true
  } catch (err) {
    success.value = false
    if (err.response?.data?.message) {
      errorMessage.value = err.response.data.message
    }
  } finally {
    verifying.value = false
  }
}

onMounted(() => {
  token.value = route.query.token || ''
  if (!token.value) {
    verifying.value = false
    success.value = false
  } else {
    verifyEmail()
  }
})
</script>
