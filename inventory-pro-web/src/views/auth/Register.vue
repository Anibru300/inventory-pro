<template>
  <div class="min-h-screen bg-cj-navy flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-6">
        <div class="flex items-center justify-center mb-4">
          <img 
            src="/logo-lobo.png" 
            alt="CJ Consultoría" 
            class="h-20 w-auto logo-glow"
          />
        </div>
        <h1 class="text-2xl font-bold gradient-text font-heading">CJ Consultoría</h1>
        <p class="text-cj-silver font-tagline mt-1 italic">"Transformamos procesos en resultados sostenibles"</p>
      </div>

      <!-- Register Form -->
      <div class="card border-cj-silver/20">
        <h2 class="text-xl font-semibold mb-2 text-white font-heading">Crear Cuenta</h2>
        <p class="text-cj-silver text-sm mb-6">Gestión profesional de inventarios para tu empresa</p>
        
        <form @submit.prevent="handleRegister" class="space-y-4">
          <!-- Nombre de la empresa -->
          <div>
            <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">
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
            <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">
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
            <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">
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
            <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">
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
                class="absolute right-3 top-1/2 -translate-y-1/2 text-cj-gray hover:text-cj-silver transition-colors"
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
            <p class="text-xs text-cj-gray mt-1">Debe tener al menos 8 caracteres</p>
          </div>

          <!-- Confirmar contraseña -->
          <div>
            <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">
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
              class="mt-1 rounded border-cj-navy-light bg-cj-navy text-cj-electric focus:ring-cj-electric focus:ring-offset-cj-navy" 
            />
            <span class="text-sm text-cj-gray group-hover:text-cj-silver transition-colors">
              Acepto los <a href="#" class="text-cj-electric hover:underline">Términos de Servicio</a> y la 
              <a href="#" class="text-cj-electric hover:underline">Política de Privacidad</a>
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
            class="w-full btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg v-if="authStore.loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ authStore.loading ? 'Creando cuenta...' : 'Crear Cuenta' }}</span>
          </button>
        </form>

        <!-- Link a login -->
        <div class="mt-6 text-center text-sm text-cj-gray">
          ¿Ya tienes cuenta?
          <router-link to="/login" class="text-cj-electric hover:text-cj-electric-light font-medium ml-1 transition-colors font-heading">
            Inicia sesión
          </router-link>
        </div>
      </div>

      <!-- Footer -->
      <div class="mt-8 text-center">
        <div class="flex items-center justify-center gap-4 mb-4">
          <img src="/logo-cj.png" alt="CJ" class="h-8 w-auto opacity-60" />
        </div>
        <p class="text-xs text-cj-gray">
          © 2026 CJ Consultoría. Todos los derechos reservados.
        </p>
        <p class="text-xs text-cj-gray-dark mt-1 font-tagline italic">
          "Estrategia, Orden y Resultados para tu empresa"
        </p>
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
  authStore.clearError()
  successMessage.value = ''

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
    
    successMessage.value = '¡Cuenta creada exitosamente! Redirigiendo...'
    
    setTimeout(() => {
      router.push('/dashboard')
    }, 1500)
    
  } catch (err) {
    console.error('Register error:', err)
  }
}
</script>
