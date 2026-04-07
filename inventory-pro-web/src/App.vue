<template>
  <div id="app" class="min-h-screen bg-bg-primary text-text-primary antialiased selection:bg-accent-primary selection:text-white">
    <RouterView />
  </div>
</template>

<script setup>
// App root component - v2.0
</script>

<script setup>
import { RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth'

const authStore = useAuthStore()

// Initialize auth on app start
authStore.initializeAuth()
</script>