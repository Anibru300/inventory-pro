<template>
  <div class="relative rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-2"
    :class="[
      popular 
        ? 'transform scale-105 shadow-2xl shadow-[#2E7DE8]/30' 
        : 'hover:shadow-xl border',
      isDark 
        ? popular ? 'bg-gradient-to-br from-[#2E7DE8] via-[#1e6ad1] to-[#0B1F3A]' : 'bg-[#0B1F3A]/60 border-[#2E7DE8]/20 hover:border-[#2E7DE8]/40'
        : popular ? 'bg-gradient-to-br from-[#2E7DE8] via-[#1e6ad1] to-[#0B1F3A]' : 'bg-white/90 border-gray-200 hover:border-[#2E7DE8]'
    ]">
    
    <!-- Popular Badge -->
    <div v-if="popular" class="absolute top-0 right-0 left-0 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-center py-2 text-sm font-bold">
      MÁS POPULAR
    </div>

    <!-- Popular Top Corner -->
    <div v-if="popular" class="absolute -top-3 right-6">
      <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 shadow-lg">
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
        </svg>
      </div>
    </div>

    <div class="p-8" :class="{ 'pt-14': popular }">
      <!-- Header -->
      <div class="text-center mb-6">
        <h3 class="text-xl font-bold mb-2" :class="popular ? 'text-white' : isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
          {{ name }}
        </h3>
        <p class="text-sm mb-4" :class="popular ? 'text-blue-100' : isDark ? 'text-gray-400' : 'text-gray-500'">
          {{ description }}
        </p>
        <div class="flex items-baseline justify-center">
          <span class="text-sm" :class="popular ? 'text-blue-200' : isDark ? 'text-gray-400' : 'text-gray-500'">$</span>
          <span class="text-5xl font-bold mx-1" :class="popular ? 'text-white' : isDark ? 'text-white' : 'text-[#0B1F3A]'">{{ price }}</span>
          <span class="text-sm" :class="popular ? 'text-blue-200' : isDark ? 'text-gray-400' : 'text-gray-500'">/{{ period }}</span>
        </div>
        <p class="text-xs mt-2" :class="popular ? 'text-blue-200' : isDark ? 'text-gray-500' : 'text-gray-400'">IVA incluido</p>
      </div>

      <!-- Features -->
      <ul class="space-y-3 mb-8">
        <li v-for="feature in features" :key="feature" class="flex items-center gap-3">
          <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0" :class="popular ? 'bg-white/20' : 'bg-[#2E7DE8]/10'">
            <svg class="w-3 h-3" :class="popular ? 'text-white' : 'text-[#2E7DE8]'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <span class="text-sm" :class="popular ? 'text-white' : isDark ? 'text-gray-300' : 'text-gray-600'">
            {{ feature }}
          </span>
        </li>
      </ul>

      <!-- CTA Button -->
      <router-link 
        v-if="!popular && name !== 'Enterprise'"
        to="/register"
        class="block w-full py-3 px-4 text-center rounded-xl font-semibold transition-all border-2 active:scale-95"
        :class="isDark 
          ? 'border-[#2E7DE8] text-[#2E7DE8] hover:bg-[#2E7DE8] hover:text-white'
          : 'border-[#2E7DE8] text-[#2E7DE8] hover:bg-[#2E7DE8] hover:text-white'">
        {{ cta }}
      </router-link>
      <router-link 
        v-else-if="popular"
        to="/register"
        class="block w-full py-3 px-4 text-center rounded-xl font-semibold bg-white text-[#2E7DE8] hover:bg-gray-100 transition-all shadow-lg active:scale-95">
        {{ cta }}
      </router-link>
      <button 
        v-else
        @click="contactEnterprise"
        class="block w-full py-3 px-4 text-center rounded-xl font-semibold transition-all border-2 active:scale-95"
        :class="isDark 
          ? 'border-[#2E7DE8] text-[#2E7DE8] hover:bg-[#2E7DE8] hover:text-white'
          : 'border-[#2E7DE8] text-[#2E7DE8] hover:bg-[#2E7DE8] hover:text-white'">
        {{ cta }}
      </button>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  name: String,
  price: String,
  period: String,
  description: String,
  features: Array,
  cta: String,
  popular: Boolean,
  isDark: Boolean
})

const contactEnterprise = () => {
  const phone = '524776940272'
  const message = encodeURIComponent(`Hola Carlos, estoy interesado en el Plan Enterprise de Inventory Pro. ¿Podemos agendar una llamada para conocer más detalles?`)
  window.open(`https://wa.me/${phone}?text=${message}`, '_blank', 'noopener,noreferrer')
}
</script>
