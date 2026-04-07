<template>
  <div id="app" class="min-h-screen bg-cj-navy text-white antialiased selection:bg-cj-electric selection:text-white relative">
    <!-- Background gradient overlay -->
    <div class="fixed inset-0 bg-gradient-radial pointer-events-none z-0"></div>
    <div class="fixed inset-0 bg-gradient-mesh pointer-events-none z-0"></div>
    
    <!-- Content -->
    <div class="relative z-10">
      <RouterView />
    </div>
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth'

const authStore = useAuthStore()
authStore.initializeAuth()
</script>

<style>
/* Subtle pattern overlay */
#app::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  pointer-events: none;
  z-index: 1;
}
</style>
