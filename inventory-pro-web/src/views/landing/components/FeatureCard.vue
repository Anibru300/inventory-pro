<template>
  <div class="group p-6 rounded-2xl border transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
    :class="isDark ? 'bg-[#0B1F3A]/50 border-gray-700 hover:border-[#2E7DE8]/50' : 'bg-white border-gray-200 hover:border-[#2E7DE8]'">
    
    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-5 transition-transform group-hover:scale-110"
      :class="isDark ? 'bg-[#2E7DE8]/20' : 'bg-blue-50'">
      <svg class="w-7 h-7" :class="isDark ? 'text-[#2E7DE8]' : 'text-[#2E7DE8]'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path v-if="icon === 'inventory'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        <path v-else-if="icon === 'barcode'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M12 4v16m8-8h2m-2 0h-2M4 12H2m2 0h2m13-8l-4 4m0 0l-4-4m4 4V3m0 18l-4-4m0 0l4-4m-4 4h10" />
        <path v-else-if="icon === 'chart'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        <path v-else-if="icon === 'alert'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        <path v-else-if="icon === 'users'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        <path v-else-if="icon === 'mobile'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
      </svg>
    </div>

    <h3 class="text-lg font-semibold mb-2 transition-colors" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
      {{ title }}
    </h3>
    <p class="text-sm leading-relaxed" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
      {{ description }}
    </p>
  </div>
</template>

<script setup>
import { useDarkMode } from '../../../composables/useDarkMode'

const { isDark } = useDarkMode()

defineProps({
  icon: String,
  title: String,
  description: String
})
</script>
