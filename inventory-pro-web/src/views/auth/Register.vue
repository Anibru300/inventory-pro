<template>
  <div class="min-h-screen bg-bg-primary flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-br from-accent-primary to-accent-secondary rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-glow">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
        </div>
        <h1 class="text-2xl font-bold gradient-text">Inventory Pro</h1>
        <p class="text-text-secondary mt-1">Gestión inteligente de inventarios</p>
      </div>

      <!-- Register Form -->
      <div class="card">
        <h2 class="text-xl font-semibold mb-2 text-text-primary">Crear Cuenta</h2>
        <p class="text-text-secondary text-sm mb-6">Comienza tu prueba gratuita de 14 días. No se requiere tarjeta de crédito.</p>
        
        <form @submit.prevent="handleRegister" class="space-y-4">
          <!-- Nombre de la empresa -->
          <div>
            <label class="block text-sm font-medium mb-2 text-text-secondary">
              Nombre de tu empresa <span class="text-danger">*</span>
            </label>
            <input
              v-model="form.company_name"
              type="text"
              required
              placeholder="Ej: Mi Empresa S.A."
              class="w-full"
            />
          </div>

          <!-- Tu nombre -->
          <div>
            <label class="block text-sm font-medium mb-2 text-text-secondary">
              Tu nombre completo <span class="text-danger">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              placeholder="Ej: Juan Pérez"
              class="w-full"
            />
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium mb-2 text-text-secondary">
              Email corporativo <span class="text-danger">*</span>
            </label>
            <input
              v-model="form.email"
              type="email"
              required
              placeholder="tu@empresa.com"
              class="w-full"
            />
          </div>

          <!-- Contraseña -->
          <div>
            <label class="block text-sm font-medium mb-2 text-text-secondary">
              Contraseña <span class="text-danger">*</span>
            </label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                minlength="8"
                placeholder="Mínimo 8 caracteres"
                class="w-full pr-10"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-text-tertiary hover:text-text-secondary transition-colors"
              >
                <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
            <p class="text-xs text-text-tertiary mt-1">Debe tener al menos 8 caracteres</p>
          </div>

          <!-- Confirmar contraseña -->
          <div>
            <label class="block text-sm font-medium mb-2 text-text-secondary">
              Confirmar contraseña <span class="text-danger">*</span>
            </label>
            <input
              v-model="form.password_confirmation"
              :type="showPassword ? 'text' : 'password'"
              required
              placeholder="Repite tu contraseña"
              class="w-full"
            />
          </div>

          <!-- Términos -->
          <label class="flex items-start gap-3 cursor-pointer group">
            <input 
              v-model="form.terms" 
              type="checkbox" 
              required 
              class="mt-1 rounded border-border-default bg-bg-secondary text-accent-primary focus:ring-accent-primary focus:ring-offset-bg-primary" 
            />
            <span class="text-sm text-text-secondary group-hover:text-text-primary transition-colors">
              Acepto los <a href="#" class="text-accent-primary hover:underline">Términos de Servicio</a> y la 
              <a href="#" class="text-accent-primary hover:underline">Política de Privacidad</a>
            </span>
          </label>

          <!-- Error message -->
          <div v-if="authStore.error" class="p-4 bg-danger/10 border border-danger/30 rounded-lg text-danger text-sm flex items-start gap-2">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ authStore.error }}</span>
          </div>

          <!-- Success message -->
          <div v-if="successMessage" class="p-4 bg-success/10 border border-success/30 rounded-lg text-success text-sm flex items-start gap-2">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ successMessage }}</span>
          </div>

          <!-- Botón de registro -->
          <button
            type="submit"
            :disabled="authStore.loading || !form.terms"
            class="w-full bg-accent-primary hover:bg-accent-hover disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none text-white font-medium py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-soft hover:shadow-glow hover:-translate-y-0.5"
          >
            <svg v-if="authStore.loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ authStore.loading ? 'Creando tu cuenta...' : 'Crear Cuenta Gratis' }}</span>
          </button>
        </form>

        <!-- Link a login -->
        <div class="mt-6 text-center text-sm text-text-secondary">
          ¿Ya tienes cuenta?
          <router-link to="/login" class="text-accent-primary hover:text-accent-secondary font-medium ml-1 transition-colors">
            Inicia sesión
          </router-link>
        </div>
      </div>

      <!-- Features -->
      <div class="mt-8 grid grid-cols-3 gap-4 text-center">
        <div class="p-4">
          <div class="w-10 h-10 bg-accent-primary/10 rounded-lg flex items-center justify-center mx-auto mb-2">
            <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
          <p class="text-xs text-text-tertiary">Seguro</p>
        </div>
        <div class="p-4">
          <div class="w-10 h-10 bg-accent-primary/10 rounded-lg flex items-center justify-center mx-auto mb-2">
            <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <p class="text-xs text-text-tertiary">Rápido</p>
        </div>
        <div class="p-4">
          <div class="w-10 h-10 bg-accent-primary/10 rounded-lg flex items-center justify-center mx-auto mb-2">
            <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <p class="text-xs text-text-tertiary">Gratis 14 días</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const authStore = useAuthStore()
const showPassword = ref(false)
const successMessage = ref('')

const form = reactive({
  company_name: '',
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
})

async function handleRegister() {
  // Limpiar errores previos
  authStore.clearError()
  successMessage.value = ''

  // Validaciones del frontend
  if (form.password !== form.password_confirmation) {
    authStore.error = 'Las contraseñas no coinciden'
    return
  }

  if (form.password.length < 8) {
    authStore.error = 'La contraseña debe tener al menos 8 caracteres'
    return
  }

  try {
    await authStore.register({
      company_name: form.company_name,
      name: form.name,
      email: form.email,
      password: form.password,
      password_confirmation: form.password_confirmation,
    })
    
    // Mostrar mensaje de éxito
    successMessage.value = '¡Cuenta creada exitosamente! Redirigiendo...'
    
    // Esperar un momento y redirigir
    setTimeout(() => {
      router.push('/')
    }, 1500)
    
  } catch (err) {
    console.error('Register error:', err)
    // El error ya se maneja en el store
  }
}
</script>
