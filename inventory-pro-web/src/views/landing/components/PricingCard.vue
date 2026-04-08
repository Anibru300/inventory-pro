<template>
  <div class="relative rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-2"
    :class="[
      popular 
        ? 'transform scale-105 shadow-2xl shadow-[#2E7DE8]/30 z-10' 
        : 'hover:shadow-xl border',
      free
        ? 'border-2 border-emerald-500/50'
        : '',
      isDark 
        ? popular ? 'bg-gradient-to-br from-[#2E7DE8] via-[#1e6ad1] to-[#0B1F3A]' : free ? 'bg-[#0B1F3A]/80' : 'bg-[#0B1F3A]/60 border-[#2E7DE8]/20 hover:border-[#2E7DE8]/40'
        : popular ? 'bg-gradient-to-br from-[#2E7DE8] via-[#1e6ad1] to-[#0B1F3A]' : free ? 'bg-emerald-50/80' : 'bg-white/90 border-gray-200 hover:border-[#2E7DE8]'
    ]">
    
    <!-- Popular Badge -->
    <div v-if="popular" class="absolute top-0 right-0 left-0 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-center py-2 text-sm font-bold">
      MÁS POPULAR
    </div>

    <!-- Free Badge -->
    <div v-if="free" class="absolute top-0 right-0 left-0 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-center py-2 text-sm font-bold">
      GRATIS PARA SIEMPRE
    </div>

    <!-- Popular Top Corner -->
    <div v-if="popular" class="absolute -top-3 right-6">
      <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 shadow-lg">
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
        </svg>
      </div>
    </div>

    <!-- Free Icon -->
    <div v-if="free" class="absolute -top-3 right-6">
      <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 shadow-lg">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </div>
    </div>

    <div class="p-8" :class="{ 'pt-14': popular || free }">
      <!-- Header -->
      <div class="text-center mb-6">
        <h3 class="text-xl font-bold mb-2" :class="popular ? 'text-white' : isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
          {{ name }}
        </h3>
        <p class="text-sm mb-4" :class="popular ? 'text-blue-100' : isDark ? 'text-gray-400' : 'text-gray-500'">
          {{ description }}
        </p>
        <div class="flex items-baseline justify-center">
          <span v-if="price !== '0'" class="text-sm" :class="popular ? 'text-blue-200' : isDark ? 'text-gray-400' : 'text-gray-500'">$</span>
          <span class="text-5xl font-bold mx-1" :class="popular ? 'text-white' : free ? (isDark ? 'text-emerald-400' : 'text-emerald-600') : isDark ? 'text-white' : 'text-[#0B1F3A]'">
            {{ price === '0' ? 'GRATIS' : price }}
          </span>
          <span v-if="price !== '0'" class="text-sm" :class="popular ? 'text-blue-200' : isDark ? 'text-gray-400' : 'text-gray-500'">/{{ period }}</span>
        </div>
        <p v-if="trial" class="text-xs mt-2 font-semibold" :class="popular ? 'text-amber-300' : 'text-amber-500'">
          🎁 Incluye 1 mes de prueba gratis
        </p>
        <p v-else-if="!free" class="text-xs mt-2" :class="popular ? 'text-blue-200' : isDark ? 'text-gray-500' : 'text-gray-400'">IVA incluido</p>
      </div>

      <!-- Features -->
      <ul class="space-y-3 mb-8">
        <li v-for="feature in features" :key="feature" class="flex items-center gap-3">
          <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0" 
            :class="[
              popular ? 'bg-white/20' : 
              free ? (isDark ? 'bg-emerald-500/20' : 'bg-emerald-100') : 
              'bg-[#2E7DE8]/10'
            ]">
            <svg class="w-3 h-3" :class="popular ? 'text-white' : free ? (isDark ? 'text-emerald-400' : 'text-emerald-600') : 'text-[#2E7DE8]'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <span class="text-sm" :class="popular ? 'text-white' : isDark ? 'text-gray-300' : 'text-gray-600'">
            {{ feature }}
          </span>
        </li>
      </ul>

      <!-- CTA Button -->
      <!-- Plan Gratis -->
      <router-link 
        v-if="free"
        to="/register?plan=free"
        class="block w-full py-3 px-4 text-center rounded-xl font-semibold transition-all active:scale-95 border-2"
        :class="isDark 
          ? 'border-emerald-500 text-emerald-400 hover:bg-emerald-500 hover:text-white'
          : 'border-emerald-500 text-emerald-600 hover:bg-emerald-500 hover:text-white'">
        {{ cta }}
      </router-link>

      <!-- Plan Popular con Trial -->
      <router-link 
        v-else-if="popular && trial"
        to="/subscribe?plan=professional&trial=1"
        class="block w-full py-3 px-4 text-center rounded-xl font-semibold bg-white text-[#2E7DE8] hover:bg-gray-100 transition-all shadow-lg active:scale-95">
        {{ cta }}
      </router-link>

      <!-- Plan Ilimitado -->
      <router-link 
        v-else-if="unlimited"
        to="/subscribe?plan=unlimited"
        class="block w-full py-3 px-4 text-center rounded-xl font-semibold transition-all border-2 active:scale-95"
        :class="isDark 
          ? 'border-[#2E7DE8] text-[#2E7DE8] hover:bg-[#2E7DE8] hover:text-white'
          : 'border-[#2E7DE8] text-[#2E7DE8] hover:bg-[#2E7DE8] hover:text-white'">
        {{ cta }}
      </router-link>
    </div>
  </div>
</template>

<script setup>
defineProps({
  name: String,
  price: String,
  period: String,
  description: String,
  features: Array,
  cta: String,
  popular: Boolean,
  free: Boolean,
  trial: Boolean,
  unlimited: Boolean,
  isDark: Boolean
})

const contactSales = () => {
  const phone = '524776940272'
  const message = encodeURIComponent(`Hola Carlos, estoy interesado en el Plan Ilimitado de Inventory Pro ($799/mes). ¿Podemos agendar una llamada?`)
  window.open(`https://wa.me/${phone}?text=${message}`, '_blank', 'noopener,noreferrer')
}
</script>
