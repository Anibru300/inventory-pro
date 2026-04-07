<template>
  <div class="min-h-screen flex items-center justify-center p-4 relative">
    <!-- Decorative elements -->
    <div class="absolute top-20 left-10 w-72 h-72 bg-cj-electric/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-cj-silver/5 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="w-full max-w-md relative z-10">
      <!-- Logo -->
      <div class="text-center mb-8">
        <div class="flex items-center justify-center mb-4">
          <img 
            src="/logo-lobo.png" 
            alt="CJ Consultoría" 
            class="h-24 w-auto logo-glow"
          />
        </div>
        <h1 class="text-3xl font-bold gradient-text font-heading tracking-tight">CJ Consultoría</h1>
        <p class="text-cj-silver/80 font-tagline mt-2 italic text-lg">"Transformamos procesos en resultados sostenibles"</p>
      </div>

      <!-- Login Form -->
      <div class="card-modern p-8">
        <h2 class="text-xl font-semibold mb-2 text-white font-heading">Bienvenido de vuelta</h2>
        <p class="text-cj-gray text-sm mb-6">Ingresa tus credenciales para continuar</p>
        
        <form @submit.prevent="handleLogin" class="space-y-5">
          <!-- Email -->
          <div>
            <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">
              Correo electrónico
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
              Contraseña
            </label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                placeholder="••••••••"
                class="w-full pr-12"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-cj-gray hover:text-cj-silver transition-colors"
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
          </div>

          <!-- Opciones -->
          <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 cursor-pointer group">
              <input 
                v-model="form.remember" 
                type="checkbox" 
                class="rounded border-white/20 bg-white/5 text-cj-electric focus:ring-cj-electric focus:ring-offset-cj-navy" 
              />
              <span class="text-cj-gray group-hover:text-cj-silver transition-colors">Recordarme</span>
            </label>
            <a href="#" class="text-cj-electric hover:text-cj-electric-light transition-colors text-sm font-heading">
              ¿Olvidaste tu contraseña?
            </a>
          </div>

          <!-- Error message -->
          <div v-if="authStore.error" class="p-4 bg-danger/10 border border-danger/30 rounded-xl text-danger text-sm flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ authStore.error }}</span>
          </div>

          <!-- Botón de login -->
          <button
            type="submit"
            :disabled="authStore.loading"
            class="w-full btn-primary disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
          >
            <svg v-if="authStore.loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ authStore.loading ? 'Iniciando sesión...' : 'Iniciar Sesión' }}</span>
          </button>
        </form>

        <!-- Divider -->
        <div class="relative my-8">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-white/10"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-cj-navy text-cj-gray">¿No tienes cuenta?</span>
          </div>
        </div>

        <!-- Link a registro -->
        <router-link 
          to="/register" 
          class="w-full btn-secondary block text-center"
        >
          Crear cuenta nueva
        </router-link>
      </div>

      <!-- Footer -->
      <div class="mt-8 text-center">
        <div class="flex items-center justify-center gap-4 mb-4">
          <img src="/logo-cj.png" alt="CJ" class="h-6 w-auto opacity-40" />
        </div>
        <p class="text-xs text-cj-gray/60">
          © 2026 CJ Consultoría. Todos los derechos reservados.
        </p>
        <p class="text-xs text-cj-gray/40 mt-1 font-tagline italic">
          "Estrategia, Orden y Resultados"
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

const form = reactive({
  email: '',
  password: '',
  remember: false,
})

async function handleLogin() {
  authStore.clearError()

  try {
    await authStore.login({
      email: form.email,
      password: form.password,
    })
    
    router.push('/dashboard')
  } catch (err) {
    console.error('Login error:', err)
  }
}
</script>
