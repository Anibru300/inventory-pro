<template>
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <!-- Left: Back + Title -->
    <div class="flex items-center gap-4">
      <!-- Botón Menú Principal -->
      <button
        @click="$router.push('/menu')"
        class="p-2 rounded-xl transition-all flex items-center gap-2 font-medium"
        :class="isDark 
          ? 'text-slate-300 hover:text-white hover:bg-slate-800' 
          : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
        title="Ir al Menú Principal"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
        </svg>
        <span class="hidden sm:block text-sm">Menú</span>
      </button>

      <div class="w-px h-8" :class="isDark ? 'bg-slate-700' : 'bg-slate-200'"></div>

      <!-- Back button if provided -->
      <button
        v-if="showBack"
        @click="$router.back()"
        class="p-2 rounded-xl transition-all"
        :class="isDark 
          ? 'text-slate-400 hover:text-slate-200 hover:bg-slate-800' 
          : 'text-slate-400 hover:text-slate-600 hover:bg-slate-100'"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
      </button>

      <!-- Title -->
      <div>
        <h1 class="text-2xl md:text-3xl font-bold" :class="isDark ? 'text-white' : 'text-slate-800'">
          {{ title }}
        </h1>
        <p v-if="subtitle" class="text-sm mt-1" :class="isDark ? 'text-slate-400' : 'text-slate-500'">
          {{ subtitle }}
        </p>
      </div>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-3">
      <!-- Actions slot -->
      <slot name="actions"></slot>

      <!-- Fullscreen Toggle -->
      <button
        @click="toggleFullscreen"
        class="p-2 rounded-xl transition-all"
        :class="isDark 
          ? 'text-slate-400 hover:text-white hover:bg-slate-800' 
          : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
        :title="isFullscreen ? 'Salir de pantalla completa' : 'Pantalla completa'"
      >
        <!-- Expand icon -->
        <svg v-if="!isFullscreen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
        </svg>
        <!-- Compress icon -->
        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useDarkMode } from '../composables/useDarkMode'

const props = defineProps({
  title: { type: String, required: true },
  subtitle: { type: String, default: '' },
  showBack: { type: Boolean, default: false }
})

const { isDark } = useDarkMode()
const isFullscreen = ref(false)

const toggleFullscreen = () => {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen().then(() => {
      isFullscreen.value = true
    }).catch(err => {
      console.error('Error attempting to enable fullscreen:', err)
    })
  } else {
    document.exitFullscreen().then(() => {
      isFullscreen.value = false
    }).catch(err => {
      console.error('Error attempting to exit fullscreen:', err)
    })
  }
}

const handleFullscreenChange = () => {
  isFullscreen.value = !!document.fullscreenElement
}

onMounted(() => {
  document.addEventListener('fullscreenchange', handleFullscreenChange)
})

onUnmounted(() => {
  document.removeEventListener('fullscreenchange', handleFullscreenChange)
})
</script>
