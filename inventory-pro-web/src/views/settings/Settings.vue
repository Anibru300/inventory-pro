<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
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
          <h1 class="text-3xl font-bold text-slate-800 mb-1">Configuración</h1>
          <p class="text-slate-500">Personaliza tu sistema de inventario</p>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Company Settings -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Company Profile -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
          <h3 class="text-lg font-semibold mb-4 text-slate-800 flex items-center gap-2">
            <span class="w-1 h-5 bg-blue-600 rounded-full"></span>
            Perfil de la Empresa
          </h3>
          <form @submit.prevent="saveCompany" class="space-y-4">
            <!-- Logo Upload -->
            <div class="flex items-center gap-4 mb-4">
              <div class="relative">
                <div class="w-24 h-24 rounded-2xl bg-slate-100 border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden">
                  <img v-if="company.logo" :src="company.logo" class="w-full h-full object-cover" />
                  <svg v-else class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <input 
                  ref="logoInput"
                  type="file" 
                  accept="image/*"
                  class="hidden"
                  @change="handleLogoUpload"
                />
              </div>
              <div>
                <button 
                  type="button"
                  @click="$refs.logoInput.click()"
                  class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
                >
                  Subir Logo
                </button>
                <p class="text-xs text-slate-500 mt-1">PNG, JPG hasta 2MB</p>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Nombre de la Empresa</label>
              <input 
                v-model="company.name"
                type="text" 
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-slate-800"
                placeholder="Tu Empresa S.A. de C.V."
              />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2 text-slate-700">Email de Contacto</label>
                <input 
                  v-model="company.email"
                  type="email" 
                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-slate-800"
                  placeholder="contacto@empresa.com"
                />
              </div>
              <div>
                <label class="block text-sm font-medium mb-2 text-slate-700">Teléfono</label>
                <input 
                  v-model="company.phone"
                  type="tel" 
                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-slate-800"
                  placeholder="+52 55 1234 5678"
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Dirección</label>
              <textarea 
                v-model="company.address"
                rows="2" 
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-slate-800 resize-none"
                placeholder="Calle, número, colonia, ciudad..."
              ></textarea>
            </div>

            <div class="pt-4">
              <button 
                type="submit"
                :disabled="saving"
                class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 disabled:opacity-50 flex items-center gap-2"
              >
                <svg v-if="saving" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ saving ? 'Guardando...' : 'Guardar Cambios' }}
              </button>
            </div>
          </form>
        </div>

        <!-- User Profile -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
          <h3 class="text-lg font-semibold mb-4 text-slate-800 flex items-center gap-2">
            <span class="w-1 h-5 bg-emerald-600 rounded-full"></span>
            Mi Perfil
          </h3>
          <form @submit.prevent="saveProfile" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2 text-slate-700">Nombre</label>
                <input 
                  v-model="profile.first_name"
                  type="text" 
                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-slate-800"
                />
              </div>
              <div>
                <label class="block text-sm font-medium mb-2 text-slate-700">Apellido</label>
                <input 
                  v-model="profile.last_name"
                  type="text" 
                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-slate-800"
                />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Email</label>
              <input 
                v-model="profile.email"
                type="email" 
                disabled
                class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed"
              />
            </div>
            <div class="pt-4">
              <button 
                type="submit"
                :disabled="savingProfile"
                class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 disabled:opacity-50 flex items-center gap-2"
              >
                <svg v-if="savingProfile" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ savingProfile ? 'Actualizando...' : 'Actualizar Perfil' }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Sidebar Settings -->
      <div class="space-y-6">
        <!-- Plan Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
          <h3 class="text-lg font-semibold mb-4 text-slate-800">Tu Plan</h3>
          <div class="text-center">
            <span class="inline-block px-4 py-2 bg-blue-100 text-blue-700 border border-blue-200 rounded-full text-sm font-bold uppercase">
              {{ authStore.tenant?.plan || 'FREE' }}
            </span>
            <p class="mt-4 text-sm text-slate-600">
              Prueba gratuita hasta:<br />
              <span class="font-semibold text-slate-800">{{ formatDate(authStore.tenant?.trial_ends_at) }}</span>
            </p>
            <button 
              @click="upgradePlan"
              :disabled="upgrading"
              class="w-full mt-4 px-4 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 disabled:opacity-50 flex items-center justify-center gap-2"
            >
              <svg v-if="upgrading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
              {{ upgrading ? 'Procesando...' : 'Actualizar Plan' }}
            </button>
          </div>
        </div>

        <!-- Preferences -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
          <h3 class="text-lg font-semibold mb-4 text-slate-800">Preferencias</h3>
          <div class="space-y-4">
            <label class="flex items-center justify-between cursor-pointer">
              <span class="text-slate-700">Notificaciones por email</span>
              <input 
                v-model="preferences.email_notifications"
                type="checkbox" 
                class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
              />
            </label>
            <label class="flex items-center justify-between cursor-pointer">
              <span class="text-slate-700">Alertas de stock bajo</span>
              <input 
                v-model="preferences.low_stock_alerts"
                type="checkbox" 
                class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
              />
            </label>
            <label class="flex items-center justify-between cursor-pointer">
              <span class="text-slate-700">Modo oscuro</span>
              <input 
                v-model="preferences.dark_mode"
                type="checkbox"
                @change="toggleDarkMode"
                class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
              />
            </label>
          </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-2xl shadow-sm border border-rose-200 p-6">
          <h3 class="text-lg font-semibold mb-4 text-rose-600">Zona de Peligro</h3>
          <button 
            @click="confirmDeleteAccount"
            class="w-full px-4 py-3 bg-rose-100 text-rose-700 font-semibold rounded-xl hover:bg-rose-200 transition-all border border-rose-200"
          >
            Eliminar Cuenta
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import apiClient from '../../services/api'

