<template>
  <div class="relative">
    <div class="relative">
      <input
        v-model="searchQuery"
        type="text"
        :placeholder="placeholder"
        class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
        @input="handleInput"
        @keyup.enter="$emit('search', debouncedQuery)"
      />
      
      <!-- Search icon -->
      <svg 
        class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"
        fill="none" 
        stroke="currentColor" 
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
      
      <!-- Clear button -->
      <button
        v-if="searchQuery"
        @click="clearSearch"
        class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 transition-colors"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    
    <!-- Loading indicator -->
    <div 
      v-if="isSearching"
      class="absolute right-3 top-1/2 -translate-y-1/2"
    >
      <svg class="w-4 h-4 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useDebounce } from '@/composables/usePerformance'

const props = defineProps({
  placeholder: {
    type: String,
    default: 'Buscar...'
  },
  modelValue: {
    type: String,
    default: ''
  },
  debounceMs: {
    type: Number,
    default: 300
  },
  isSearching: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'search'])

const searchQuery = ref(props.modelValue)
let debounceTimeout = null

const handleInput = () => {
  emit('update:modelValue', searchQuery.value)
  
  // Clear existing timeout
  if (debounceTimeout) {
    clearTimeout(debounceTimeout)
  }
  
  // Set new timeout
  debounceTimeout = setTimeout(() => {
    emit('search', searchQuery.value)
  }, props.debounceMs)
}

const clearSearch = () => {
  searchQuery.value = ''
  emit('update:modelValue', '')
  emit('search', '')
  if (debounceTimeout) {
    clearTimeout(debounceTimeout)
  }
}

// Sync with external modelValue
watch(() => props.modelValue, (newValue) => {
  searchQuery.value = newValue
})
</script>
