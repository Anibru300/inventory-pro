<template>
  <div id="app" class="min-h-screen bg-bg-primary text-text-primary antialiased">
    <RouterView />
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth'

const authStore = useAuthStore()

// Initialize auth on app start
authStore.initializeAuth()
</script>