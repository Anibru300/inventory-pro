<template>
  <button 
    class="group p-5 rounded-2xl border transition-all hover:shadow-lg text-left h-full"
    :class="[
      isDark 
        ? 'bg-slate-800/50 border-slate-700 hover:border-slate-600' 
        : 'bg-white border-slate-200 hover:border-slate-300'
    ]"
    @click="$emit('click')">
    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 transition-transform group-hover:scale-110"
      :class="iconBgClass">
      <svg class="w-6 h-6" :class="iconColorClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path v-if="icon === 'package'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        <path v-else-if="icon === 'arrows'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
        <path v-else-if="icon === 'transfer'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
        <path v-else-if="icon === 'search'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
    </div>
    <p class="font-semibold mb-1 transition-colors" :class="isDark ? 'text-white' : 'text-slate-800'">
      {{ label }}
    </p>
    <p class="text-sm transition-colors" :class="isDark ? 'text-slate-400' : 'text-slate-500'">
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
  color: String
})

defineEmits(['click'])

const { isDark } = useDarkMode()

const iconBgClass = computed(() => {
  const colors = {
    blue: isDark.value ? 'bg-blue-500/20' : 'bg-blue-100',
    emerald: isDark.value ? 'bg-emerald-500/20' : 'bg-emerald-100',
    indigo: isDark.value ? 'bg-indigo-500/20' : 'bg-indigo-100',
    amber: isDark.value ? 'bg-amber-500/20' : 'bg-amber-100'
  }
  return colors[props.color] || colors.blue
})

const iconColorClass = computed(() => {
  const colors = {
    blue: isDark.value ? 'text-blue-400' : 'text-blue-600',
    emerald: isDark.value ? 'text-emerald-400' : 'text-emerald-600',
    indigo: isDark.value ? 'text-indigo-400' : 'text-indigo-600',
    amber: isDark.value ? 'text-amber-400' : 'text-amber-600'
  }
  return colors[props.color] || colors.blue
})
</script>
