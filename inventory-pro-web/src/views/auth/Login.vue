<template>
  <div class="min-h-screen bg-bg-primary flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-br from-accent-primary to-accent-secondary rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
        </div>
        <h1 class="text-2xl font-bold gradient-text">Inventory Pro</h1>
        <p class="text-text-secondary mt-1">Gestión inteligente de inventarios</p>
      </div>

      <!-- Login Form -->
      <div class="card">
        <h2 class="text-xl font-semibold mb-6">Iniciar Sesión</h2>
        
        <form @submit.prevent="handleLogin" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input
              v-model="form.email"
              type="email"
              required
              placeholder="tu@empresa.com"
              class="w-full bg-bg-tertiary border border-border-default rounded-lg px-4 py-3 focus:outline-none focus:border-accent-primary transition-colors"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Contraseña</label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                placeholder="••••••••"
                class="w-full bg-bg-tertiary border border-border-default rounded-lg px-4 py-3 pr-10 focus:outline-none focus:border-accent-primary transition-colors"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-text-tertiary hover:text-text-secondary"
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

          <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 cursor-pointer">
              <input v-model="form.remember" type="checkbox" class="rounded border-border-default bg-bg-tertiary text-accent-primary focus:ring-accent-primary" />
              <span class="text-text-secondary">Recordarme</span>
            </label>
            <a href="#" class="text-accent-primary hover:text-accent-secondary transition-colors">
              ¿Olvidaste tu contraseña?
            </a>
          </div>

          <div v-if="authStore.error" class="p-3 bg-danger/10 border border-danger/30 rounded-lg text-danger text-sm">
            {{ authStore.error }}
          </div>

          <button
            type="submit"
            :disabled="authStore.loading"
            class="w-full bg-accent-primary hover:bg-accent-secondary text-white font-medium py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg v-if="authStore.loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ authStore.loading ? 'Iniciando sesión...' : 'Iniciar Sesión' }}</span>
          </button>
        </form>

        <div class="mt-6 text-center text-sm text-text-secondary">
          ¿No tienes cuenta?
          <router-link to="/register" class="text-accent-primary hover:text-accent-secondary font-medium ml-1">
            Regístrate gratis
          </router-link>
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

const form = reactive({
  email: '',
  password: '',
  remember: false,
})

async function handleLogin() {
  try {
    await authStore.login({
      email: form.email,
      password: form.password,
    })
    router.push('/')
  } catch (err) {
    console.error('Login error:', err)
  }
}
</script>