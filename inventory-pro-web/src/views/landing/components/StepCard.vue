<template>
  <div class="relative text-center group">
    <!-- Step Number -->
    <div class="relative inline-flex items-center justify-center w-16 h-16 mb-6 rounded-full text-2xl font-bold transition-all duration-300 group-hover:scale-110 shadow-lg"
      :class="isDark 
        ? 'bg-gradient-to-br from-[#2E7DE8] to-[#1e6ad1] text-white border-2 border-[#2E7DE8]/50' 
        : 'bg-gradient-to-br from-[#2E7DE8] to-[#1e6ad1] text-white'">
      {{ step }}
    </div>

    <!-- Icon -->
    <div class="w-12 h-12 mx-auto mb-4 rounded-xl flex items-center justify-center transition-colors"
      :class="isDark 
        ? 'bg-[#2E7DE8]/10 border border-[#2E7DE8]/20' 
        : 'bg-blue-50 border border-blue-100'">
      <svg v-if="icon === 'register'" class="w-6 h-6 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
      </svg>
      <svg v-else-if="icon === 'configure'" class="w-6 h-6 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
      <svg v-else-if="icon === 'train'" class="w-6 h-6 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
      </svg>
      <svg v-else class="w-6 h-6 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
      </svg>
    </div>

    <h3 class="text-lg font-bold mb-2" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">{{ title }}</h3>
    <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-600'">{{ description }}</p>

    <!-- Connector line (hidden on mobile) -->
    <div v-if="step < 4" class="hidden md:block absolute top-8 left-[60%] w-[80%] h-0.5"
      :class="isDark ? 'bg-gradient-to-r from-[#2E7DE8]/50 to-transparent' : 'bg-gradient-to-r from-[#2E7DE8]/30 to-transparent'">
    </div>
  </div>
</template>

<script setup>
defineProps({
  step: Number,
  icon: String,
  title: String,
  description: String,
  isDark: Boolean
})
</script>
