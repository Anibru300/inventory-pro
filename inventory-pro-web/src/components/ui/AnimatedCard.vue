<template>
  <div 
    class="group bg-white rounded-2xl border border-slate-100 overflow-hidden transition-all duration-300"
    :class="{
      'shadow-lg hover:shadow-xl hover:-translate-y-1': hover,
      'shadow-md': !hover,
      'cursor-pointer': clickable,
    }"
    @click="$emit('click')"
  >
    <div v-if="image" class="relative overflow-hidden">
      <img 
        :src="image" 
        :alt="title"
        class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105"
      >
      <div v-if="badge" class="absolute top-4 right-4">
        <span class="px-3 py-1 text-xs font-semibold rounded-full" :class="badgeClass">{{ badge }}</span>
      </div>
    </div>
    
    <div class="p-6">
      <div v-if="icon" class="mb-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-colors duration-300" :class="iconBgClass">
          <component :is="icon" class="w-6 h-6" :class="iconClass" />
        </div>
      </div>
      
      <h3 v-if="title" class="text-lg font-semibold text-slate-800 mb-2">{{ title }}</h3>
      <p v-if="description" class="text-slate-500 text-sm leading-relaxed">{{ description }}</p>
      
      <slot />
    </div>
    
    <div v-if="$slots.footer" class="px-6 py-4 bg-slate-50 border-t border-slate-100">
      <slot name="footer" />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: String,
  description: String,
  image: String,
  icon: [Object, Function],
  badge: String,
  badgeColor: {
    type: String,
    default: 'blue', // blue, green, amber, rose, purple
  },
  iconBg: {
    type: String,
    default: 'blue', // blue, green, amber, rose, purple, slate
  },
  hover: {
    type: Boolean,
    default: true,
  },
  clickable: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['click'])

const badgeClass = computed(() => {
  const colors = {
    blue: 'bg-blue-100 text-blue-700',
    green: 'bg-emerald-100 text-emerald-700',
    amber: 'bg-amber-100 text-amber-700',
    rose: 'bg-rose-100 text-rose-700',
    purple: 'bg-purple-100 text-purple-700',
  }
  return colors[props.badgeColor] || colors.blue
})

const iconBgClass = computed(() => {
  const colors = {
    blue: 'bg-blue-100 group-hover:bg-blue-600',
    green: 'bg-emerald-100 group-hover:bg-emerald-600',
    amber: 'bg-amber-100 group-hover:bg-amber-600',
    rose: 'bg-rose-100 group-hover:bg-rose-600',
    purple: 'bg-purple-100 group-hover:bg-purple-600',
    slate: 'bg-slate-100 group-hover:bg-slate-600',
  }
  return colors[props.iconBg] || colors.blue
})

const iconClass = computed(() => {
  const colors = {
    blue: 'text-blue-600 group-hover:text-white',
    green: 'text-emerald-600 group-hover:text-white',
    amber: 'text-amber-600 group-hover:text-white',
    rose: 'text-rose-600 group-hover:text-white',
    purple: 'text-purple-600 group-hover:text-white',
    slate: 'text-slate-600 group-hover:text-white',
  }
  return colors[props.iconBg] || colors.blue
})
</script>
