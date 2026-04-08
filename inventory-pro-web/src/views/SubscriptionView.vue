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

        <!-- Payment Form -->
        <div class="rounded-2xl p-6 border backdrop-blur-sm"
          :class="isDark ? 'bg-[#0B1F3A]/60 border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
          
          <!-- Payment Method Tabs -->
          <div class="flex gap-2 mb-6">
            <button 
              @click="paymentMethod = 'card'"
              class="flex-1 py-3 px-4 rounded-xl font-medium transition-all flex items-center justify-center gap-2"
              :class="paymentMethod === 'card' 
                ? 'bg-[#2E7DE8] text-white shadow-lg' 
                : isDark ? 'bg-[#0B1F3A] text-gray-400 border border-gray-700' : 'bg-gray-100 text-gray-600'">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
              Tarjeta
            </button>
            <button 
              @click="paymentMethod = 'transfer'"
              class="flex-1 py-3 px-4 rounded-xl font-medium transition-all flex items-center justify-center gap-2"
              :class="paymentMethod === 'transfer' 
                ? 'bg-[#2E7DE8] text-white shadow-lg' 
                : isDark ? 'bg-[#0B1F3A] text-gray-400 border border-gray-700' : 'bg-gray-100 text-gray-600'">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
              </svg>
              Transferencia
            </button>
          </div>

          <!-- Card Payment Form -->
          <div v-if="paymentMethod === 'card'">
            <form @submit.prevent="handleCardSubmit" class="space-y-4">
              <div>
                <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">
                  Nombre en la tarjeta
                </label>
                <input 
                  v-model="cardForm.name"
                  type="text" 
                  required
                  class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] focus:border-transparent transition-all"
                  :class="isDark ? 'bg-[#0B1F3A] border-gray-700 text-white' : 'bg-white border-gray-300'"
                  placeholder="Nombre completo">
              </div>

              <div>
                <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">
                  Número de tarjeta
                </label>
                <div class="relative">
                  <input 
                    v-model="cardForm.number"
                    type="text" 
                    required
                    maxlength="19"
                    @input="formatCardNumber"
                    class="w-full px-4 py-3 pl-12 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] focus:border-transparent transition-all"
                    :class="isDark ? 'bg-[#0B1F3A] border-gray-700 text-white' : 'bg-white border-gray-300'"
                    placeholder="0000 0000 0000 0000">
                  <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                  </svg>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">
                    Fecha de expiración
                  </label>
                  <input 
                    v-model="cardForm.expiry"
                    type="text" 
                    required
                    maxlength="5"
                    @input="formatExpiry"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] focus:border-transparent transition-all"
                    :class="isDark ? 'bg-[#0B1F3A] border-gray-700 text-white' : 'bg-white border-gray-300'"
                    placeholder="MM/AA">
                </div>
                <div>
                  <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">
                    CVC
                  </label>
                  <input 
                    v-model="cardForm.cvc"
                    type="text" 
                    required
                    maxlength="4"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] focus:border-transparent transition-all"
                    :class="isDark ? 'bg-[#0B1F3A] border-gray-700 text-white' : 'bg-white border-gray-300'"
                    placeholder="123">
                </div>
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
                  <span v-if="hasTrial">Iniciar Prueba Gratis</span>
                  <span v-else>Pagar ${{ planPrice }}</span>
                </button>
              </div>

              <div class="flex items-center justify-center gap-2 text-sm" :class="isDark ? 'text-gray-500' : 'text-gray-400'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Pago seguro con Stripe
              </div>
            </form>
          </div>

          <!-- Transfer Payment Form -->
          <div v-else class="space-y-6">
            <div class="p-4 rounded-xl border" :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-gray-50 border-gray-200'">
              <h3 class="font-semibold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Datos Bancarios</h3>
              <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                  <span :class="isDark ? 'text-gray-400' : 'text-gray-600'">Institución:</span>
                  <span class="font-medium" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Mercado Pago W</span>
                </div>
                <div class="flex justify-between">
                  <span :class="isDark ? 'text-gray-400' : 'text-gray-600'">CLABE:</span>
                  <span class="font-mono font-medium" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">722969020205392763</span>
                </div>
                <div class="flex justify-between">
                  <span :class="isDark ? 'text-gray-400' : 'text-gray-600'">Beneficiario:</span>
                  <span class="font-medium" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Maria Jimena Mena Prado</span>
                </div>
                <div class="flex justify-between">
                  <span :class="isDark ? 'text-gray-400' : 'text-gray-600'">Concepto:</span>
                  <span class="font-mono font-medium text-[#2E7DE8]">INVPRO</span>
                </div>
              </div>
            </div>

            <form @submit.prevent="handleTransferSubmit" class="space-y-4">
              <div>
                <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">
                  Monto a transferir
                </label>
                <div class="px-4 py-3 rounded-xl border font-semibold"
                  :class="isDark ? 'bg-[#0B1F3A] border-gray-700 text-white' : 'bg-gray-100 border-gray-300 text-[#0B1F3A]'">
                  ${{ planPrice }}.00 MXN
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">
                  Referencia de transferencia
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
                  <span>Confirmar Transferencia</span>
                </button>
              </div>

              <p class="text-xs text-center" :class="isDark ? 'text-gray-500' : 'text-gray-400'">
                Tu cuenta será activada en 24-48 horas hábiles una vez confirmado el pago.
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
      <div class="bg-white rounded-2xl p-8 max-w-md w-full text-center shadow-2xl">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-emerald-100 flex items-center justify-center">
          <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">¡Suscripción Exitosa!</h3>
        <p class="text-gray-600 mb-6">
          <span v-if="hasTrial">Tu prueba gratis de 30 días ha comenzado. Disfruta de todas las funciones del plan {{ planName }}.</span>
          <span v-else>Tu suscripción al plan {{ planName }} ha sido activada exitosamente.</span>
        </p>
        <router-link to="/dashboard" class="inline-block px-8 py-3 bg-[#2E7DE8] text-white rounded-xl font-semibold hover:bg-blue-600 transition-all">
          Ir al Dashboard
        </router-link>
      </div>
    </div>

    <!-- Transfer Pending Modal -->
    <div v-if="showTransferModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
      <div class="rounded-2xl p-8 max-w-md w-full text-center shadow-2xl"
        :class="isDark ? 'bg-[#0B1F3A] border border-[#2E7DE8]/20' : 'bg-white'">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-amber-100 flex items-center justify-center">
          <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="text-2xl font-bold mb-2" :class="isDark ? 'text-white' : 'text-gray-900'">Transferencia en Proceso</h3>
        <p class="mb-6" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
          Hemos recibido tu solicitud. Tu cuenta será activada en <strong>24-48 horas hábiles</strong> una vez confirmado el pago.
        </p>
        <div class="p-4 rounded-xl mb-6 text-left" :class="isDark ? 'bg-[#1a3050]' : 'bg-gray-100'">
          <p class="text-sm mb-1" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Referencia:</p>
          <p class="font-mono font-semibold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">{{ transferForm.reference }}</p>
        </div>
        <router-link to="/" class="inline-block px-8 py-3 bg-[#2E7DE8] text-white rounded-xl font-semibold hover:bg-blue-600 transition-all">
          Volver al Inicio
        </router-link>
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

