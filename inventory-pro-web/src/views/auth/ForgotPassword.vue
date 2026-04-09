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

      <!-- Success State -->
      <div v-if="success" class="bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <h2 class="text-xl font-bold mb-2 text-slate-800">¡Correo enviado!</h2>
        <p class="text-slate-500 mb-6">Hemos enviado un enlace de recuperación a <strong>{{ email }}</strong>. Revisa tu bandeja de entrada y sigue las instrucciones.</p>
        <div class="space-y-3">
          <router-link to="/login" 
            class="w-full flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all">
            Volver al inicio de sesión
          </router-link>
          <button @click="resetForm" 
            class="w-full px-6 py-3 text-slate-600 font-medium hover:text-blue-600 transition-colors">
            Enviar a otro correo
          </button>
        </div>
      </div>

      <!-- Form -->
      <div v-else class="bg-white rounded-2xl shadow-xl p-8">
        <div class="flex items-center gap-2 mb-6">
          <router-link to="/login" class="p-2 -ml-2 text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </router-link>
          <div>
            <h2 class="text-xl font-bold text-slate-800">Recuperar Contraseña</h2>
            <p class="text-slate-500 text-sm">Ingresa tu correo para recibir instrucciones</p>
          </div>
        </div>
        
        <form @submit.prevent="handleSubmit" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Correo electrónico</label>
            <input 
              v-model="email" 
              type="email" 
              required 
              placeholder="tu@empresa.com" 
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
              :class="{ 'border-rose-500': error }"
              :disabled="loading"
            />
            <p v-if="error" class="mt-2 text-sm text-rose-600">{{ error }}</p>
          </div>

          <button 
            type="submit" 
            :disabled="loading || !email" 
            class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg v-if="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span>{{ loading ? 'Enviando...' : 'Enviar instrucciones' }}</span>
          </button>
        </form>

        <div class="mt-6 text-center">
          <p class="text-sm text-slate-500">
            ¿Recordaste tu contraseña? 
            <router-link to="/login" class="text-blue-600 hover:text-blue-700 font-medium">Inicia sesión</router-link>
          </p>
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
import { ref } from 'vue'
import apiClient from '../../services/api'

const email = ref('')
const loading = ref(false)
const error = ref('')
const success = ref(false)

async function handleSubmit() {
  loading.value = true
  error.value = ''
  
  try {
    await apiClient.post('/forgot-password', { email: email.value })
    success.value = true
  } catch (err) {
    if (err.response?.data?.errors?.email) {
      error.value = err.response.data.errors.email[0]
    } else {
      error.value = 'Error al procesar la solicitud. Intenta más tarde.'
    }
  } finally {
    loading.value = false
  }
}

function resetForm() {
  email.value = ''
  success.value = false
  error.value = ''
}
</script>
