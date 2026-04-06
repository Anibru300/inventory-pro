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

      <!-- Register Form -->
      <div class="card">
        <h2 class="text-xl font-semibold mb-2">Crear Cuenta</h2>
        <p class="text-text-secondary text-sm mb-6">Comienza tu prueba gratuita de 14 días</p>
        
        <form @submit.prevent="handleRegister" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2">Nombre de tu empresa *</label>
            <input
              v-model="form.company_name"
              type="text"
              required
              placeholder="Mi Empresa S.A."
              class="w-full bg-bg-tertiary border border-border-default rounded-lg px-4 py-3 focus:outline-none focus:border-accent-primary transition-colors"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Tu nombre completo *</label>
            <input
              v-model="form.name"
              type="text"
              required
              placeholder="Juan Pérez"
              class="w-full bg-bg-tertiary border border-border-default rounded-lg px-4 py-3 focus:outline-none focus:border-accent-primary transition-colors"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Email corporativo *</label>
            <input
              v-model="form.email"
              type="email"
              required
              placeholder="tu@empresa.com"
              class="w-full bg-bg-tertiary border border-border-default rounded-lg px-4 py-3 focus:outline-none focus:border-accent-primary transition-colors"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Contraseña *</label>
            <input
              v-model="form.password"
              type="password"
              required
              minlength="8"
              placeholder="Mínimo 8 caracteres"
              class="w-full bg-bg-tertiary border border-border-default rounded-lg px-4 py-3 focus:outline-none focus:border-accent-primary transition-colors"
            />
            <p class="text-xs text-text-tertiary mt-1">Mínimo 8 caracteres</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Confirmar contraseña *</label>
            <input
              v-model="form.password_confirmation"
              type="password"
              required
              placeholder="Repite tu contraseña"
              class="w-full bg-bg-tertiary border border-border-default rounded-lg px-4 py-3 focus:outline-none focus:border-accent-primary transition-colors"
            />
          </div>

          <label class="flex items-start gap-2 cursor-pointer">
            <input v-model="form.terms" type="checkbox" required class="mt-1 rounded border-border-default bg-bg-tertiary text-accent-primary focus:ring-accent-primary" />
            <span class="text-sm text-text-secondary">
              Acepto los <a href="#" class="text-accent-primary hover:underline">Términos de Servicio</a> y la 
              <a href="#" class="text-accent-primary hover:underline">Política de Privacidad</a>
            </span>
          </label>

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
            <span>{{ authStore.loading ? 'Creando cuenta...' : 'Crear Cuenta Gratis' }}</span>
          </button>
        </form>

        <div class="mt-6 text-center text-sm text-text-secondary">
          ¿Ya tienes cuenta?
          <router-link to="/login" class="text-accent-primary hover:text-accent-secondary font-medium ml-1">
            Inicia sesión
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  company_name: '',
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
})

async function handleRegister() {
  if (form.password !== form.password_confirmation) {
    authStore.error = 'Las contraseñas no coinciden'
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
    router.push('/')
  } catch (err) {
    console.error('Register error:', err)
  }
}
</script>