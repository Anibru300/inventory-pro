<template>
  <div id="app" class="min-h-screen antialiased relative overflow-hidden transition-colors duration-300"
    :class="isDark ? 'bg-slate-900 text-slate-100' : 'bg-slate-50 text-slate-900'">
    
    <!-- Background Pattern - Dark Mode -->
    <div v-if="isDark" class="fixed inset-0 pointer-events-none z-0">
      <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
      <div class="absolute top-20 left-10 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl"></div>
      <div class="absolute bottom-20 right-10 w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-3xl"></div>
      <!-- Grid pattern -->
      <div class="absolute inset-0 opacity-5" 
        style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 50px 50px;">
      </div>
    </div>

    <!-- Background Pattern - Light Mode -->
    <div v-else class="fixed inset-0 pointer-events-none z-0">
      <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-100"></div>
      <div class="absolute top-20 right-10 w-96 h-96 bg-blue-200/30 rounded-full blur-3xl"></div>
      <div class="absolute bottom-20 left-10 w-[400px] h-[400px] bg-indigo-200/20 rounded-full blur-3xl"></div>
    </div>
    
    <!-- Content -->
    <div class="relative z-10">
      <RouterView />
    </div>
    
    <!-- Offline Status -->
    <OfflineStatusBar v-if="authStore.isAuthenticated" />
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router'
import { onMounted } from 'vue'
import { useAuthStore } from './stores/auth'
import { useDarkMode } from './composables/useDarkMode'
import { syncService } from './services/syncService'
import OfflineStatusBar from './components/OfflineStatusBar.vue'

const authStore = useAuthStore()
authStore.initializeAuth()

const { isDark } = useDarkMode()

// Initialize sync service when app loads
onMounted(() => {
  if (authStore.isAuthenticated) {
    syncService.init()
  }
})
</script>

<style>
/* Global dark mode transitions */
* {
  transition-property: background-color, border-color, color, fill, stroke;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

/* Scrollbar styling for dark mode */
.dark ::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

.dark ::-webkit-scrollbar-track {
  background: #1e293b;
}

.dark ::-webkit-scrollbar-thumb {
  background: #475569;
  border-radius: 4px;
}

.dark ::-webkit-scrollbar-thumb:hover {
  background: #64748b;
}

/* Light mode scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>
