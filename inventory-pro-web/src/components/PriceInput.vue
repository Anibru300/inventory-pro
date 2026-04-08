<template>
  <div class="relative">
    <label v-if="label" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">
      {{ label }}
    </label>
    <div class="relative">
      <!-- Currency Symbol Prefix -->
      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <span class="text-slate-400 font-medium">$</span>
      </div>
      
      <input 
        :value="modelValue"
        @input="handleInput"
        type="number" 
        :step="step"
        :min="min"
        :placeholder="placeholder"
        class="w-full pl-8 pr-4 py-3 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900 transition-all"
        :class="{ 'border-rose-500 focus:border-rose-500 focus:ring-rose-200': error }"
      />
    </div>
    <p v-if="error" class="mt-1 text-sm text-rose-600">{{ error }}</p>
    <p v-else-if="hint" class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ hint }}</p>
  </div>
</template>

<script setup>
const props = defineProps({
  modelValue: { type: [Number, String], default: '' },
  label: { type: String, default: '' },
  placeholder: { type: String, default: '0.00' },
  step: { type: String, default: '0.01' },
  min: { type: Number, default: 0 },
  error: { type: String, default: '' },
  hint: { type: String, default: '' }
})

const emit = defineEmits(['update:modelValue'])

function handleInput(event) {
  emit('update:modelValue', event.target.value)
}
</script>
