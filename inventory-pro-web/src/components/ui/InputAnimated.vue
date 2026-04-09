<template>
  <div class="relative">
    <label 
      v-if="label"
      class="block text-sm font-medium mb-2 transition-colors duration-200"
      :class="focused ? 'text-blue-600' : 'text-slate-700'"
    >
      {{ label }}
      <span v-if="required" class="text-rose-500">*</span>
    </label>
    
    <div class="relative">
      <!-- Left icon -->
      <div v-if="icon" class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none transition-colors duration-200" :class="iconClass">
        <component :is="icon" class="w-5 h-5" />
      </div>
      
      <input
        :type="showPassword && type === 'password' ? 'text' : type"
        :value="modelValue"
        :placeholder="placeholder"
        :required="required"
        :disabled="disabled"
        :class="[
          'w-full bg-slate-50 border rounded-xl transition-all duration-200 outline-none',
          'focus:ring-2 focus:ring-blue-500/20',
          icon ? 'pl-12' : 'pl-4',
          hasRightContent ? 'pr-12' : 'pr-4',
          sizeClass,
          inputClass,
        ]"
        @input="$emit('update:modelValue', $event.target.value)"
        @focus="focused = true"
        @blur="focused = false"
      >
      
      <!-- Password toggle -->
      <button
        v-if="type === 'password'"
        type="button"
        class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 transition-colors"
        @click="showPassword = !showPassword"
      >
        <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        </svg>
        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
      </button>
      
      <!-- Clear button -->
      <button
        v-if="clearable && modelValue"
        type="button"
        class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 transition-colors"
        @click="$emit('update:modelValue', '')"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    
    <!-- Helper text or error -->
    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="transform -translate-y-1 opacity-0"
      enter-to-class="transform translate-y-0 opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="transform translate-y-0 opacity-100"
      leave-to-class="transform -translate-y-1 opacity-0"
    >
      <p v-if="error" class="mt-1.5 text-sm text-rose-600 flex items-center gap-1">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ error }}
      </p>
      <p v-else-if="helper" class="mt-1.5 text-sm text-slate-500">{{ helper }}</p>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  modelValue: [String, Number],
  label: String,
  type: {
    type: String,
    default: 'text',
  },
  placeholder: String,
  required: Boolean,
  disabled: Boolean,
  clearable: Boolean,
  icon: [Object, Function],
  error: String,
  helper: String,
  size: {
    type: String,
    default: 'md', // sm, md, lg
  },
  state: {
    type: String,
    default: 'default', // default, error, success
  },
})

defineEmits(['update:modelValue'])

const focused = ref(false)
const showPassword = ref(false)

const hasRightContent = computed(() => {
  return props.type === 'password' || (props.clearable && props.modelValue)
})

const sizeClass = computed(() => {
  const sizes = {
    sm: 'py-2 text-sm',
    md: 'py-3 text-base',
    lg: 'py-4 text-lg',
  }
  return sizes[props.size] || sizes.md
})

const inputClass = computed(() => {
  if (props.error || props.state === 'error') {
    return 'border-rose-300 focus:border-rose-500 text-rose-900 placeholder-rose-300'
  }
  if (props.state === 'success') {
    return 'border-emerald-300 focus:border-emerald-500 text-emerald-900'
  }
  return focused.value 
    ? 'border-blue-500 bg-white' 
    : 'border-slate-200 hover:border-slate-300'
})

const iconClass = computed(() => {
  if (props.error || props.state === 'error') return 'text-rose-400'
  if (props.state === 'success') return 'text-emerald-400'
  return focused.value ? 'text-blue-500' : 'text-slate-400'
})
</script>