const paymentMethod = ref('card')
const loading = ref(false)
const showSuccessModal = ref(false)
const showTransferModal = ref(false)

const cardForm = ref({
  name: '',
  number: '',
  expiry: '',
  cvc: ''
})

const transferForm = ref({
  reference: ''
})

const formatCardNumber = (e) => {
  let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '')
  const matches = value.match(/\d{4,16}/g)
  const match = matches && matches[0] || ''
  const parts = []
  for (let i = 0, len = match.length; i < len; i += 4) {
    parts.push(match.substring(i, i + 4))
  }
  if (parts.length) {
    cardForm.value.number = parts.join(' ')
  } else {
    cardForm.value.number = value
  }
}

const formatExpiry = (e) => {
  let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '')
  if (value.length >= 2) {
    value = value.substring(0, 2) + '/' + value.substring(2, 4)
  }
  cardForm.value.expiry = value
}

const handleCardSubmit = async () => {
  loading.value = true
  try {
    // Aquí integrarías Stripe.js para tokenizar la tarjeta
    // Por ahora simulamos el éxito
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    // Llamar al backend para crear la suscripción
    // const response = await axios.post('/api/payments/subscribe', {
    //   plan: plan.value,
    //   payment_method_id: paymentMethodId
    // })
    
    showSuccessModal.value = true
  } catch (error) {
    alert('Error al procesar el pago. Intenta de nuevo.')
  } finally {
    loading.value = false
  }
}

const handleTransferSubmit = async () => {
  loading.value = true
  try {
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    // const response = await axios.post('/api/payments/transfer', {
    //   plan: plan.value,
    //   reference: transferForm.value.reference
    // })
    
    showTransferModal.value = true
  } catch (error) {
    alert('Error al registrar la transferencia. Intenta de nuevo.')
  } finally {
    loading.value = false
  }
}
</script>
