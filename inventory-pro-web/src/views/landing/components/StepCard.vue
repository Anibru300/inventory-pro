<template>
  <div class="relative text-center">
    <!-- Step Number -->
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-bold"
      :class="isDark ? 'bg-[#2E7DE8] text-white' : 'bg-[#2E7DE8] text-white'">
      {{ step }}
    </div>

    <!-- Connector Line (hidden on last item) -->
    <div v-if="step < 4" class="hidden md:block absolute top-8 left-[60%] w-[80%] h-0.5"
      :class="isDark ? 'bg-gray-700' : 'bg-gray-200'"></div>

    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 border-2"
      :class="isDark ? 'bg-[#0B1F3A] border-gray-700' : 'bg-white border-gray-200'">
      <svg class="w-7 h-7 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path v-if="icon === 'register'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        <path v-else-if="icon === 'configure'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
        <path v-else-if="icon === 'train'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        <path v-else-if="icon === 'grow'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
      </svg>
    </div>

    <h3 class="text-lg font-semibold mb-2" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
      {{ title }}
    </h3>
    <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
      {{ description }}
    </p>
  </div>
</template>

<script setup>
import { useDarkMode } from '../../../composables/useDarkMode'

const { isDark } = useDarkMode()

defineProps({
  step: Number,
  icon: String,
  title: String,
  description: String
})
</script>
