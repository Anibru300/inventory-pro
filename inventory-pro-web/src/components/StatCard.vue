<template>
  <div class="group bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1"
    :class="[
      isDark ? 'bg-slate-800/50 border-slate-700 hover:border-slate-600' : 'bg-white border-slate-200 hover:border-slate-300',
      alert ? 'ring-2 ring-amber-500/30' : ''
    ]">
    <div class="flex items-start justify-between">
      <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-slate-500 mb-1">{{ title }}</p>
        <div class="flex items-baseline gap-2 flex-wrap">
          <h3 class="text-2xl font-bold text-slate-800 tabular-nums" :class="isDark ? 'text-white' : 'text-slate-800'">
            <AnimatedNumber :value="displayValue" :prefix="prefix" :suffix="suffix" :loading="loading" />
          </h3>
          <span 
            v-if="trend !== null" 
            class="text-xs font-medium px-2 py-0.5 rounded-full"
            :class="trend >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'"
          >
            {{ trend >= 0 ? '+' : '' }}{{ trend }}%
          </span>
        </div>
        <p v-if="subtitle" class="text-xs text-slate-400 mt-1">{{ subtitle }}</p>
      </div>
      
      <div 
        class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 ml-4 flex-shrink-0"
        :class="[iconBgClass, 'group-hover:scale-110']"
      >
        <svg v-if="icon === 'package'" class="w-6 h-6" :class="iconColorClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
        <svg v-else-if="icon === 'currency'" class="w-6 h-6" :class="iconColorClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <svg v-else-if="icon === 'alert'" class="w-6 h-6" :class="iconColorClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <svg v-else-if="icon === 'error'" class="w-6 h-6" :class="iconColorClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <svg v-else class="w-6 h-6" :class="iconColorClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
    </div>
    
    <!-- Progress bar -->
    <div v-if="progress !== null" class="mt-4">
      <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
        <div 
          class="h-full rounded-full transition-all duration-1000 ease-out"
          :class="progressColorClass"
          :style="{ width: `${Math.min(progress, 100)}%` }"
        ></div>
      </div>
      <p class="text-xs text-slate-400 mt-1">{{ Math.round(progress) }}% del inventario</p>
    </div>
    
    <!-- Sparkline mini chart -->
    <div v-if="sparklineData && sparklineData.length > 0" class="mt-4 h-8">
      <svg viewBox="0 0 100 20" class="w-full h-full" preserveAspectRatio="none">
        <path
          :d="sparklinePath"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          class="text-slate-300"
        />
        <path
          :d="sparklineAreaPath"
          fill="currentColor"
          class="opacity-10"
        />
      </svg>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { useDarkMode } from '../composables/useDarkMode'

const props = defineProps({
  title: { type: String, required: true },
  value: { type: [String, Number], default: 0 },
  prefix: { type: String, default: '' },
  suffix: { type: String, default: '' },
  icon: { type: String, default: 'package' },
  color: { type: String, default: 'blue' },
  loading: { type: Boolean, default: false },
  alert: { type: Boolean, default: false },
  trend: { type: Number, default: null },
  subtitle: { type: String, default: '' },
  progress: { type: Number, default: null },
  sparklineData: { type: Array, default: () => [] },
  animate: { type: Boolean, default: true },
})

const { isDark } = useDarkMode()

const displayValue = ref(0)

// Animated counter
watch(() => props.value, (newVal) => {
  if (props.loading || !props.animate) {
    displayValue.value = Number(newVal) || 0
    return
  }
  
  const numericValue = Number(newVal) || 0
  const startVal = displayValue.value
  const duration = 1000
  const startTime = performance.now()
  
  const animate = (currentTime) => {
    const elapsed = currentTime - startTime
    const progress = Math.min(elapsed / duration, 1)
    
    // Easing function (ease-out-cubic)
    const easeOut = 1 - Math.pow(1 - progress, 3)
    
    displayValue.value = Math.round(startVal + (numericValue - startVal) * easeOut)
    
    if (progress < 1) {
      requestAnimationFrame(animate)
    }
  }
  
  requestAnimationFrame(animate)
}, { immediate: true })

const iconBgClass = computed(() => {
  const colors = {
    blue: isDark.value ? 'bg-blue-500/20' : 'bg-blue-100',
    emerald: isDark.value ? 'bg-emerald-500/20' : 'bg-emerald-100',
    amber: isDark.value ? 'bg-amber-500/20' : 'bg-amber-100',
    rose: isDark.value ? 'bg-rose-500/20' : 'bg-rose-100',
    green: isDark.value ? 'bg-emerald-500/20' : 'bg-emerald-100',
    purple: isDark.value ? 'bg-purple-500/20' : 'bg-purple-100',
  }
  return colors[props.color] || colors.blue
})

const iconColorClass = computed(() => {
  const colors = {
    blue: isDark.value ? 'text-blue-400' : 'text-blue-600',
    emerald: isDark.value ? 'text-emerald-400' : 'text-emerald-600',
    amber: isDark.value ? 'text-amber-400' : 'text-amber-600',
    rose: isDark.value ? 'text-rose-400' : 'text-rose-600',
    green: isDark.value ? 'text-emerald-400' : 'text-emerald-600',
    purple: isDark.value ? 'text-purple-400' : 'text-purple-600',
  }
  return colors[props.color] || colors.blue
})

const progressColorClass = computed(() => {
  const colors = {
    blue: 'bg-blue-500',
    emerald: 'bg-emerald-500',
    green: 'bg-emerald-500',
    amber: 'bg-amber-500',
    rose: 'bg-rose-500',
    purple: 'bg-purple-500',
  }
  return colors[props.color] || colors.blue
})

// Sparkline path
const sparklinePath = computed(() => {
  if (!props.sparklineData.length) return ''
  
  const max = Math.max(...props.sparklineData)
  const min = Math.min(...props.sparklineData)
  const range = max - min || 1
  const width = 100
  const height = 20
  const step = width / (props.sparklineData.length - 1)
  
  return props.sparklineData.map((val, i) => {
    const x = i * step
    const y = height - ((val - min) / range) * height * 0.8 - height * 0.1
    return `${i === 0 ? 'M' : 'L'} ${x} ${y}`
  }).join(' ')
})

const sparklineAreaPath = computed(() => {
  if (!props.sparklineData.length) return ''
  return `${sparklinePath.value} L 100 20 L 0 20 Z`
})

// AnimatedNumber sub-component
const AnimatedNumber = {
  props: ['value', 'prefix', 'suffix', 'loading'],
  template: `
    <span v-if="!loading">{{ prefix }}{{ formattedValue }}{{ suffix }}</span>
    <span v-else class="inline-block w-16 h-6 bg-slate-200 rounded animate-pulse"></span>
  `,
  computed: {
    formattedValue() {
      return this.value.toLocaleString()
    }
  }
}
</script>
