<template>
  <div class="min-h-screen" :class="isDark ? 'bg-[#0B1F3A]' : 'bg-gray-50'">
    <!-- Header -->
    <nav class="w-full z-50" :class="isDark ? 'bg-[#0B1F3A]/95 border-b border-[#2E7DE8]/20' : 'bg-white shadow'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <router-link to="/" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg overflow-hidden border-2 border-[#C0C0C0]">
              <img src="/logo-cj.png" alt="CJ Consultoría" class="w-full h-full object-cover" />
            </div>
            <span class="text-xl font-bold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Inventory Pro</span>
          </router-link>
          <button @click="toggleDarkMode" class="p-2 rounded-lg transition-colors" :class="isDark ? 'text-gray-300 hover:bg-[#1a3050]' : 'text-gray-600 hover:bg-gray-100'">
            <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
          </button>
        </div>
      </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-12">
      <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
          Completa tu Suscripción
        </h1>
        <p class="text-lg" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
          Estás suscribiéndote al plan <span class="font-semibold text-[#2E7DE8]">{{ planName }}</span>
        </p>
        <div v-if="hasTrial" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
          </svg>
          🎁 1 mes de prueba GRATIS
        </div>
      </div>

      <div class="grid md:grid-cols-2 gap-8">
        <!-- Plan Summary -->
        <div class="rounded-2xl p-6 border backdrop-blur-sm h-fit"
          :class="isDark ? 'bg-[#0B1F3A]/60 border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
          <h2 class="text-xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Resumen del Plan</h2>
          
          <div class="space-y-4">
            <div class="flex justify-between items-center py-3 border-b" :class="isDark ? 'border-gray-700' : 'border-gray-100'">
              <span :class="isDark ? 'text-gray-400' : 'text-gray-600'">Plan</span>
              <span class="font-semibold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">{{ planName }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b" :class="isDark ? 'border-gray-700' : 'border-gray-100'">
              <span :class="isDark ? 'text-gray-400' : 'text-gray-600'">Precio mensual</span>
              <span class="font-semibold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">${{ planPrice }}/mes</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b" :class="isDark ? 'border-gray-700' : 'border-gray-100'">
              <span :class="isDark ? 'text-gray-400' : 'text-gray-600'">Almacenes</span>
              <span class="font-semibold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">{{ planLimits.warehouses }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b" :class="isDark ? 'border-gray-700' : 'border-gray-100'">
              <span :class="isDark ? 'text-gray-400' : 'text-gray-600'">Productos</span>
              <span class="font-semibold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">{{ planLimits.products }}</span>
            </div>
            <div class="flex justify-between items-center py-3">
              <span :class="isDark ? 'text-gray-400' : 'text-gray-600'">Usuarios</span>
              <span class="font-semibold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">{{ planLimits.users }}</span>
            </div>
          </div>

          <div v-if="hasTrial" class="mt-6 p-4 bg-emerald-50 rounded-xl border border-emerald-200">
            <p class="text-sm text-emerald-700">
              <strong>Prueba gratis:</strong> No se te cobrará nada durante el primer mes. 
              Puedes cancelar en cualquier momento antes de que termine la prueba.
            </p>
          </div>

          <div v-else class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
            <p class="text-sm text-blue-700">
              <strong>Suscripción mensual:</strong> Se te cobrará ${{ planPrice }} cada mes. 
              Puedes cancelar en cualquier momento.
            </p>
          </div>
        </div>

        <!-- Transfer Payment Form -->
        <div class="rounded-2xl p-6 border backdrop-blur-sm"
          :class="isDark ? 'bg-[#0B1F3A]/60 border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
          
          <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-xl bg-[#2E7DE8]/10 flex items-center justify-center">
              <svg class="w-6 h-6 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Pago por Transferencia</h2>
              <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Activa tu cuenta en 24-48 horas</p>
            </div>
          </div>

          <div class="p-4 rounded-xl border mb-6" :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-gray-50 border-gray-200'">
            <h3 class="font-semibold mb-4 flex items-center gap-2" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">
              <svg class="w-5 h-5 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              Datos Bancarios
            </h3>
            <div class="space-y-3 text-sm">
              <div class="flex justify-between items-center py-2 border-b border-dashed" :class="isDark ? 'border-gray-700' : 'border-gray-300'">
                <span :class="isDark ? 'text-gray-400' : 'text-gray-600'">Institución:</span>
                <span class="font-medium" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Mercado Pago W</span>
              </div>
              <div class="flex justify-between items-center py-2 border-b border-dashed" :class="isDark ? 'border-gray-700' : 'border-gray-300'">
                <span :class="isDark ? 'text-gray-400' : 'text-gray-600'">CLABE:</span>
                <div class="flex items-center gap-2">
                  <span class="font-mono font-medium text-lg" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">722969020205392763</span>
                  <button @click="copyClabe" class="p-1 rounded hover:bg-[#2E7DE8]/20 transition-colors" title="Copiar CLABE">
                    <svg class="w-4 h-4 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                  </button>
                </div>
              </div>
              <div class="flex justify-between items-center py-2 border-b border-dashed" :class="isDark ? 'border-gray-700' : 'border-gray-300'">
                <span :class="isDark ? 'text-gray-400' : 'text-gray-600'">Beneficiario:</span>
                <span class="font-medium" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Maria Jimena Mena Prado</span>
              </div>
              <div class="flex justify-between items-center py-2">
                <span :class="isDark ? 'text-gray-400' : 'text-gray-600'">Concepto:</span>
                <span class="font-mono font-bold text-[#2E7DE8] bg-[#2E7DE8]/10 px-3 py-1 rounded-lg">INVPRO</span>
              </div>
            </div>
          </div>

          <form @submit.prevent="handleTransferSubmit" class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">
                Monto a transferir
              </label>
              <div class="px-4 py-3 rounded-xl border font-semibold text-lg flex items-center justify-between"
                :class="isDark ? 'bg-[#0B1F3A] border-gray-700 text-white' : 'bg-gray-100 border-gray-300 text-[#0B1F3A]'">
                <span>${{ planPrice }}.00 MXN</span>
                <span class="text-sm font-normal" :class="isDark ? 'text-gray-400' : 'text-gray-500'">IVA incluido</span>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">
                Referencia de transferencia <span class="text-red-500">*</span>
              </label>
              <input 
                v-model="transferForm.reference"
                type="text" 
                required
                class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] focus:border-transparent transition-all"
                :class="isDark ? 'bg-[#0B1F3A] border-gray-700 text-white' : 'bg-white border-gray-300'"
                placeholder="Ej: INVPRO-001">
              <p class="text-xs mt-1" :class="isDark ? 'text-gray-500' : 'text-gray-400'">
                Ingresa la referencia que usaste al hacer la transferencia
              </p>
            </div>

            <div class="pt-4">
              <button 
                type="submit"
                :disabled="loading"
                class="w-full py-4 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] hover:from-[#1e6ad1] hover:to-[#2E7DE8] text-white rounded-xl font-semibold transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <svg v-if="loading" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Confirmar Transferencia</span>
              </button>
            </div>

            <div class="flex items-center gap-2 text-sm p-3 rounded-lg" :class="isDark ? 'bg-amber-500/10 text-amber-400' : 'bg-amber-50 text-amber-700'">
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Tu cuenta será activada en <strong>24-48 horas hábiles</strong> una vez confirmado el pago.</span>
            </div>

            <p class="text-xs text-center" :class="isDark ? 'text-gray-500' : 'text-gray-400'">
              ¿Tienes dudas? <button type="button" @click="openWhatsApp" class="text-[#2E7DE8] hover:underline">Contáctanos por WhatsApp</button>
            </p>
          </form>
        </div>
      </div>
    </div>

    <!-- Transfer Pending Modal -->
    <div v-if="showTransferModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
      <div class="rounded-2xl p-8 max-w-md w-full text-center shadow-2xl"
        :class="isDark ? 'bg-[#0B1F3A] border border-[#2E7DE8]/20' : 'bg-white'">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-emerald-100 flex items-center justify-center">
          <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <h3 class="text-2xl font-bold mb-2" :class="isDark ? 'text-white' : 'text-gray-900'">¡Solicitud Recibida!</h3>
        <p class="mb-6" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
          Hemos recibido tu solicitud. Tu cuenta será activada en <strong>24-48 horas hábiles</strong> una vez confirmado el pago.
        </p>
        <div class="p-4 rounded-xl mb-6 text-left" :class="isDark ? 'bg-[#1a3050]' : 'bg-gray-100'">
          <p class="text-sm mb-1" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Referencia:</p>
          <p class="font-mono font-semibold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">{{ transferForm.reference }}</p>
        </div>
        <div class="flex gap-3">
          <router-link to="/" class="flex-1 px-4 py-3 border rounded-xl font-semibold transition-all" :class="isDark ? 'border-gray-600 text-gray-300 hover:bg-gray-800' : 'border-gray-300 text-gray-700 hover:bg-gray-50'">
            Volver al Inicio
          </router-link>
          <button @click="openWhatsApp" class="flex-1 px-4 py-3 bg-[#25D366] hover:bg-[#128C7E] text-white rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            WhatsApp
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useDarkMode } from '../composables/useDarkMode'
import axios from 'axios'

const { isDark, toggleDarkMode } = useDarkMode()
const route = useRoute()
const router = useRouter()

const plan = computed(() => route.query.plan || 'professional')
const hasTrial = computed(() => route.query.trial === '1' || plan.value === 'professional')

const planName = computed(() => {
  return {
    'professional': 'Profesional',
    'unlimited': 'Ilimitado'
  }[plan.value] || 'Profesional'
})

const planPrice = computed(() => {
  return {
    'professional': '299',
    'unlimited': '799'
  }[plan.value] || '299'
})

const planLimits = computed(() => {
  return {
    'professional': { warehouses: '10', products: '500', users: '5' },
    'unlimited': { warehouses: 'Ilimitados', products: 'Ilimitados', users: 'Ilimitados' }
  }[plan.value] || { warehouses: '10', products: '500', users: '5' }
})

const loading = ref(false)
const showTransferModal = ref(false)

const transferForm = ref({
  reference: ''
})

const copyClabe = () => {
  navigator.clipboard.writeText('722969020205392763')
  alert('CLABE copiada al portapapeles')
}

const openWhatsApp = () => {
  const phone = '524776940272'
  const message = encodeURIComponent('Hola Carlos, tengo una duda sobre mi suscripción a Inventory Pro.')
  window.open(`https://wa.me/${phone}?text=${message}`, '_blank', 'noopener,noreferrer')
}

const handleTransferSubmit = async () => {
  loading.value = true
  try {
    const response = await axios.post('/api/payments/transfer', {
      plan: plan.value,
      reference: transferForm.value.reference
    })
    
    showTransferModal.value = true
  } catch (error) {
    alert('Error al registrar la transferencia. Intenta de nuevo.')
  } finally {
    loading.value = false
  }
}
</script>
