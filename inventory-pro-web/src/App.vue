<template>
  <div id="app" class="min-h-screen antialiased selection:bg-blue-500/30 dark:selection:bg-cj-gold/30 relative overflow-hidden"
    :class="isDark ? 'bg-cj-navy text-white' : 'bg-slate-50 text-slate-900'">
    
    <!-- Background layers - Dark Mode -->
    <template v-if="isDark">
      <div class="fixed inset-0 bg-gradient-radial pointer-events-none z-0"></div>
      <div class="fixed inset-0 bg-gradient-mesh pointer-events-none z-0"></div>
      <div class="fixed inset-0 bg-gradient-gold pointer-events-none z-0"></div>
      
      <!-- Decorative elements -->
      <div class="fixed top-0 left-0 w-full h-full pointer-events-none z-0">
        <div class="absolute top-20 left-10 w-96 h-96 bg-cj-gold/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-[500px] h-[500px] bg-cj-blue/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-cj-navy-light/50 rounded-full blur-3xl"></div>
      </div>
    </template>
    
    <!-- Content -->
    <div class="relative z-10">
      <RouterView />
    </div>
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth'
import { ref, onMounted } from 'vue'

const authStore = useAuthStore()
authStore.initializeAuth()

const isDark = ref(false)

onMounted(() => {
  // Check for saved dark mode preference
  isDark.value = localStorage.getItem('darkMode') === 'true'
  document.documentElement.classList.toggle('dark', isDark.value)
  
  // Listen for dark mode changes from other components
  window.addEventListener('storage', (e) => {
    if (e.key === 'darkMode') {
      isDark.value = e.newValue === 'true'
      document.documentElement.classList.toggle('dark', isDark.value)
    }
  })
})
</script>

<style>
/* Subtle grid pattern - Dark mode only */
#app.dark::before,
#app:has(.dark)::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: 
    linear-gradient(rgba(201, 169, 98, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(201, 169, 98, 0.03) 1px, transparent 1px);
  background-size: 50px 50px;
  pointer-events: none;
  z-index: 1;
}
</style>
