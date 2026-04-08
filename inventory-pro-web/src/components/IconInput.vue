<template>
  <div class="relative">
    <label v-if="label" class="block text-sm font-medium mb-2" 
      :class="isDark ? 'text-slate-300' : 'text-slate-700'">
      {{ label }}
    </label>
    
    <div class="relative">
      <!-- Left Icon -->
      <div v-if="iconLeft" class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <component :is="iconLeft" class="w-5 h-5" :class="iconClass" />
      </div>
      
      <!-- Currency Symbol -->
      <div v-if="currency" class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <span class="text-lg font-medium" :class="isDark ? 'text-slate-400' : 'text-slate-500'">$</span>
      </div>

      <input 
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :type="type"
        :placeholder="placeholder"
        :disabled="disabled"
        class="w-full rounded-xl border transition-all focus:outline-none focus:ring-2"
        :class="[
          // Padding based on left content
          iconLeft || currency ? 'pl-12' : 'pl-4',
          iconRight ? 'pr-12' : 'pr-4',
          'py-3',
          // Dark mode styles
          isDark 
            ? 'bg-slate-800 border-slate-600 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500/30' 
            : 'bg-white border-slate-200 text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500/20',
          // Error state
          error ? (isDark ? 'border-rose-500 focus:border-rose-500' : 'border-rose-500 focus:border-rose-500 focus:ring-rose-200') : '',
          // Disabled state
          disabled ? 'opacity-50 cursor-not-allowed' : ''
        ]"
      />

      <!-- Right Icon -->
      <div v-if="iconRight" class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
        <component :is="iconRight" class="w-5 h-5" :class="iconClass" />
      </div>
    </div>
    
    <!-- Error Message -->
    <p v-if="error" class="mt-1 text-sm text-rose-500">{{ error }}</p>
    
    <!-- Hint -->
    <p v-else-if="hint" class="mt-1 text-sm" :class="isDark ? 'text-slate-400' : 'text-slate-500'">
      {{ hint }}
    </p>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useDarkMode } from '../composables/useDarkMode'

const props = defineProps({
  modelValue: { type: [String, Number], default: '' },
  label: { type: String, default: '' },
  type: { type: String, default: 'text' },
  placeholder: { type: String, default: '' },
  iconLeft: { type: Object, default: null },
  iconRight: { type: Object, default: null },
  currency: { type: Boolean, default: false },
  error: { type: String, default: '' },
  hint: { type: String, default: '' },
  disabled: { type: Boolean, default: false }
})

defineEmits(['update:modelValue'])

const { isDark } = useDarkMode()

const iconClass = computed(() => {
  return isDark.value ? 'text-slate-400' : 'text-slate-400'
})
</script>
