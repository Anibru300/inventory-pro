<template>
  <div class="group relative p-6 rounded-2xl transition-all duration-300 hover:-translate-y-1 border backdrop-blur-sm"
    :class="isDark 
      ? 'bg-[#0B1F3A]/60 border-[#2E7DE8]/20 hover:border-[#2E7DE8]/50 hover:shadow-lg hover:shadow-[#2E7DE8]/10' 
      : 'bg-white/80 border-gray-200 hover:border-[#2E7DE8] hover:shadow-xl hover:shadow-blue-500/10'">
    
    <!-- Icon -->
    <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-4 transition-all duration-300 group-hover:scale-110"
      :class="isDark 
        ? 'bg-gradient-to-br from-[#2E7DE8] to-[#0B1F3A] border border-[#2E7DE8]/30' 
        : 'bg-gradient-to-br from-[#2E7DE8] to-[#1e6ad1]'">
      <svg v-if="icon === 'inventory'" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
      </svg>
      <svg v-else-if="icon === 'barcode'" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
      </svg>
      <svg v-else-if="icon === 'chart'" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
      </svg>
      <svg v-else-if="icon === 'alert'" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
      <svg v-else-if="icon === 'users'" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
      </svg>
      <svg v-else class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
      </svg>
    </div>

    <!-- Content -->
    <h3 class="text-lg font-bold mb-2" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">{{ title }}</h3>
    <p class="text-sm leading-relaxed" :class="isDark ? 'text-gray-400' : 'text-gray-600'">{{ description }}</p>

    <!-- Hover indicator -->
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-[#2E7DE8] to-[#C0C0C0] rounded-b-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
  </div>
</template>

<script setup>
defineProps({
  icon: String,
  title: String,
  description: String,
  isDark: Boolean
})
</script>
