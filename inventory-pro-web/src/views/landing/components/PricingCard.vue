<template>
  <div class="relative rounded-2xl p-8 transition-all duration-300 hover:shadow-2xl"
    :class="[
      popular 
        ? 'bg-gradient-to-br from-[#0B1F3A] to-[#2E7DE8] text-white scale-105 shadow-xl' 
        : (isDark ? 'bg-[#0B1F3A]/50 border border-gray-700 hover:border-gray-600' : 'bg-white border border-gray-200 hover:border-[#2E7DE8]')
    ]">
    
    <!-- Popular Badge -->
    <div v-if="popular" class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-[#C0C0C0] text-[#0B1F3A] rounded-full text-sm font-bold">
      Más Popular
    </div>

    <div class="text-center mb-8">
      <h3 class="text-xl font-bold mb-2" :class="popular ? 'text-white' : (isDark ? 'text-white' : 'text-[#0B1F3A]')" style="font-family: 'Montserrat', sans-serif;">
        {{ name }}
      </h3>
      <p class="text-sm mb-4" :class="popular ? 'text-blue-100' : (isDark ? 'text-gray-400' : 'text-gray-500')">
        {{ description }}
      </p>
      <div class="flex items-baseline justify-center gap-1">
        <span class="text-2xl" :class="popular ? 'text-blue-100' : (isDark ? 'text-gray-400' : 'text-gray-500')">$</span>
        <span class="text-5xl font-bold" :class="popular ? 'text-white' : (isDark ? 'text-white' : 'text-[#0B1F3A]')">{{ price }}</span>
        <span class="text-sm" :class="popular ? 'text-blue-100' : (isDark ? 'text-gray-400' : 'text-gray-500')">/{{ period }}</span>
      </div>
    </div>

    <ul class="space-y-4 mb-8">
      <li v-for="feature in features" :key="feature" class="flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" :class="popular ? 'text-[#C0C0C0]' : 'text-[#2E7DE8]'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span class="text-sm" :class="popular ? 'text-blue-100' : (isDark ? 'text-gray-300' : 'text-gray-600')">
          {{ feature }}
        </span>
      </li>
    </ul>

    <button class="w-full py-3 rounded-xl font-semibold transition-all"
      :class="popular 
        ? 'bg-white text-[#2E7DE8] hover:bg-gray-100' 
        : (isDark 
          ? 'bg-gray-700 text-white hover:bg-gray-600' 
          : 'bg-[#0B1F3A] text-white hover:bg-[#1a3050]')">
      {{ cta }}
    </button>
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
  isDark: Boolean
})
</script>
