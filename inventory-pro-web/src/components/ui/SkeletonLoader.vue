<template>
  <div class="animate-pulse">
    <div :class="['bg-slate-200 rounded', skeletonClass]"></div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: {
    type: String,
    default: 'text', // text, title, card, circle, avatar
  },
  lines: {
    type: Number,
    default: 1,
  },
})

const skeletonClass = computed(() => {
  switch (props.type) {
    case 'title':
      return 'h-8 w-3/4 mb-4'
    case 'card':
      return 'h-32 w-full mb-4'
    case 'circle':
      return 'h-12 w-12 rounded-full'
    case 'avatar':
      return 'h-10 w-10 rounded-full'
    case 'button':
      return 'h-10 w-24'
    case 'input':
      return 'h-12 w-full'
    default:
      return 'h-4 w-full mb-2'
  }
})
</script>
