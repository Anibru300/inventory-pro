<template>
  <div id="app" class="min-h-screen bg-bg-primary text-text-primary antialiased selection:bg-accent-primary selection:text-white">
    <RouterView />
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth'

const authStore = useAuthStore()
authStore.initializeAuth()
</script>
