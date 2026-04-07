<template>
  <div id="app" class="min-h-screen bg-cj-navy text-white antialiased selection:bg-cj-electric selection:text-white">
    <RouterView />
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth'

const authStore = useAuthStore()
authStore.initializeAuth()
</script>
