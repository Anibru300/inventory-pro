<template>
  <div class="min-h-screen flex items-center justify-center p-4 bg-silver-50">
    <!-- Decorative background elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-electric/20 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-semantic-success/10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="w-full max-w-lg relative z-10">
      <!-- Logo -->
      <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-electric rounded-xl mb-3 shadow-lg shadow-electric/20">
          <svg class="w-7 h-7 text-silver-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-silver-900 mb-1">Crear Cuenta</h1>
        <p class="text-silver-600 text-sm">Gestión profesional de inventarios para tu empresa</p>
      </div>

      <!-- Register Form -->
      <div class="card p-8 shadow-card">
        <!-- Progress steps -->
        <div class="flex items-center justify-center gap-2 mb-6">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-electric text-silver-900 flex items-center justify-center text-sm font-semibold">1</div>
            <span class="text-sm font-medium text-silver-900">Registro</span>
          </div>
          <div class="w-12 h-px bg-silver-200"></div>
          <div class="flex items-center gap-2 opacity-50">
            <div class="w-8 h-8 rounded-full bg-silver-200 text-silver-600 flex items-center justify-center text-sm font-semibold">2</div>
            <span class="text-sm text-silver-600">Verificación</span>
          </div>
        </div>
        
        <form @submit.prevent="handleRegister" class="space-y-5">
          <!-- Company name -->
          <div>
            <label class="form-label">
              Nombre de tu empresa <span class="text-semantic-error">*</span>
            </label>
            <input
              v-model="form.company_name"
              type="text"
              required
              placeholder="Ej: Mi Empresa S.A."
              class="w-full"
            />
          </div>

          <!-- Name -->
          <div>
            <label class="form-label">
              Tu nombre completo <span class="text-semantic-error">*</span>
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
            <label class="form-label">
              Email corporativo <span class="text-semantic-error">*</span>
            </label>
            <input
              v-model="form.email"
              type="email"
              required
              placeholder="tu@empresa.com"
              class="w-full"
            />
          </div>

          <!-- Password -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="form-label">
                Contraseña <span class="text-semantic-error">*</span>
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
                  class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-silver-500 hover:text-silver-700"
                >
                  <svg v-if="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                  </svg>
                  <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </div>
            </div>

            <div>
              <label class="form-label">
                Confirmar <span class="text-semantic-error">*</span>
              </label>
              <input
                v-model="form.password_confirmation"
                :type="showPassword ? 'text' : 'password'"
                required
                placeholder="Repite contraseña"
                class="w-full"
              />
            </div>
          </div>

          <!-- Terms -->
          <label class="flex items-start gap-3 cursor-pointer group">
            <input 
              v-model="form.terms" 
              type="checkbox" 
              required 
              class="mt-1 rounded border-silver-300 text-electric focus:ring-electric" 
            />
            <span class="text-sm text-silver-600 group-hover:text-silver-700 transition-colors">
              Acepto los <a href="#" class="text-electric-dark hover:text-electric font-medium">Términos de Servicio</a> y la 
              <a href="#" class="text-electric-dark hover:text-electric font-medium">Política de Privacidad</a>
            </span>
          </label>

          <!-- Error message -->
          <div v-if="authStore.error" class="toast toast-error">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ authStore.error }}</span>
          </div>

          <!-- Success message -->
          <div v-if="successMessage" class="toast toast-success">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ successMessage }}</span>
          </div>

          <!-- Submit button -->
          <button
            type="submit"
            :disabled="authStore.loading || !form.terms"
            class="btn btn-primary w-full"
            :class="{ 'opacity-50 cursor-not-allowed': authStore.loading || !form.terms }"
          >
            <svg v-if="authStore.loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ authStore.loading ? 'Creando cuenta...' : 'Crear Cuenta Gratis' }}</span>
          </button>
        </form>

        <!-- Link to login -->
        <div class="mt-6 text-center text-sm text-silver-600">
          ¿Ya tienes cuenta?
          <router-link to="/login" class="text-electric-dark hover:text-electric font-medium ml-1 transition-colors">
            Inicia sesión
          </router-link>
        </div>
      </div>

      <!-- Features -->
      <div class="mt-8 grid grid-cols-3 gap-4 text-center">
        <div class="p-4">
          <div class="w-10 h-10 bg-semantic-success/10 rounded-lg flex items-center justify-center mx-auto mb-2">
            <svg class="w-5 h-5 text-semantic-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <p class="text-xs text-silver-600">Prueba gratuita 14 días</p>
        </div>
        <div class="p-4">
          <div class="w-10 h-10 bg-semantic-info/10 rounded-lg flex items-center justify-center mx-auto mb-2">
            <svg class="w-5 h-5 text-semantic-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </div>
          <p class="text-xs text-silver-600">Datos seguros</p>
        </div>
        <div class="p-4">
          <div class="w-10 h-10 bg-electric-muted rounded-lg flex items-center justify-center mx-auto mb-2">
            <svg class="w-5 h-5 text-electric-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <p class="text-xs text-silver-600">Soporte técnico</p>
        </div>
      </div>

      <!-- Footer -->
      <div class="mt-6 text-center">
        <p class="text-xs text-silver-500">© 2026 Inventory Pro. Todos los derechos reservados.</p>
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
