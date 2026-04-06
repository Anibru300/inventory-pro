<template>
  <div class="min-h-screen bg-bg-primary flex items-center justify-center p-4">
    <div class="text-center">
      <div class="w-24 h-24 bg-bg-secondary rounded-2xl flex items-center justify-center mx-auto mb-6">
        <svg class="w-12 h-12 text-text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <h1 class="text-6xl font-bold gradient-text mb-4">404</h1>
      <h2 class="text-2xl font-semibold mb-2">Página no encontrada</h2>
      <p class="text-text-secondary mb-6">La página que buscas no existe o ha sido movida.</p>
      <router-link to="/" class="btn-primary inline-flex">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Volver al Dashboard
      </router-link>
    </div>
  </div>
</template>