<template>
  <div>
    <!-- Header -->
    <div class="mb-8">
      <div class="flex items-center gap-4">
        <button 
          @click="$router.push('/menu')"
          class="flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm"
          title="Volver al menú"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
        </button>
        <div>
          <h1 class="text-3xl font-bold gradient-text font-heading mb-2">Configuración</h1>
          <p class="text-cj-silver-dark font-tagline italic">Personaliza tu sistema de inventario</p>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Company Settings -->
      <div class="lg:col-span-2">
        <div class="card-premium p-6 mb-6">
          <h3 class="text-lg font-semibold mb-4 font-heading flex items-center gap-2">
            <span class="w-1 h-5 bg-cj-gold rounded-full"></span>
            Datos de la Empresa
          </h3>
          <form class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-2 text-cj-silver">Nombre de la Empresa</label>
              <input type="text" :value="authStore.tenant?.name" class="w-full" />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2 text-cj-silver">Email</label>
                <input type="email" :value="authStore.tenant?.email" class="w-full" />
              </div>
              <div>
                <label class="block text-sm font-medium mb-2 text-cj-silver">Teléfono</label>
                <input type="tel" :value="authStore.tenant?.phone" class="w-full" />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-cj-silver">Dirección</label>
              <textarea rows="2" :value="authStore.tenant?.address" class="w-full"></textarea>
            </div>
            <div class="pt-4">
              <button type="button" class="btn-primary">Guardar Cambios</button>
            </div>
          </form>
        </div>

        <!-- User Settings -->
        <div class="card-premium p-6">
          <h3 class="text-lg font-semibold mb-4 font-heading flex items-center gap-2">
            <span class="w-1 h-5 bg-cj-gold rounded-full"></span>
            Mi Perfil
          </h3>
          <form class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2 text-cj-silver">Nombre</label>
                <input type="text" :value="authStore.user?.first_name" class="w-full" />
              </div>
              <div>
                <label class="block text-sm font-medium mb-2 text-cj-silver">Apellido</label>
                <input type="text" :value="authStore.user?.last_name" class="w-full" />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-cj-silver">Email</label>
              <input type="email" :value="authStore.user?.email" class="w-full" disabled />
            </div>
            <div class="pt-4">
              <button type="button" class="btn-primary">Actualizar Perfil</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Sidebar Settings -->
      <div class="space-y-6">
        <!-- Plan Info -->
        <div class="card-premium p-6">
          <h3 class="text-lg font-semibold mb-4 font-heading">Tu Plan</h3>
          <div class="text-center">
            <span class="inline-block px-4 py-2 bg-cj-gold/10 text-cj-gold border border-cj-gold/20 rounded-full text-sm font-bold uppercase">
              {{ authStore.tenant?.plan }}
            </span>
            <p class="mt-4 text-sm text-cj-silver-dark">
              Prueba gratuita hasta:<br />
              <span class="text-cj-silver">{{ formatDate(authStore.tenant?.trial_ends_at) }}</span>
            </p>
            <button class="btn-primary w-full mt-4 text-sm">Actualizar Plan</button>
          </div>
        </div>

        <!-- Preferences -->
        <div class="card-premium p-6">
          <h3 class="text-lg font-semibold mb-4 font-heading">Preferencias</h3>
          <div class="space-y-4">
            <label class="flex items-center justify-between cursor-pointer">
              <span class="text-cj-silver">Notificaciones por email</span>
              <input type="checkbox" checked class="rounded border-white/20 bg-white/5 text-cj-gold focus:ring-cj-gold" />
            </label>
            <label class="flex items-center justify-between cursor-pointer">
              <span class="text-cj-silver">Alertas de stock bajo</span>
              <input type="checkbox" checked class="rounded border-white/20 bg-white/5 text-cj-gold focus:ring-cj-gold" />
            </label>
            <label class="flex items-center justify-between cursor-pointer">
              <span class="text-cj-silver">Modo oscuro</span>
              <input type="checkbox" checked disabled class="rounded border-white/20 bg-white/5 text-cj-gold focus:ring-cj-gold" />
            </label>
          </div>
        </div>

        <!-- Danger Zone -->
        <div class="card-premium p-6 border-danger/30">
          <h3 class="text-lg font-semibold mb-4 font-heading text-danger">Zona de Peligro</h3>
          <button class="btn-danger w-full text-sm">Eliminar Cuenta</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '../../stores/auth'

const authStore = useAuthStore()

function formatDate(date) {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('es-ES', { day: '2-digit', month: 'long', year: 'numeric' })
}
</script>
