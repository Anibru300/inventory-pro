<template>
  <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Animated background -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-blue-50/50 to-indigo-50"></div>
    
    <!-- Floating animated elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-20 -right-20 w-96 h-96 bg-blue-400/20 rounded-full blur-3xl animate-float-slow"></div>
      <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-indigo-400/20 rounded-full blur-3xl animate-float-delayed"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-purple-300/10 rounded-full blur-3xl animate-pulse-slow"></div>
    </div>
    
    <!-- Decorative grid pattern -->
    <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(circle, #64748b 1px, transparent 1px); background-size: 24px 24px;"></div>
    
    <div class="w-full max-w-md relative z-10">
      <!-- Logo with animation -->
      <div class="text-center mb-8 animate-fade-in-down">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl mb-4 shadow-xl shadow-blue-600/25 transform hover:scale-105 transition-transform duration-300">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-slate-800 mb-2 tracking-tight">Inventory Pro</h1>
        <p class="text-slate-500">Sistema ERP de Gestión de Inventarios</p>
      </div>

      <!-- Login Form Card -->
      <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-slate-200/50 p-8 border border-white/50 animate-fade-in-up">
        <div class="text-center mb-8">
          <h2 class="text-xl font-bold text-slate-800">Bienvenido de nuevo</h2>
          <p class="text-slate-500 text-sm mt-1">Ingresa tus credenciales para continuar</p>
        </div>
        
        <form @submit.prevent="handleLogin" class="space-y-5">
          <InputAnimated
            v-model="form.email"
            type="email"
            label="Correo electrónico"
            placeholder="tu@empresa.com"
            required
            :icon="EnvelopeIcon"
            :error="emailError"
          />

          <InputAnimated
            v-model="form.password"
            type="password"
            label="Contraseña"
            placeholder="••••••••"
            required
            :error="passwordError"
          />

          <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 cursor-pointer group">
              <input v-model="form.remember" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-colors" />
              <span class="text-slate-600 group-hover:text-slate-800 transition-colors">Recordarme</span>
            </label>
            <router-link to="/forgot-password" class="text-blue-600 hover:text-blue-700 font-medium hover:underline transition-all">¿Olvidaste tu contraseña?</router-link>
          </div>

          <!-- Error message with animation -->
          <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform -translate-y-2 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform -translate-y-2 opacity-0"
          >
            <div v-if="authStore.error" class="flex items-center gap-2 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl text-sm">
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>{{ authStore.error }}</span>
            </div>
          </transition>

          <ButtonAnimated
            type="submit"
            :loading="authStore.loading"
            block
            size="lg"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            Iniciar Sesión
          </ButtonAnimated>
        </form>

        <div class="relative my-8">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-slate-200"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-slate-500">o continúa con</span>
          </div>
        </div>

        <GoogleLoginButton @error="(msg) => authStore.error = msg" />

        <div class="mt-8 text-center">
          <p class="text-slate-500 text-sm">
            ¿No tienes una cuenta?
            <router-link to="/register" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline transition-all ml-1">
              Crear cuenta
            </router-link>
          </p>
        </div>
      </div>
      
      <!-- Footer -->
      <p class="text-center text-xs text-slate-400 mt-8 animate-fade-in">
        © 2026 Inventory Pro. Todos los derechos reservados.
      </p>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import GoogleLoginButton from '@/components/GoogleLoginButton.vue'
import { InputAnimated, ButtonAnimated } from '@/components/ui'
import { h } from 'vue'

const authStore = useAuthStore()

// Simple envelope icon component using render function
const EnvelopeIcon = {
  render() {
    return h('svg', {
      class: 'w-5 h-5',
      fill: 'none',
      stroke: 'currentColor',
      viewBox: '0 0 24 24'
    }, [
      h('path', {
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        'stroke-width': '2',
        d: 'M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207'
      })
    ])
  }
}

const form = reactive({
  email: '',
  password: '',
  remember: false
})

const emailError = ref('')
const passwordError = ref('')

const handleLogin = async () => {
  // Reset errors
  emailError.value = ''
  passwordError.value = ''
  
  // Basic validation
  if (!form.email) {
    emailError.value = 'El correo electrónico es requerido'
    return
  }
  if (!form.password) {
    passwordError.value = 'La contraseña es requerida'
    return
  }
  
  await authStore.login(form)
}
</script>

<style scoped>
@keyframes float-slow {
  0%, 100% { transform: translate(0, 0) scale(1); }
  50% { transform: translate(20px, -20px) scale(1.05); }
}

@keyframes float-delayed {
  0%, 100% { transform: translate(0, 0) scale(1); }
  50% { transform: translate(-20px, 20px) scale(1.05); }
}

@keyframes pulse-slow {
  0%, 100% { opacity: 0.1; transform: scale(1); }
  50% { opacity: 0.2; transform: scale(1.1); }
}

.animate-float-slow {
  animation: float-slow 20s ease-in-out infinite;
}

.animate-float-delayed {
  animation: float-delayed 25s ease-in-out infinite;
  animation-delay: -5s;
}

.animate-pulse-slow {
  animation: pulse-slow 15s ease-in-out infinite;
}

@keyframes fade-in-down {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-down {
  animation: fade-in-down 0.6s ease-out;
}

.animate-fade-in-up {
  animation: fade-in-up 0.6s ease-out 0.2s both;
}

.animate-fade-in {
  animation: fade-in-down 0.6s ease-out 0.4s both;
}
</style>
