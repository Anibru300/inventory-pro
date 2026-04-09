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

      <!-- Validating Token -->
      <div v-if="validating" class="bg-white rounded-2xl shadow-xl p-12 text-center">
        <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-slate-500">Validando enlace...</p>
      </div>

      <!-- Invalid Token -->
      <div v-else-if="!isValidToken" class="bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h2 class="text-xl font-bold mb-2 text-slate-800">Enlace expirado</h2>
        <p class="text-slate-500 mb-6">El enlace de recuperación ya no es válido o ha expirado. Por favor, solicita uno nuevo.</p>
        <router-link to="/forgot-password" 
          class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all">
          Solicitar nuevo enlace
        </router-link>
      </div>

      <!-- Success State -->
      <div v-else-if="success" class="bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <h2 class="text-xl font-bold mb-2 text-slate-800">¡Contraseña actualizada!</h2>
        <p class="text-slate-500 mb-6">Tu contraseña ha sido restablecida exitosamente. Ahora puedes iniciar sesión con tu nueva contraseña.</p>
        <router-link to="/login" 
          class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all">
          Iniciar sesión
        </router-link>
      </div>

      <!-- Reset Form -->
      <div v-else class="bg-white rounded-2xl shadow-xl p-8">
        <h2 class="text-xl font-bold mb-1 text-slate-800">Nueva Contraseña</h2>
        <p class="text-slate-500 text-sm mb-6">Crea una contraseña segura para tu cuenta</p>
        
        <form @submit.prevent="handleSubmit" class="space-y-5">
          <!-- New Password -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Nueva contraseña</label>
            <div class="relative">
              <input 
                v-model="password" 
                :type="showPassword ? 'text' : 'password'" 
                required 
                minlength="8"
                placeholder="••••••••" 
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all pr-12"
                :class="{ 'border-rose-500': errors.password }"
              />
              <button type="button" @click="showPassword = !showPassword" 
                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 transition-colors">
                <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
            <p v-if="errors.password" class="mt-2 text-sm text-rose-600">{{ errors.password }}</p>
            <p v-else class="mt-2 text-xs text-slate-400">Mínimo 8 caracteres</p>
          </div>

          <!-- Confirm Password -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Confirmar contraseña</label>
            <div class="relative">
              <input 
                v-model="passwordConfirmation" 
                :type="showPassword ? 'text' : 'password'" 
                required 
                placeholder="••••••••" 
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all pr-12"
                :class="{ 'border-rose-500': errors.password_confirmation }"
              />
            </div>
            <p v-if="errors.password_confirmation" class="mt-2 text-sm text-rose-600">{{ errors.password_confirmation }}</p>
          </div>

          <!-- Password Strength Indicator -->
          <div v-if="password" class="space-y-2">
            <div class="flex gap-1">
              <div v-for="n in 4" :key="n" 
                class="flex-1 h-1.5 rounded-full transition-colors"
                :class="passwordStrength >= n ? strengthColor : 'bg-slate-200'"></div>
            </div>
            <p class="text-xs" :class="strengthTextColor">{{ strengthLabel }}</p>
          </div>

          <!-- General Error -->
          <div v-if="errors.general" class="p-4 bg-rose-50 text-rose-700 rounded-xl text-sm">
            {{ errors.general }}
          </div>

          <button 
            type="submit" 
            :disabled="loading || !isFormValid" 
            class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg v-if="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
            <span>{{ loading ? 'Actualizando...' : 'Restablecer contraseña' }}</span>
          </button>
        </form>
      </div>

      <!-- Footer -->
      <div class="mt-8 text-center">
        <p class="text-xs text-slate-400">© 2026 StockWolf. Todos los derechos reservados.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import apiClient from '../../services/api'

const route = useRoute()

const token = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const showPassword = ref(false)
const loading = ref(false)
const validating = ref(true)
const isValidToken = ref(false)
const success = ref(false)
const errors = ref({})

// Password strength calculation
const passwordStrength = computed(() => {
  let score = 0
  if (password.value.length >= 8) score++
  if (/[a-z]/.test(password.value) && /[A-Z]/.test(password.value)) score++
  if (/\d/.test(password.value)) score++
  if (/[^a-zA-Z0-9]/.test(password.value)) score++
  return score
})

const strengthColor = computed(() => {
  const colors = ['bg-rose-500', 'bg-amber-500', 'bg-yellow-500', 'bg-emerald-500']
  return colors[passwordStrength.value - 1] || 'bg-slate-200'
})

const strengthTextColor = computed(() => {
  const colors = ['text-rose-600', 'text-amber-600', 'text-yellow-600', 'text-emerald-600']
  return colors[passwordStrength.value - 1] || 'text-slate-400'
})

const strengthLabel = computed(() => {
  const labels = ['Débil', 'Regular', 'Buena', 'Excelente']
  return labels[passwordStrength.value - 1] || 'Ingresa una contraseña'
})

const isFormValid = computed(() => {
  return password.value.length >= 8 && 
         password.value === passwordConfirmation.value
})

async function validateToken() {
  try {
    const response = await apiClient.post('/validate-reset-token', { token: token.value })
    isValidToken.value = response.data.valid
  } catch (err) {
    isValidToken.value = false
  } finally {
    validating.value = false
  }
}

async function handleSubmit() {
  if (!isFormValid.value) return
  
  loading.value = true
  errors.value = {}
  
  try {
    await apiClient.post('/reset-password', {
      token: token.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value
    })
    success.value = true
  } catch (err) {
    if (err.response?.data?.errors) {
      errors.value = err.response.data.errors
    } else if (err.response?.data?.message) {
      errors.value.general = err.response.data.message
    } else {
      errors.value.general = 'Error al restablecer la contraseña. Intenta más tarde.'
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  token.value = route.query.token || ''
  if (!token.value) {
    isValidToken.value = false
    validating.value = false
  } else {
    validateToken()
  }
})
</script>
