<template>
  <div class="min-h-screen flex items-center justify-center p-4 relative">
    <!-- Decorative elements -->
    <div class="absolute top-10 left-10 w-64 h-64 bg-cj-gold/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-80 h-80 bg-cj-blue/30 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="w-full max-w-md relative z-10">
      <!-- Logo -->
      <div class="text-center mb-8">
        <div class="flex items-center justify-center mb-6">
          <img src="/logo-lobo.png" alt="CJ Consultoría" class="h-28 w-auto logo-glow" />
        </div>
        <h1 class="text-3xl font-bold gradient-text font-heading tracking-tight mb-2">CJ Consultoría</h1>
        <p class="text-cj-silver-dark font-tagline italic text-lg">"Excelencia en gestión empresarial"</p>
      </div>

      <!-- Login Form -->
      <div class="card-premium p-8">
        <h2 class="text-xl font-semibold mb-2 text-white font-heading">Iniciar Sesión</h2>
        <p class="text-cj-silver-dark text-sm mb-6">Accede a tu sistema de inventario</p>
        
        <form @submit.prevent="handleLogin" class="space-y-5">
          <div>
            <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">Correo electrónico</label>
            <input v-model="form.email" type="email" required placeholder="tu@empresa.com" class="w-full" />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2 text-cj-silver font-heading">Contraseña</label>
            <div class="relative">
              <input v-model="form.password" :type="showPassword ? 'text' : 'password'" required placeholder="••••••••" class="w-full pr-12" />
              <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-cj-silver-dark hover:text-cj-silver transition-colors">
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
            <label class="flex items-center gap-2 cursor-pointer group">
              <input v-model="form.remember" type="checkbox" class="rounded border-white/20 bg-white/5 text-cj-gold focus:ring-cj-gold focus:ring-offset-cj-navy" />
              <span class="text-cj-silver-dark group-hover:text-cj-silver transition-colors">Recordarme</span>
            </label>
            <a href="#" class="text-cj-gold hover:text-cj-gold-light transition-colors text-sm font-heading">¿Olvidaste tu contraseña?</a>
          </div>

          <div v-if="authStore.error" class="p-4 bg-danger/10 border border-danger/30 rounded-xl text-danger text-sm flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ authStore.error }}</span>
          </div>

          <button type="submit" :disabled="authStore.loading" class="w-full btn-primary disabled:opacity-50">
            <svg v-if="authStore.loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ authStore.loading ? 'Iniciando...' : 'Iniciar Sesión' }}</span>
          </button>
        </form>

        <div class="relative my-8">
          <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-white/10"></div></div>
          <div class="relative flex justify-center text-sm"><span class="px-4 bg-cj-navy text-cj-silver-dark">¿Nuevo usuario?</span></div>
        </div>

        <router-link to="/register" class="w-full btn-secondary block text-center">Crear cuenta nueva</router-link>
      </div>

      <div class="mt-8 text-center">
        <img src="/logo-cj.png" alt="CJ" class="h-6 w-auto mx-auto opacity-30 mb-3" />
        <p class="text-xs text-cj-silver-dark/60">© 2026 CJ Consultoría. Todos los derechos reservados.</p>
        <p class="text-xs text-cj-gold/40 mt-1 font-tagline italic">"Transformamos procesos en resultados sostenibles"</p>
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

const form = reactive({ email: '', password: '', remember: false })

async function handleLogin() {
  authStore.clearError()
  try {
    await authStore.login({ email: form.email, password: form.password })
    router.push('/dashboard')
  } catch (err) {
    console.error('Login error:', err)
  }
}
</script>