const router = useRouter()
const authStore = useAuthStore()

const saving = ref(false)
const savingProfile = ref(false)
const upgrading = ref(false)
const logoInput = ref(null)

const company = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
  logo: null
})

const profile = ref({
  first_name: '',
  last_name: '',
  email: ''
})

const preferences = ref({
  email_notifications: true,
  low_stock_alerts: true,
  dark_mode: false
})

// Load data on mount
onMounted(() => {
  // Load company data from tenant
  if (authStore.tenant) {
    company.value = {
      name: authStore.tenant.name || '',
      email: authStore.tenant.email || '',
      phone: authStore.tenant.phone || '',
      address: authStore.tenant.address || '',
      logo: authStore.tenant.logo_url || null
    }
  }
  
  // Load profile data
  if (authStore.user) {
    profile.value = {
      first_name: authStore.user.first_name || '',
      last_name: authStore.user.last_name || '',
      email: authStore.user.email || ''
    }
  }
  
  // Load preferences
  const savedPrefs = localStorage.getItem('user_preferences')
  if (savedPrefs) {
    preferences.value = JSON.parse(savedPrefs)
  }
})

async function saveCompany() {
  saving.value = true
  try {
    await apiClient.put('/tenant', company.value)
    alert('Configuración de empresa guardada correctamente')
  } catch (error) {
    console.error('Error saving company:', error)
    alert('Error al guardar la configuración')
  } finally {
    saving.value = false
  }
}

async function saveProfile() {
  savingProfile.value = true
  try {
    await apiClient.put('/user/profile', profile.value)
    authStore.user.first_name = profile.value.first_name
    authStore.user.last_name = profile.value.last_name
    alert('Perfil actualizado correctamente')
  } catch (error) {
    console.error('Error saving profile:', error)
    alert('Error al actualizar el perfil')
  } finally {
    savingProfile.value = false
  }
}

async function handleLogoUpload(event) {
  const file = event.target.files[0]
  if (!file) return
  
  if (file.size > 2 * 1024 * 1024) {
    alert('El archivo debe ser menor a 2MB')
    return
  }
  
  const formData = new FormData()
  formData.append('logo', file)
  
  try {
    const response = await apiClient.post('/tenant/logo', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    company.value.logo = response.data.logo_url
    authStore.tenant.logo_url = response.data.logo_url
    alert('Logo actualizado correctamente')
  } catch (error) {
    console.error('Error uploading logo:', error)
    alert('Error al subir el logo')
  }
}

async function upgradePlan() {
  upgrading.value = true
  try {
    // Create Stripe checkout session
    const response = await apiClient.post('/stripe/checkout', {
      plan: 'pro',
      success_url: window.location.origin + '/#/settings?success=true',
      cancel_url: window.location.origin + '/#/settings?canceled=true'
    })
    
    // Redirect to Stripe Checkout
    window.location.href = response.data.url
  } catch (error) {
    console.error('Error upgrading plan:', error)
    alert('Error al procesar la actualización del plan')
    upgrading.value = false
  }
}

function toggleDarkMode() {
  // Implement dark mode toggle
  document.documentElement.classList.toggle('dark')
  localStorage.setItem('user_preferences', JSON.stringify(preferences.value))
}

function confirmDeleteAccount() {
  if (confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.')) {
    // Implement account deletion
    alert('Función de eliminación de cuenta en desarrollo')
  }
}

function formatDate(date) {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('es-ES', { day: '2-digit', month: 'long', year: 'numeric' })
}
</script>
