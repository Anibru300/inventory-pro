<template>
  <div class="rounded-2xl p-6 border transition-all hover:shadow-lg"
    :class="[
      isDark ? 'bg-slate-800/50 border-slate-700 hover:border-slate-600' : 'bg-white border-slate-200 hover:border-slate-300',
      alert ? 'ring-2 ring-amber-500/30' : ''
    ]">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-xs font-medium uppercase tracking-wider mb-1"
          :class="isDark ? 'text-slate-400' : 'text-slate-500'">
          {{ title }}
        </p>
        <div class="flex items-baseline gap-2">
          <p v-if="!loading" class="text-3xl font-bold"
            :class="isDark ? 'text-white' : 'text-slate-800'">
            {{ value }}
          </p>
          <div v-else class="h-8 w-20 rounded animate-pulse"
            :class="isDark ? 'bg-slate-700' : 'bg-slate-200'"></div>
        </div>
      </div>
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center"
        :class="iconBgClass">
        <svg class="w-7 h-7" :class="iconColorClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path v-if="icon === 'package'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          <path v-else-if="icon === 'currency'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          <path v-else-if="icon === 'alert'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          <path v-else-if="icon === 'error'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useDarkMode } from '../composables/useDarkMode'

const props = defineProps({
  title: { type: String, required: true },
  value: { type: [String, Number], required: true },
  icon: { type: String, default: 'package' },
  color: { type: String, default: 'blue' },
  loading: { type: Boolean, default: false },
  alert: { type: Boolean, default: false }
})

const { isDark } = useDarkMode()

const iconBgClass = computed(() => {
  const colors = {
    blue: isDark.value ? 'bg-blue-500/20' : 'bg-blue-50',
    emerald: isDark.value ? 'bg-emerald-500/20' : 'bg-emerald-50',
    amber: isDark.value ? 'bg-amber-500/20' : 'bg-amber-50',
    rose: isDark.value ? 'bg-rose-500/20' : 'bg-rose-50'
  }
  return colors[props.color] || colors.blue
})

const iconColorClass = computed(() => {
  const colors = {
    blue: isDark.value ? 'text-blue-400' : 'text-blue-600',
    emerald: isDark.value ? 'text-emerald-400' : 'text-emerald-600',
    amber: isDark.value ? 'text-amber-400' : 'text-amber-600',
    rose: isDark.value ? 'text-rose-400' : 'text-rose-600'
  }
  return colors[props.color] || colors.blue
})
</script>
