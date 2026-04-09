<template>
  <button 
    class="group p-5 rounded-xl border transition-all hover:shadow-md text-left relative overflow-hidden h-full"
    :class="[
      isDark 
        ? 'bg-slate-800/50 border-slate-700 hover:border-slate-600' 
        : 'bg-white border-slate-200 hover:border-slate-300'
    ]"
    @click="$emit('click')">
    
    <!-- Alert Badge -->
    <div v-if="alertCount > 0" 
      class="absolute top-3 right-3 w-6 h-6 rounded-full bg-rose-500 flex items-center justify-center text-white text-xs font-bold">
      {{ alertCount }}
    </div>

    <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3"
      :class="iconBgClass">
      <svg class="w-5 h-5" :class="iconColorClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path v-if="icon === 'chart'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        <path v-else-if="icon === 'package'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        <path v-else-if="icon === 'warehouse'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        <path v-else-if="icon === 'grid'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
        <path v-else-if="icon === 'arrows'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
        <path v-else-if="icon === 'transfer'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
        <path v-else-if="icon === 'report'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        <path v-else-if="icon === 'upload'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
        <path v-else-if="icon === 'users'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        <path v-else-if="icon === 'settings'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path v-else-if="icon === 'history'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
    </div>
    <p class="font-semibold mb-0.5 transition-colors" :class="isDark ? 'text-white' : 'text-slate-800'">
      {{ label }}
    </p>
    <p class="text-xs transition-colors" :class="isDark ? 'text-slate-400' : 'text-slate-500'">
      {{ description }}
    </p>
  </button>
</template>

<script setup>
import { computed } from 'vue'
import { useDarkMode } from '../composables/useDarkMode'

const props = defineProps({
  label: String,
  description: String,
  icon: String,
  color: String,
  alertCount: { type: Number, default: 0 }
})

defineEmits(['click'])

const { isDark } = useDarkMode()

const iconBgClass = computed(() => {
  const colors = {
    blue: isDark.value ? 'bg-blue-500/20' : 'bg-blue-100',
    emerald: isDark.value ? 'bg-emerald-500/20' : 'bg-emerald-100',
    indigo: isDark.value ? 'bg-indigo-500/20' : 'bg-indigo-100',
    purple: isDark.value ? 'bg-purple-500/20' : 'bg-purple-100',
    amber: isDark.value ? 'bg-amber-500/20' : 'bg-amber-100',
    cyan: isDark.value ? 'bg-cyan-500/20' : 'bg-cyan-100',
    rose: isDark.value ? 'bg-rose-500/20' : 'bg-rose-100',
    lime: isDark.value ? 'bg-lime-500/20' : 'bg-lime-100',
    violet: isDark.value ? 'bg-violet-500/20' : 'bg-violet-100',
    slate: isDark.value ? 'bg-slate-500/20' : 'bg-slate-100',
    orange: isDark.value ? 'bg-orange-500/20' : 'bg-orange-100'
  }
  return colors[props.color] || colors.blue
})

const iconColorClass = computed(() => {
  const colors = {
    blue: isDark.value ? 'text-blue-400' : 'text-blue-600',
    emerald: isDark.value ? 'text-emerald-400' : 'text-emerald-600',
    indigo: isDark.value ? 'text-indigo-400' : 'text-indigo-600',
    purple: isDark.value ? 'text-purple-400' : 'text-purple-600',
    amber: isDark.value ? 'text-amber-400' : 'text-amber-600',
    cyan: isDark.value ? 'text-cyan-400' : 'text-cyan-600',
    rose: isDark.value ? 'text-rose-400' : 'text-rose-600',
    lime: isDark.value ? 'text-lime-400' : 'text-lime-600',
    violet: isDark.value ? 'text-violet-400' : 'text-violet-600',
    slate: isDark.value ? 'text-slate-400' : 'text-slate-600',
    orange: isDark.value ? 'text-orange-400' : 'text-orange-600'
  }
  return colors[props.color] || colors.blue
})
</script>
