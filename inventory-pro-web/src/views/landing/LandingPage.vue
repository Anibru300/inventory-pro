<template>
  <div class="min-h-screen overflow-x-hidden transition-colors duration-300" :class="isDark ? 'bg-[#0a1628]' : 'bg-slate-50'">
    <!-- Animated Professional Background -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
      <!-- Base Gradient -->
      <div class="absolute inset-0 transition-colors duration-500" :class="isDark ? 'bg-gradient-to-br from-[#0a1628] via-[#0d1d32] to-[#152238]' : 'bg-gradient-to-br from-slate-100 via-white to-blue-50'"></div>
      
      <!-- Animated Orbs -->
      <div class="absolute top-0 left-1/4 w-[600px] h-[600px] bg-[#2E7DE8]/20 rounded-full blur-[100px] animate-pulse"></div>
      <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-[#2E7DE8]/15 rounded-full blur-[80px]" style="animation: pulse 4s ease-in-out infinite; animation-delay: 1s;"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-r from-[#2E7DE8]/10 to-transparent rounded-full blur-[120px]" style="animation: pulse 6s ease-in-out infinite; animation-delay: 2s;"></div>
      
      <!-- Silver Accent Lines -->
      <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#C0C0C0]/30 to-transparent"></div>
      <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#C0C0C0]/30 to-transparent"></div>
      
      <!-- Animated Grid Pattern - Visible in both modes -->
      <div class="absolute inset-0 opacity-[0.03]" 
        style="background-image: 
          linear-gradient(rgba(46,125,232,0.8) 1px, transparent 1px),
          linear-gradient(90deg, rgba(46,125,232,0.8) 1px, transparent 1px);
        background-size: 50px 50px;
        animation: gridMove 20s linear infinite;">
      </div>
      
      <!-- Floating Geometric Shapes -->
      <div class="absolute top-20 left-[10%] w-20 h-20 border-2 border-[#2E7DE8]/30 rotate-45 animate-float" style="animation-delay: 0s;"></div>
      <div class="absolute top-40 right-[15%] w-16 h-16 border-2 border-[#C0C0C0]/30 rounded-full animate-float" style="animation-delay: 1s;"></div>
      <div class="absolute bottom-32 left-[20%] w-24 h-24 border-2 border-[#2E7DE8]/20 rotate-12 animate-float" style="animation-delay: 2s;"></div>
      <div class="absolute top-1/3 right-[10%] w-12 h-12 bg-[#2E7DE8]/10 rotate-45 animate-float" style="animation-delay: 1.5s;"></div>
      <div class="absolute bottom-1/4 right-[25%] w-32 h-1 bg-gradient-to-r from-transparent via-[#C0C0C0]/30 to-transparent animate-float" style="animation-delay: 0.5s;"></div>
      
      <!-- Connection Lines (Consulting Network Effect) -->
      <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <linearGradient id="lineGrad" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#2E7DE8;stop-opacity:0" />
            <stop offset="50%" style="stop-color:#2E7DE8;stop-opacity:0.3" />
            <stop offset="100%" style="stop-color:#2E7DE8;stop-opacity:0" />
          </linearGradient>
        </defs>
        <line x1="10%" y1="20%" x2="30%" y2="40%" stroke="url(#lineGrad)" stroke-width="1" class="animate-draw-line" style="animation-delay: 0s;"/>
        <line x1="70%" y1="15%" x2="85%" y2="35%" stroke="url(#lineGrad)" stroke-width="1" class="animate-draw-line" style="animation-delay: 0.5s;"/>
        <line x1="20%" y1="70%" x2="40%" y2="85%" stroke="url(#lineGrad)" stroke-width="1" class="animate-draw-line" style="animation-delay: 1s;"/>
        <line x1="60%" y1="60%" x2="80%" y2="75%" stroke="url(#lineGrad)" stroke-width="1" class="animate-draw-line" style="animation-delay: 1.5s;"/>
        <line x1="5%" y1="50%" x2="25%" y2="30%" stroke="url(#lineGrad)" stroke-width="1" class="animate-draw-line" style="animation-delay: 2s;"/>
      </svg>
      
      <!-- Floating Particles -->
      <div class="absolute top-[15%] left-[8%] w-2 h-2 bg-[#C0C0C0] rounded-full animate-particle" style="animation-delay: 0s;"></div>
      <div class="absolute top-[25%] right-[12%] w-3 h-3 bg-[#2E7DE8]/50 rounded-full animate-particle" style="animation-delay: 1s;"></div>
      <div class="absolute top-[60%] left-[15%] w-2 h-2 bg-[#C0C0C0]/70 rounded-full animate-particle" style="animation-delay: 0.5s;"></div>
      <div class="absolute top-[45%] right-[8%] w-4 h-4 bg-[#2E7DE8]/30 rounded-full animate-particle" style="animation-delay: 1.5s;"></div>
      <div class="absolute top-[80%] left-[25%] w-2 h-2 bg-[#C0C0C0]/50 rounded-full animate-particle" style="animation-delay: 2s;"></div>
      <div class="absolute top-[35%] left-[35%] w-3 h-3 bg-[#2E7DE8]/20 rounded-full animate-particle" style="animation-delay: 0.8s;"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300" 
      :class="[scrolled ? (isDark ? 'bg-[#0a1628]/95 backdrop-blur-md shadow-lg border-b border-[#2E7DE8]/30' : 'bg-white/95 backdrop-blur-md shadow-lg') : 'bg-transparent']">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
          <!-- Logo -->
          <div class="flex items-center gap-3 cursor-pointer" @click="scrollToSection('top')">
            <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-lg overflow-hidden border-2 border-[#2E7DE8] bg-white">
              <img src="/logo-cj-main.png" alt="CJ Consultoría" class="w-full h-full object-contain" />
            </div>
            <div>
              <span class="text-2xl font-black tracking-tight" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">StockWolf</span>
              <span class="block text-xs font-medium tracking-widest uppercase" :class="isDark ? 'text-[#2E7DE8]' : 'text-[#2E7DE8]'">by CJ Consultoría</span>
            </div>
          </div>

          <!-- Desktop Menu -->
          <div class="hidden md:flex items-center gap-8">
            <button @click="scrollToSection('features')" class="text-sm font-medium transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-300' : 'text-gray-600'">Características</button>
            <button @click="scrollToSection('demo-interactive')" class="text-sm font-medium transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-300' : 'text-gray-600'">Cómo Funciona</button>
            <button @click="scrollToSection('pricing')" class="text-sm font-medium transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-300' : 'text-gray-600'">Precios</button>
            <button @click="scrollToSection('blog')" class="text-sm font-medium transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-300' : 'text-gray-600'">Blog</button>
            <a href="https://anibru300.github.io/cj-consultoria-web/" target="_blank" class="text-sm font-medium transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-300' : 'text-gray-600'">Consultoría</a>
          </div>

          <!-- CTA Buttons -->
          <div class="flex items-center gap-4">
            <button @click="toggleDarkMode" class="p-2.5 rounded-xl transition-all duration-300 border" 
              :class="isDark ? 'bg-[#1a2d47] border-[#2E7DE8]/30 text-yellow-400 hover:bg-[#243552]' : 'bg-slate-100 border-slate-200 text-slate-600 hover:bg-slate-200'">
              <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
              </svg>
            </button>
            <router-link to="/login" class="hidden sm:block text-sm font-medium transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
              Iniciar Sesión
            </router-link>
            <router-link to="/register" class="hidden sm:block px-5 py-2.5 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] hover:from-[#1e6ad1] hover:to-[#2E7DE8] text-white rounded-xl font-semibold transition-all shadow-lg shadow-[#2E7DE8]/30 hover:shadow-[#2E7DE8]/50">
              Prueba Gratis
            </router-link>
            
            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg" :class="isDark ? 'text-white hover:bg-[#1a2d47]' : 'text-gray-700 hover:bg-gray-100'">
              <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        
        <!-- Mobile Menu -->
        <div v-show="mobileMenuOpen" class="md:hidden py-4 border-t" :class="isDark ? 'border-gray-700' : 'border-gray-200'">
          <div class="flex flex-col gap-3">
            <button @click="scrollToSection('features'); mobileMenuOpen = false" class="text-left px-4 py-2 rounded-lg transition-colors" :class="isDark ? 'text-gray-300 hover:bg-[#1a2d47]' : 'text-gray-600 hover:bg-gray-100'">Características</button>
            <button @click="scrollToSection('demo-interactive'); mobileMenuOpen = false" class="text-left px-4 py-2 rounded-lg transition-colors" :class="isDark ? 'text-gray-300 hover:bg-[#1a2d47]' : 'text-gray-600 hover:bg-gray-100'">Cómo Funciona</button>
            <button @click="scrollToSection('pricing'); mobileMenuOpen = false" class="text-left px-4 py-2 rounded-lg transition-colors" :class="isDark ? 'text-gray-300 hover:bg-[#1a2d47]' : 'text-gray-600 hover:bg-gray-100'">Precios</button>
            <button @click="scrollToSection('blog'); mobileMenuOpen = false" class="text-left px-4 py-2 rounded-lg transition-colors" :class="isDark ? 'text-gray-300 hover:bg-[#1a2d47]' : 'text-gray-600 hover:bg-gray-100'">Blog</button>
            <a href="https://anibru300.github.io/cj-consultoria-web/" target="_blank" @click="mobileMenuOpen = false" class="text-left px-4 py-2 rounded-lg transition-colors" :class="isDark ? 'text-gray-300 hover:bg-[#1a2d47]' : 'text-gray-600 hover:bg-gray-100'">Consultoría</a>
            <router-link to="/login" @click="mobileMenuOpen = false" class="text-left px-4 py-2 rounded-lg transition-colors" :class="isDark ? 'text-gray-300 hover:bg-[#1a2d47]' : 'text-gray-600 hover:bg-gray-100'">Iniciar Sesión</router-link>
            <router-link to="/register" @click="mobileMenuOpen = false" class="mx-4 px-5 py-2.5 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] text-white rounded-xl font-medium text-center">
              Prueba Gratis
            </router-link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section id="top" class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div class="text-center lg:text-left">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-6 border backdrop-blur-sm"
              :class="isDark ? 'bg-[#2E7DE8]/20 border-[#2E7DE8]/50 text-[#2E7DE8]' : 'bg-blue-50 border-blue-200 text-[#2E7DE8]'">
              <span class="flex h-2 w-2 rounded-full bg-[#2E7DE8] animate-pulse"></span>
              <span class="text-sm font-semibold">🐺 Desarrollado por CJ Consultoría</span>
            </div>
            
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black leading-tight mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
              StockWolf
              <span class="block text-2xl md:text-3xl lg:text-4xl mt-3 font-bold" :class="isDark ? 'text-[#2E7DE8]' : 'text-[#2E7DE8]'" style="font-family: 'Playfair Display', serif;">
                La fuerza de tu inventario
              </span>
            </h1>
            
            <p class="text-lg md:text-xl mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
              Sistema ERP profesional para gestión de inventario multi-almacén. 
              Desarrollado por <strong class="text-[#2E7DE8]">CJ Consultoría</strong> para PYMEs y emprendedores que buscan resultados.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
              <router-link to="/register" class="px-8 py-4 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] hover:from-[#1e6ad1] hover:to-[#2E7DE8] text-white rounded-xl font-semibold transition-all shadow-xl shadow-[#2E7DE8]/30 hover:shadow-[#2E7DE8]/50 flex items-center justify-center gap-2 group">
                Comenzar Prueba Gratis
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </router-link>
              <button @click="scrollToSection('demo-form')" class="px-8 py-4 border-2 rounded-xl font-semibold transition-all flex items-center justify-center gap-2 border-[#2E7DE8] text-[#2E7DE8] hover:bg-[#2E7DE8] hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Solicitar Demo
              </button>
            </div>

            <!-- Trust Badges -->
            <div class="mt-10 flex flex-wrap items-center justify-center lg:justify-start gap-6">
              <div class="flex items-center gap-2" :class="isDark ? 'text-gray-400' : 'text-gray-500'">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">14 días gratis</span>
              </div>
              <div class="flex items-center gap-2" :class="isDark ? 'text-gray-400' : 'text-gray-500'">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">Sin tarjeta</span>
              </div>
              <div class="flex items-center gap-2" :class="isDark ? 'text-gray-400' : 'text-gray-500'">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">Soporte técnico</span>
              </div>
            </div>
          </div>

          <!-- Hero Image -->
          <div class="relative flex justify-center items-center">
            <div class="absolute -inset-4 bg-gradient-to-r from-[#2E7DE8] to-[#C0C0C0] rounded-full opacity-20 blur-3xl"></div>
            <div class="relative w-[400px] h-[400px] lg:w-[500px] lg:h-[500px] flex items-center justify-center">
              <img src="/logo-cj-sin-fondo.png" alt="CJ Consultoría" class="w-full h-full object-contain drop-shadow-2xl" />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 border-y relative" :class="isDark ? 'border-[#2E7DE8]/20 bg-[#0a1628]/50' : 'border-gray-200 bg-white/50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
          <div class="p-6 rounded-2xl backdrop-blur-sm transition-all hover:scale-105" :class="isDark ? 'bg-[#0d1d32]/80 border border-[#2E7DE8]/20' : 'bg-white border border-gray-100 shadow-lg'">
            <p class="text-4xl md:text-5xl font-black text-[#2E7DE8] mb-2">
              <AnimatedCounter :target="500" suffix="+" />
            </p>
            <p class="text-sm font-medium" :class="isDark ? 'text-gray-400' : 'text-gray-600'">PYMEs confían</p>
          </div>
          <div class="p-6 rounded-2xl backdrop-blur-sm transition-all hover:scale-105" :class="isDark ? 'bg-[#0d1d32]/80 border border-[#2E7DE8]/20' : 'bg-white border border-gray-100 shadow-lg'">
            <p class="text-4xl md:text-5xl font-black text-[#2E7DE8] mb-2">
              <AnimatedCounter :target="25" suffix="%" prefix="+" />
            </p>
            <p class="text-sm font-medium" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Eficiencia promedio</p>
          </div>
          <div class="p-6 rounded-2xl backdrop-blur-sm transition-all hover:scale-105" :class="isDark ? 'bg-[#0d1d32]/80 border border-[#2E7DE8]/20' : 'bg-white border border-gray-100 shadow-lg'">
            <p class="text-4xl md:text-5xl font-black text-[#2E7DE8] mb-2">
              <AnimatedCounter :target="80" suffix="%" />
            </p>
            <p class="text-sm font-medium" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Tiempo ahorrado</p>
          </div>
          <div class="p-6 rounded-2xl backdrop-blur-sm transition-all hover:scale-105" :class="isDark ? 'bg-[#0d1d32]/80 border border-[#2E7DE8]/20' : 'bg-white border border-gray-100 shadow-lg'">
            <p class="text-4xl md:text-5xl font-black text-[#2E7DE8] mb-2">99.9%</p>
            <p class="text-sm font-medium" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Uptime garantizado</p>
          </div>
        </div>
      </div>
    </section>


    <!-- Features Section -->
    <section id="features" class="py-24 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <span class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold mb-4 border" :class="isDark ? 'bg-[#2E7DE8]/20 border-[#2E7DE8]/50 text-[#2E7DE8]' : 'bg-blue-50 border-blue-200 text-[#2E7DE8]'">
            Características
          </span>
          <h2 class="text-4xl md:text-5xl font-black mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Todo lo que necesitas
          </h2>
          <p class="text-lg leading-relaxed" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
            Desde el control básico hasta análisis avanzados, StockWolf tiene las herramientas que tu negocio necesita para dominar su inventario.
          </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="feature in features" :key="feature.title" 
            class="p-8 rounded-2xl border transition-all duration-300 hover:shadow-xl hover:-translate-y-1 group"
            :class="isDark ? 'bg-[#0d1d32]/80 border-[#2E7DE8]/20 hover:border-[#2E7DE8]/50' : 'bg-white border-gray-100 shadow-lg hover:border-[#2E7DE8]/30'">
            <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-6 transition-colors" :class="isDark ? 'bg-[#2E7DE8]/20 group-hover:bg-[#2E7DE8]/30' : 'bg-blue-50 group-hover:bg-blue-100'">
              <svg class="w-7 h-7 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path v-if="feature.icon === 'inventory'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                <path v-else-if="feature.icon === 'barcode'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM8 9h2v4H8V9zm8 0h2v4h-2V9zM6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z" />
                <path v-else-if="feature.icon === 'chart'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                <path v-else-if="feature.icon === 'alert'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                <path v-else-if="feature.icon === 'users'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-3" :class="isDark ? 'text-white group-hover:text-[#2E7DE8]' : 'text-gray-900'">{{ feature.title }}</h3>
            <p class="leading-relaxed" :class="isDark ? 'text-gray-400' : 'text-gray-600'">{{ feature.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- MEJORADO: Blog Section con imágenes y mejor diseño -->
    <section id="blog" class="py-24 relative" :class="isDark ? 'bg-[#0d1d32]/30' : 'bg-slate-50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <span class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold mb-4 border" :class="isDark ? 'bg-cyan-500/20 border-cyan-500/50 text-cyan-400' : 'bg-cyan-50 border-cyan-200 text-cyan-600'">
            📝 Blog & Recursos
          </span>
          <h2 class="text-4xl md:text-5xl font-black mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Conocimiento para tu éxito
          </h2>
          <p class="text-lg leading-relaxed" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
            Aprende estrategias probadas para optimizar tu inventario y hacer crecer tu negocio
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <article v-for="(post, idx) in blogPosts" :key="idx" 
            class="group cursor-pointer rounded-2xl overflow-hidden border transition-all duration-300 hover:shadow-2xl hover:-translate-y-2"
            :class="isDark ? 'bg-[#0a1628] border-[#2E7DE8]/20 hover:border-[#2E7DE8]/50' : 'bg-white border-gray-100 shadow-lg'"
            @click="openArticle(post)">
            <!-- Imagen del artículo -->
            <div class="h-56 overflow-hidden relative">
              <div class="absolute inset-0 transition-transform duration-500 group-hover:scale-110" :class="post.gradient"></div>
              <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-7xl drop-shadow-lg">{{ post.icon }}</span>
              </div>
              <div class="absolute top-4 left-4">
                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider" :class="isDark ? 'bg-[#2E7DE8]/90 text-white' : 'bg-white/90 text-[#2E7DE8]'">
                  {{ post.category }}
                </span>
              </div>
              <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-black/60 to-transparent"></div>
            </div>
            
            <div class="p-8">
              <div class="flex items-center gap-3 text-sm mb-4" :class="isDark ? 'text-gray-400' : 'text-gray-500'">
                <span class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  {{ post.date }}
                </span>
                <span>•</span>
                <span class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  {{ post.readTime }}
                </span>
              </div>
              
              <h3 class="text-xl font-bold mb-3 leading-tight group-hover:text-[#2E7DE8] transition-colors" :class="isDark ? 'text-white' : 'text-gray-900'">
                {{ post.title }}
              </h3>
              
              <p class="leading-relaxed mb-6" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
                {{ post.excerpt }}
              </p>
              
              <div class="flex items-center text-[#2E7DE8] font-semibold">
                Leer artículo completo
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- MEJORADO: Demo Interactiva -->
    <section id="demo-interactive" class="py-24 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-12">
          <span class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold mb-4 border" :class="isDark ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-400' : 'bg-emerald-50 border-emerald-200 text-emerald-600'">
            🎮 Demo Interactiva
          </span>
          <h2 class="text-4xl md:text-5xl font-black mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Pruébalo sin registrarte
          </h2>
          <p class="text-lg leading-relaxed" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
            Explora todas las funcionalidades con datos de prueba. Sin compromisos.
          </p>
        </div>

        <div class="rounded-2xl overflow-hidden shadow-2xl border"
          :class="isDark ? 'border-[#2E7DE8]/30 bg-[#0a1628]' : 'border-gray-200 bg-white'">
          
          <!-- Demo Header -->
          <div class="p-4 border-b flex items-center justify-between" 
            :class="isDark ? 'bg-[#0d1d32] border-[#2E7DE8]/20' : 'bg-gray-50 border-gray-200'">
            <div class="flex items-center gap-3">
              <div class="flex gap-2">
                <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
              </div>
              <span class="ml-4 text-sm font-semibold px-3 py-1 rounded-full" :class="isDark ? 'bg-amber-500/20 text-amber-400' : 'bg-amber-100 text-amber-700'">
                🚧 Modo Demo - Los cambios no se guardan
              </span>
            </div>
            <router-link to="/register" 
              class="px-5 py-2 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] text-white rounded-lg text-sm font-semibold hover:shadow-lg transition-all flex items-center gap-2">
              Crear cuenta real
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
            </router-link>
          </div>

          <!-- Demo Interface -->
          <div class="grid lg:grid-cols-[280px,1fr] min-h-[600px]">
            <!-- Sidebar -->
            <div class="p-4 border-r" :class="isDark ? 'border-[#2E7DE8]/20 bg-[#0d1d32]/50' : 'border-gray-200 bg-gray-50'">
              <div class="flex items-center gap-3 mb-6 p-3 rounded-xl" :class="isDark ? 'bg-[#0a1628]' : 'bg-white'">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#2E7DE8] to-[#1e6ad1] flex items-center justify-center">
                  <span class="text-white font-bold text-lg">SW</span>
                </div>
                <div>
                  <p class="font-bold text-sm" :class="isDark ? 'text-white' : 'text-gray-900'">StockWolf</p>
                  <p class="text-xs" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Demo Account</p>
                </div>
              </div>
              
              <nav class="space-y-1">
                <button v-for="item in demoMenu" :key="item"
                  class="w-full px-4 py-3 rounded-xl text-left flex items-center gap-3 transition-all"
                  :class="demoActiveTab === item 
                    ? 'bg-[#2E7DE8] text-white shadow-lg shadow-[#2E7DE8]/30' 
                    : (isDark ? 'text-gray-400 hover:bg-[#1a2d47] hover:text-white' : 'text-gray-600 hover:bg-white hover:shadow-sm')"
                  @click="demoActiveTab = item">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path v-if="item === 'Dashboard'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    <path v-else-if="item === 'Productos'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    <path v-else-if="item === 'Movimientos'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    <path v-else-if="item === 'Reportes'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  </svg>
                  {{ item }}
                </button>
              </nav>
            </div>

            <!-- Main Content -->
            <div class="p-6" :class="isDark ? 'bg-[#0a1628]' : 'bg-white'">
              <!-- Dashboard Demo -->
              <div v-if="demoActiveTab === 'Dashboard'" class="space-y-6">
                <div class="flex items-center justify-between">
                  <h3 class="text-2xl font-bold" :class="isDark ? 'text-white' : 'text-gray-900'">Dashboard</h3>
                  <div class="flex gap-2">
                    <button class="px-3 py-1.5 text-sm rounded-lg border transition-colors" :class="isDark ? 'border-[#2E7DE8]/30 text-gray-400 hover:border-[#2E7DE8]' : 'border-gray-200 text-gray-600 hover:border-[#2E7DE8]'">Hoy</button>
                    <button class="px-3 py-1.5 text-sm rounded-lg border transition-colors" :class="isDark ? 'border-[#2E7DE8]/30 text-gray-400 hover:border-[#2E7DE8]' : 'border-gray-200 text-gray-600 hover:border-[#2E7DE8]'">Semana</button>
                    <button class="px-3 py-1.5 text-sm rounded-lg bg-[#2E7DE8] text-white">Mes</button>
                  </div>
                </div>
                
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                  <div v-for="stat in demoStats" :key="stat.label" 
                    class="p-5 rounded-xl border transition-all hover:shadow-lg"
                    :class="isDark ? 'bg-[#0d1d32] border-[#2E7DE8]/20 hover:border-[#2E7DE8]/50' : 'bg-gray-50 border-gray-200 hover:border-[#2E7DE8]/30'">
                    <p class="text-sm mb-1" :class="isDark ? 'text-gray-400' : 'text-gray-500'">{{ stat.label }}</p>
                    <p class="text-2xl font-black" :class="isDark ? 'text-white' : 'text-gray-900'">{{ stat.value }}</p>
                    <p class="text-xs mt-2 text-emerald-500 font-medium">↑ {{ stat.change }}</p>
                  </div>
                </div>
                
                <!-- Demo Chart -->
                <div class="p-6 rounded-xl border" :class="isDark ? 'border-[#2E7DE8]/20 bg-[#0d1d32]' : 'border-gray-200 bg-gray-50'">
                  <div class="flex items-center justify-between mb-6">
                    <h4 class="font-bold" :class="isDark ? 'text-white' : 'text-gray-900'">Movimientos del mes</h4>
                    <div class="flex items-center gap-4">
                      <span class="flex items-center gap-2 text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-500'"><span class="w-3 h-3 rounded-full bg-[#2E7DE8]"></span> Entradas</span>
                      <span class="flex items-center gap-2 text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-500'"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Salidas</span>
                    </div>
                  </div>
                  <div class="flex items-end gap-3 h-40">
                    <div v-for="(bar, idx) in demoBars" :key="idx" 
                      class="flex-1 flex flex-col gap-1">
                      <div class="w-full rounded-t-lg transition-all hover:opacity-80 cursor-pointer relative group"
                        :style="{ height: bar.in + '%', backgroundColor: '#2E7DE8' }">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">{{ bar.in }}%</div>
                      </div>
                      <div class="w-full rounded-t-lg transition-all hover:opacity-80 cursor-pointer relative group"
                        :style="{ height: bar.out + '%', backgroundColor: '#10b981' }">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">{{ bar.out }}%</div>
                      </div>
                    </div>
                  </div>
                  <div class="flex justify-between mt-4 text-sm font-medium" :class="isDark ? 'text-gray-400' : 'text-gray-500'">
                    <span>Semana 1</span>
                    <span>Semana 2</span>
                    <span>Semana 3</span>
                    <span>Semana 4</span>
                  </div>
                </div>
              </div>

              <!-- Productos Demo -->
              <div v-else-if="demoActiveTab === 'Productos'" class="space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                  <h3 class="text-2xl font-bold" :class="isDark ? 'text-white' : 'text-gray-900'">Productos</h3>
                  <div class="flex gap-2">
                    <input type="text" placeholder="Buscar producto..." class="px-4 py-2 rounded-lg border text-sm" :class="isDark ? 'bg-[#0a1628] border-[#2E7DE8]/30 text-white' : 'bg-white border-gray-200'">
                    <button class="px-4 py-2 bg-[#2E7DE8] text-white rounded-lg text-sm font-semibold flex items-center gap-2">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                      </svg>
                      Nuevo
                    </button>
                  </div>
                </div>
                
                <div class="overflow-x-auto rounded-xl border" :class="isDark ? 'border-[#2E7DE8]/20' : 'border-gray-200'">
                  <table class="w-full">
                    <thead :class="isDark ? 'bg-[#0d1d32]' : 'bg-gray-50'">
                      <tr :class="isDark ? 'text-gray-300' : 'text-gray-600'">
                        <th class="text-left py-4 px-4 font-semibold">Producto</th>
                        <th class="text-left py-4 px-4 font-semibold">SKU</th>
                        <th class="text-right py-4 px-4 font-semibold">Stock</th>
                        <th class="text-right py-4 px-4 font-semibold">Precio</th>
                        <th class="text-center py-4 px-4 font-semibold">Estado</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y" :class="isDark ? 'divide-[#2E7DE8]/10' : 'divide-gray-100'">
                      <tr v-for="product in demoProducts" :key="product.sku" 
                        class="transition-colors cursor-pointer"
                        :class="isDark ? 'hover:bg-[#0d1d32] text-gray-300' : 'hover:bg-gray-50 text-gray-700'">
                        <td class="py-4 px-4">
                          <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-lg">{{ product.image }}</div>
                            <span class="font-medium">{{ product.name }}</span>
                          </div>
                        </td>
                        <td class="py-4 px-4 font-mono text-sm text-[#2E7DE8]">{{ product.sku }}</td>
                        <td class="py-4 px-4 text-right font-bold">{{ product.stock }}</td>
                        <td class="py-4 px-4 text-right">${{ product.price }}</td>
                        <td class="py-4 px-4 text-center">
                          <span class="px-3 py-1 rounded-full text-xs font-semibold" 
                            :class="product.stock < 10 ? 'bg-rose-500/20 text-rose-400' : product.stock < 20 ? 'bg-amber-500/20 text-amber-400' : 'bg-emerald-500/20 text-emerald-400'">
                            {{ product.stock < 10 ? 'Crítico' : product.stock < 20 ? 'Bajo' : 'OK' }}
                          </span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Other tabs -->
              <div v-else class="flex flex-col items-center justify-center h-96 text-center">
                <div class="w-20 h-20 rounded-2xl bg-[#2E7DE8]/20 flex items-center justify-center mb-6">
                  <svg class="w-10 h-10 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <h4 class="text-xl font-bold mb-2" :class="isDark ? 'text-white' : 'text-gray-900'">Explora más en la versión completa</h4>
                <p class="mb-6 max-w-md" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Esta sección está disponible exclusivamente en la versión completa de StockWolf.</p>
                <router-link to="/register" class="px-8 py-3 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                  Crear cuenta gratuita
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- Testimonials Section -->
    <section id="testimonials" class="py-24 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <span class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold mb-4 border" :class="isDark ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-400' : 'bg-emerald-50 border-emerald-200 text-emerald-600'">
            Testimonios
          </span>
          <h2 class="text-4xl md:text-5xl font-black mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Lo que dicen nuestros clientes
          </h2>
          <p class="text-lg leading-relaxed" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
            Historias reales de empresas que transformaron su gestión de inventario
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div v-for="(testimonial, idx) in testimonials" :key="idx" 
            class="p-8 rounded-2xl border transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
            :class="isDark ? 'bg-[#0d1d32]/80 border-[#2E7DE8]/20' : 'bg-white border-gray-100 shadow-lg'">
            <div class="flex gap-1 mb-6">
              <svg v-for="n in 5" :key="n" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </div>
            <p class="mb-6 italic text-lg leading-relaxed" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
              "{{ testimonial.quote }}"
            </p>
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[#2E7DE8] to-[#1e6ad1] flex items-center justify-center text-white text-xl font-bold">
                {{ testimonial.name.charAt(0) }}
              </div>
              <div>
                <p class="font-bold" :class="isDark ? 'text-white' : 'text-gray-900'">{{ testimonial.name }}</p>
                <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-500'">{{ testimonial.role }}</p>
                <p class="text-sm text-[#2E7DE8] font-medium">{{ testimonial.company }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Demo Form Section -->
    <section id="demo-form" class="py-24 relative" :class="isDark ? 'bg-[#0d1d32]/30' : 'bg-slate-50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-start">
          <div>
            <span class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold mb-4 border" :class="isDark ? 'bg-purple-500/20 border-purple-500/50 text-purple-400' : 'bg-purple-50 border-purple-200 text-purple-600'">
              📅 Solicita una Demo Personalizada
            </span>
            <h2 class="text-4xl md:text-5xl font-black mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
              ¿Prefieres una demostración personalizada?
            </h2>
            <p class="text-lg mb-8 leading-relaxed" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
              Completa el formulario y nos pondremos en contacto contigo en menos de 24 horas para agendar una videollamada de 30 minutos.
            </p>
            
            <div class="space-y-5">
              <div class="flex items-start gap-4 p-4 rounded-xl" :class="isDark ? 'bg-[#0a1628]' : 'bg-white shadow-sm'">
                <div class="w-12 h-12 rounded-xl bg-[#2E7DE8]/20 flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-bold text-lg" :class="isDark ? 'text-white' : 'text-gray-900'">30 minutos</h4>
                  <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Suficiente para conocer tus necesidades y mostrarte la solución perfecta</p>
                </div>
              </div>
              
              <div class="flex items-start gap-4 p-4 rounded-xl" :class="isDark ? 'bg-[#0a1628]' : 'bg-white shadow-sm'">
                <div class="w-12 h-12 rounded-xl bg-[#2E7DE8]/20 flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-bold text-lg" :class="isDark ? 'text-white' : 'text-gray-900'">Videollamada</h4>
                  <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Por Zoom, Google Meet o WhatsApp, como prefieras</p>
                </div>
              </div>
              
              <div class="flex items-start gap-4 p-4 rounded-xl" :class="isDark ? 'bg-[#0a1628]' : 'bg-white shadow-sm'">
                <div class="w-12 h-12 rounded-xl bg-[#2E7DE8]/20 flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-bold text-lg" :class="isDark ? 'text-white' : 'text-gray-900'">Sin compromiso</h4>
                  <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Conoceremos tu caso y te daremos recomendaciones, sin presión de venta</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Formulario -->
          <div class="rounded-2xl overflow-hidden border shadow-2xl"
            :class="isDark ? 'border-[#2E7DE8]/30 bg-[#0a1628]' : 'border-gray-200 bg-white'">
            <div class="p-6 border-b" :class="isDark ? 'border-[#2E7DE8]/20 bg-[#0d1d32]' : 'border-gray-200 bg-gray-50'">
              <h3 class="font-bold text-xl" :class="isDark ? 'text-white' : 'text-gray-900'">Solicitar demostración</h3>
              <p class="text-sm mt-1" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Completa tus datos y te contactaremos</p>
            </div>
            <div class="p-6">
              <form @submit.prevent="submitDemoRequest" class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-semibold mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">Nombre *</label>
                    <input v-model="demoForm.name" type="text" required
                      class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] transition-all"
                      :class="isDark ? 'bg-[#0d1d32] border-[#2E7DE8]/30 text-white placeholder-gray-500' : 'bg-white border-gray-300 text-gray-900'"
                      placeholder="Tu nombre" />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">Empresa *</label>
                    <input v-model="demoForm.company" type="text" required
                      class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] transition-all"
                      :class="isDark ? 'bg-[#0d1d32] border-[#2E7DE8]/30 text-white placeholder-gray-500' : 'bg-white border-gray-300 text-gray-900'"
                      placeholder="Nombre de tu empresa" />
                  </div>
                </div>
                
                <div class="grid md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-semibold mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">Email *</label>
                    <input v-model="demoForm.email" type="email" required
                      class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] transition-all"
                      :class="isDark ? 'bg-[#0d1d32] border-[#2E7DE8]/30 text-white placeholder-gray-500' : 'bg-white border-gray-300 text-gray-900'"
                      placeholder="tu@email.com" />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">Teléfono *</label>
                    <input v-model="demoForm.phone" type="tel" required
                      class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] transition-all"
                      :class="isDark ? 'bg-[#0d1d32] border-[#2E7DE8]/30 text-white placeholder-gray-500' : 'bg-white border-gray-300 text-gray-900'"
                      placeholder="+52 123 456 7890" />
                  </div>
                </div>
                
                <div>
                  <label class="block text-sm font-semibold mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">Número de empleados</label>
                  <select v-model="demoForm.employees"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] transition-all"
                    :class="isDark ? 'bg-[#0d1d32] border-[#2E7DE8]/30 text-white' : 'bg-white border-gray-300 text-gray-900'">
                    <option value="">Selecciona una opción</option>
                    <option value="1-5">1-5 empleados</option>
                    <option value="6-20">6-20 empleados</option>
                    <option value="21-50">21-50 empleados</option>
                    <option value="51+">Más de 50 empleados</option>
                  </select>
                </div>
                
                <div>
                  <label class="block text-sm font-semibold mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">¿Qué necesitas gestionar?</label>
                  <select v-model="demoForm.inventoryType"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] transition-all"
                    :class="isDark ? 'bg-[#0d1d32] border-[#2E7DE8]/30 text-white' : 'bg-white border-gray-300 text-gray-900'">
                    <option value="">Selecciona una opción</option>
                    <option value="productos">Productos físicos</option>
                    <option value="insumos">Insumos y materias primas</option>
                    <option value="equipos">Equipos y herramientas</option>
                    <option value="mixto">Combinación de los anteriores</option>
                  </select>
                </div>
                
                <div>
                  <label class="block text-sm font-semibold mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">Mensaje (opcional)</label>
                  <textarea v-model="demoForm.message" rows="3"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] transition-all resize-none"
                    :class="isDark ? 'bg-[#0d1d32] border-[#2E7DE8]/30 text-white placeholder-gray-500' : 'bg-white border-gray-300 text-gray-900'"
                    placeholder="Cuéntanos brevemente sobre tus necesidades..."></textarea>
                </div>
                
                <div class="flex items-start gap-3">
                  <input id="demo-consent" v-model="demoForm.consent" type="checkbox" required
                    class="mt-1 w-5 h-5 rounded border-gray-300 text-[#2E7DE8] focus:ring-[#2E7DE8]" />
                  <label for="demo-consent" class="text-sm leading-relaxed" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
                    Acepto que CJ Consultoría se comunique conmigo vía email o teléfono para coordinar la demostración. 
                    <a href="/aviso-privacidad.html" target="_blank" class="text-[#2E7DE8] hover:underline font-medium">Aviso de Privacidad</a>.
                  </label>
                </div>
                
                <button type="submit" :disabled="demoSubmitting"
                  class="w-full px-6 py-4 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] hover:from-[#1e6ad1] hover:to-[#2E7DE8] text-white rounded-xl font-bold transition-all shadow-lg flex items-center justify-center gap-2 disabled:opacity-70">
                  <svg v-if="demoSubmitting" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <span>{{ demoSubmitting ? 'Enviando...' : 'Solicitar Demo' }}</span>
                </button>
                
                <p v-if="demoSuccess" class="text-center text-emerald-400 text-sm font-medium">
                  ✓ ¡Solicitud enviada! Te contactaremos pronto.
                </p>
                <p v-if="demoError" class="text-center text-rose-400 text-sm">
                  {{ demoError }}
                </p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 relative">
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#2E7DE8]/5 to-transparent pointer-events-none"></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <span class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold mb-4 border" :class="isDark ? 'bg-[#2E7DE8]/20 border-[#2E7DE8]/50 text-[#2E7DE8]' : 'bg-blue-50 border-blue-200 text-[#2E7DE8]'">
            Precios
          </span>
          <h2 class="text-4xl md:text-5xl font-black mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Planes y Precios
          </h2>
          <p class="text-lg" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
            Inversión accesible para PYMEs y emprendedores
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto items-center">
          <div v-for="plan in pricingPlans" :key="plan.name"
            class="rounded-2xl p-8 border transition-all duration-300"
            :class="plan.popular 
              ? (isDark ? 'bg-[#0d1d32] border-[#2E7DE8] shadow-2xl shadow-[#2E7DE8]/20 scale-105' : 'bg-white border-[#2E7DE8] shadow-2xl shadow-[#2E7DE8]/20 scale-105')
              : (isDark ? 'bg-[#0d1d32]/50 border-[#2E7DE8]/20' : 'bg-white border-gray-200')">
            
            <div v-if="plan.popular" class="text-center mb-4">
              <span class="px-4 py-1 rounded-full text-xs font-bold bg-[#2E7DE8] text-white">MÁS POPULAR</span>
            </div>
            
            <h3 class="text-xl font-bold mb-2" :class="isDark ? 'text-white' : 'text-gray-900'">{{ plan.name }}</h3>
            <p class="text-sm mb-4" :class="isDark ? 'text-gray-400' : 'text-gray-500'">{{ plan.description }}</p>
            
            <div class="mb-6">
              <span class="text-4xl font-black" :class="isDark ? 'text-white' : 'text-gray-900'">${{ plan.price }}</span>
              <span class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-500'">/{{ plan.period }}</span>
            </div>
            
            <ul class="space-y-3 mb-8">
              <li v-for="feature in plan.features" :key="feature" class="flex items-center gap-3 text-sm" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ feature }}
              </li>
            </ul>
            
            <router-link to="/register"
              class="block w-full py-3 rounded-xl font-semibold text-center transition-all"
              :class="plan.popular 
                ? 'bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] text-white hover:shadow-lg'
                : (isDark ? 'border border-[#2E7DE8]/50 text-[#2E7DE8] hover:bg-[#2E7DE8]/10' : 'border border-gray-300 text-gray-700 hover:border-[#2E7DE8] hover:text-[#2E7DE8]')">
              {{ plan.cta }}
            </router-link>
          </div>
        </div>
      </div>
    </section>


    <!-- Footer -->
    <footer class="py-16 border-t relative" :class="isDark ? 'border-[#2E7DE8]/20 bg-[#0a1628]' : 'border-gray-200 bg-gray-50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid md:grid-cols-4 gap-8 mb-12">
          <div class="col-span-2">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-12 h-12 rounded-xl overflow-hidden border-2 border-[#2E7DE8] shadow-lg bg-white">
                <img src="/logo-cj-main.png" alt="CJ Consultoría" class="w-full h-full object-contain" />
              </div>
              <span class="text-xl font-black" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">StockWolf</span>
            </div>
            <p class="text-sm mb-4 max-w-sm leading-relaxed" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
              Sistema ERP profesional para gestión de inventario. Desarrollado por 
              <strong class="text-[#2E7DE8]">CJ Consultoría</strong> - Transformamos procesos en resultados sostenibles.
            </p>
            <div class="flex gap-4">
              <button @click="openWhatsApp" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors bg-[#25D366] text-white hover:bg-[#128C7E]">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
              </button>
              <button @click="sendEmail" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors" :class="isDark ? 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white' : 'bg-white text-gray-600 hover:bg-gray-100 shadow-sm'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              </button>
            </div>
          </div>

          <div>
            <h4 class="font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Producto</h4>
            <ul class="space-y-3 text-sm">
              <li><button @click="scrollToSection('features')" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Características</button></li>
              <li><button @click="scrollToSection('pricing')" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Precios</button></li>
              <li><button @click="scrollToSection('blog')" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Blog</button></li>
              <li><router-link to="/login" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Iniciar Sesión</router-link></li>
            </ul>
          </div>

          <div>
            <h4 class="font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">CJ Consultoría</h4>
            <ul class="space-y-3 text-sm">
              <li><button @click="scrollToSection('consulting')" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Nosotros</button></li>
              <li><button @click="openWhatsApp" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-400' : 'text-gray-600'">WhatsApp</button></li>
              <li><button @click="sendEmail" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Email</button></li>
            </ul>
          </div>
        </div>

        <div class="pt-8 border-t flex flex-col md:flex-row justify-between items-center gap-4" :class="isDark ? 'border-gray-800' : 'border-gray-200'">
          <p class="text-sm" :class="isDark ? 'text-gray-500' : 'text-gray-500'">
            © 2026 StockWolf & CJ Consultoría. Todos los derechos reservados.
          </p>
          <div class="flex gap-6 text-sm">
            <a href="/terminos-condiciones.html" target="_blank" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-500' : 'text-gray-500'">
              Términos y Condiciones
            </a>
            <a href="/aviso-privacidad.html" target="_blank" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-500' : 'text-gray-500'">
              Aviso de Privacidad
            </a>
          </div>
        </div>
      </div>
    </footer>

    <!-- MEJORADO: ChatBot Flotante con Input -->
    <div class="fixed bottom-6 right-6 z-50">
      <!-- Chat Window -->
      <div v-if="chatOpen" 
        class="mb-4 w-96 rounded-2xl shadow-2xl overflow-hidden border animate-fade-in-up flex flex-col"
        :class="isDark ? 'bg-[#0a1628] border-[#2E7DE8]/30' : 'bg-white border-gray-200'"
        style="height: 500px;">
        
        <!-- Header -->
        <div class="p-4 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] flex-shrink-0">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center border-2 border-white/30">
              <span class="text-white font-bold text-lg">🐺</span>
            </div>
            <div>
              <p class="font-bold text-white">Asistente StockWolf</p>
              <p class="text-xs text-blue-100 flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                En línea - Responde en minutos
              </p>
            </div>
            <button @click="chatOpen = false" class="ml-auto text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        
        <!-- Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4" :class="isDark ? 'bg-[#0a1628]' : 'bg-gray-50'" ref="chatMessagesContainer">
          <div v-for="(msg, idx) in chatMessages" :key="idx" 
            :class="['flex', msg.from === 'user' ? 'justify-end' : 'justify-start']">
            <div :class="['max-w-[85%] p-3 rounded-2xl text-sm leading-relaxed',
              msg.from === 'user' 
                ? 'bg-[#2E7DE8] text-white rounded-br-md' 
                : isDark ? 'bg-[#0d1d32] text-gray-200 border border-[#2E7DE8]/20 rounded-bl-md' : 'bg-white text-gray-700 border border-gray-200 rounded-bl-md shadow-sm']">
              {{ msg.text }}
            </div>
          </div>
          <div v-if="chatTyping" class="flex justify-start">
            <div class="p-3 rounded-2xl rounded-bl-md" :class="isDark ? 'bg-[#0d1d32] border border-[#2E7DE8]/20' : 'bg-white border border-gray-200'">
              <div class="flex gap-1">
                <span class="w-2 h-2 rounded-full bg-gray-400 animate-bounce"></span>
                <span class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0.1s;"></span>
                <span class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0.2s;"></span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Quick Options -->
        <div class="p-3 border-t flex flex-wrap gap-2" :class="isDark ? 'border-[#2E7DE8]/20 bg-[#0d1d32]' : 'border-gray-200 bg-gray-50'">
          <button v-for="option in chatOptions" :key="option"
            @click="sendChatMessage(option)"
            class="px-3 py-1.5 text-xs rounded-full border transition-colors"
            :class="isDark ? 'border-[#2E7DE8]/30 text-[#2E7DE8] hover:bg-[#2E7DE8]/20' : 'border-blue-200 text-blue-600 hover:bg-blue-50'">
            {{ option }}
          </button>
        </div>
        
        <!-- Input Area -->
        <div class="p-3 border-t flex-shrink-0" :class="isDark ? 'border-[#2E7DE8]/20 bg-[#0d1d32]' : 'border-gray-200 bg-white'">
          <form @submit.prevent="sendUserMessage" class="flex gap-2">
            <input
              v-model="chatInput"
              type="text"
              placeholder="Escribe tu mensaje..."
              class="flex-1 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-[#2E7DE8] transition-all"
              :class="isDark ? 'bg-[#0a1628] border border-[#2E7DE8]/30 text-white placeholder-gray-500' : 'bg-gray-100 border border-transparent text-gray-900 placeholder-gray-400'"
            />
            <button
              type="submit"
              :disabled="!chatInput.trim()"
              class="px-4 py-2.5 bg-[#2E7DE8] text-white rounded-xl hover:bg-[#1e6ad1] transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
              </svg>
            </button>
          </form>
        </div>
      </div>
      
      <!-- Toggle Button -->
      <button v-if="!chatOpen" @click="chatOpen = true"
        class="w-16 h-16 rounded-full bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] text-white shadow-xl shadow-[#2E7DE8]/40 flex items-center justify-center hover:scale-110 transition-transform border-4 border-white dark:border-[#0a1628]">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
      </button>
    </div>

    <!-- Article Modal -->
    <div v-if="selectedArticle" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="selectedArticle = null"></div>
      <div class="relative w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-2xl"
        :class="isDark ? 'bg-[#0a1628] border border-[#2E7DE8]/30' : 'bg-white border border-gray-200'">
        
        <!-- Header -->
        <div class="sticky top-0 p-6 border-b flex items-center justify-between z-10"
          :class="isDark ? 'bg-[#0a1628] border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
          <div class="flex items-center gap-3">
            <span class="text-3xl">{{ selectedArticle.icon }}</span>
            <div>
              <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider" :class="isDark ? 'bg-[#2E7DE8]/30 text-[#2E7DE8]' : 'bg-blue-100 text-blue-700'">
                {{ selectedArticle.category }}
              </span>
              <h2 class="text-xl font-bold mt-1" :class="isDark ? 'text-white' : 'text-gray-900'">{{ selectedArticle.title }}</h2>
            </div>
          </div>
          <button @click="selectedArticle = null" 
            class="w-10 h-10 rounded-full flex items-center justify-center transition-colors"
            :class="isDark ? 'hover:bg-gray-700 text-gray-400' : 'hover:bg-gray-100 text-gray-600'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <!-- Content -->
        <div class="p-8">
          <div class="flex items-center gap-4 text-sm mb-8" :class="isDark ? 'text-gray-500' : 'text-gray-500'">
            <span class="flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              {{ selectedArticle.date }}
            </span>
            <span>•</span>
            <span class="flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              {{ selectedArticle.readTime }} de lectura
            </span>
          </div>
          
          <div class="prose max-w-none" :class="isDark ? 'prose-invert' : ''">
            <div v-html="selectedArticle.content"></div>
          </div>
          
          <!-- CTA al final del artículo -->
          <div class="mt-10 p-6 rounded-xl border" :class="isDark ? 'bg-[#0d1d32]/80 border-[#2E7DE8]/30' : 'bg-blue-50 border-blue-200'">
            <h4 class="font-bold text-lg mb-2" :class="isDark ? 'text-white' : 'text-gray-900'">¿Listo para aplicar esto en tu negocio?</h4>
            <p class="mb-4" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
              StockWolf te ayuda a implementar estas mejores prácticas de forma automática.
            </p>
            <div class="flex flex-wrap gap-3">
              <router-link to="/register" class="px-6 py-3 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                Prueba Gratis
              </router-link>
              <button @click="scrollToSection('demo-form'); selectedArticle = null" 
                class="px-6 py-3 border rounded-xl font-semibold transition-colors"
                :class="isDark ? 'border-gray-600 text-gray-300 hover:border-[#2E7DE8]' : 'border-gray-300 text-gray-700 hover:border-[#2E7DE8]'">
                Solicitar Demo
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Scroll to Top Button -->
    <button v-show="scrolled" @click="scrollToSection('top')"
      class="fixed bottom-6 left-6 z-50 w-12 h-12 rounded-full bg-[#2E7DE8] text-white shadow-lg shadow-[#2E7DE8]/30 flex items-center justify-center hover:scale-110 transition-all animate-fade-in">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
      </svg>
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed, nextTick } from 'vue'
import { useDarkMode } from '../../composables/useDarkMode'
import AnimatedCounter from '../../components/AnimatedCounter.vue'

const { isDark, toggleDarkMode } = useDarkMode()
const scrolled = ref(false)
const mobileMenuOpen = ref(false)

// ROI Calculator
const roiCalculator = ref({
  hoursPerWeek: 15,
  hourlyRate: 200,
  monthlyLosses: 5000
})

const monthlyTimeSavings = computed(() => {
  const hoursSaved = roiCalculator.value.hoursPerWeek * 4 * 0.8
  return Math.round(hoursSaved * roiCalculator.value.hourlyRate)
})

const totalMonthlyROI = computed(() => {
  return monthlyTimeSavings.value + (roiCalculator.value.monthlyLosses * 0.9)
})

const roiMultiplier = computed(() => {
  return Math.round(totalMonthlyROI.value / 299)
})

// Demo Form
const demoForm = ref({
  name: '',
  company: '',
  email: '',
  phone: '',
  employees: '',
  inventoryType: '',
  message: '',
  consent: false
})
const demoSubmitting = ref(false)
const demoSuccess = ref(false)
const demoError = ref('')

const submitDemoRequest = async () => {
  demoSubmitting.value = true
  demoError.value = ''
  demoSuccess.value = false
  
  try {
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    const emailBody = encodeURIComponent(
      `Nueva solicitud de demostración StockWolf\n\n` +
      `Nombre: ${demoForm.value.name}\n` +
      `Empresa: ${demoForm.value.company}\n` +
      `Email: ${demoForm.value.email}\n` +
      `Teléfono: ${demoForm.value.phone}\n` +
      `Empleados: ${demoForm.value.employees}\n` +
      `Tipo de inventario: ${demoForm.value.inventoryType}\n` +
      `Mensaje: ${demoForm.value.message || 'N/A'}\n\n` +
      `---\nEnviado desde el sitio web StockWolf`
    )
    
    window.location.href = `mailto:ing.carlosurbina@gmail.com?subject=Solicitud Demo StockWolf - ${demoForm.value.company}&body=${emailBody}`
    
    demoSuccess.value = true
    demoForm.value = {
      name: '',
      company: '',
      email: '',
      phone: '',
      employees: '',
      inventoryType: '',
      message: '',
      consent: false
    }
  } catch (err) {
    demoError.value = 'Hubo un error al enviar. Por favor intenta de nuevo o contáctanos por WhatsApp.'
  } finally {
    demoSubmitting.value = false
  }
}

// Article Modal
const selectedArticle = ref(null)

const openArticle = (article) => {
  selectedArticle.value = article
  document.body.style.overflow = 'hidden'
}

// Testimonials Data
const testimonials = [
  {
    quote: "Antes perdíamos 10 horas semanales en inventario. Ahora todo es automático y tenemos visibilidad en tiempo real de todos nuestros almacenes.",
    name: "María González",
    role: "Directora de Operaciones",
    company: "ADIS Diseño"
  },
  {
    quote: "Redujimos nuestras mermas en un 85% el primer mes. El sistema nos alerta antes de que se acabe el stock crítico.",
    name: "Carlos Ramírez",
    role: "Gerente General",
    company: "Caoba Muebles"
  },
  {
    quote: "La mejor inversión que hemos hecho. El soporte de CJ Consultoría es excepcional y el sistema es muy intuitivo.",
    name: "Laura Martínez",
    role: "Dueña",
    company: "Decoraciones del Norte"
  }
]

// Features
const features = [
  { icon: 'inventory', title: 'Control Multi-Almacén', description: 'Gestiona inventario en múltiples ubicaciones con transferencias entre almacenes en tiempo real.' },
  { icon: 'barcode', title: 'Códigos de Barras', description: 'Escaneo y generación de códigos de barras. Compatible con lectores y etiquetadoras DYMO.' },
  { icon: 'chart', title: 'Reportes Avanzados', description: 'Análisis ABC, rotación de inventario, valoración por métodos de costeo (FIFO, LIFO, Promedio).' },
  { icon: 'alert', title: 'Alertas Inteligentes', description: 'Notificaciones automáticas de stock bajo, productos por vencer y transferencias pendientes.' },
  { icon: 'users', title: 'Gestión de Usuarios', description: 'Roles y permisos personalizables: Administrador, Gerente, Operador y Visualizador.' },
  { icon: 'mobile', title: 'Acceso Multiplataforma', description: 'Web responsive. Accede desde cualquier dispositivo, en cualquier lugar.' }
]

// Blog Posts - MEJORADOS con gradientes para imágenes
const blogPosts = [
  {
    icon: '📊',
    category: 'Gestión',
    title: 'Cómo implementar análisis ABC en tu inventario',
    excerpt: 'Descubre cómo clasificar tus productos por valoración para optimizar tu capital de trabajo y maximizar tu rentabilidad.',
    date: '5 Abr 2026',
    readTime: '5 min',
    gradient: 'bg-gradient-to-br from-blue-500 to-purple-600',
    content: `
      <p>El análisis ABC es una técnica de clasificación de inventario que divide tus productos en tres categorías según su importancia y valor:</p>
      <h3>Categoría A (20% de productos, 80% del valor)</h3>
      <p>Son tus productos estrella. Requieren:</p>
      <ul>
        <li>Control riguroso y revisión constante</li>
        <li>Políticas de stock de seguridad más altas</li>
        <li>Negociación favorable con proveedores</li>
        <li>Seguimiento de tendencias de demanda</li>
      </ul>
      <h3>Categoría B (30% de productos, 15% del valor)</h3>
      <p>Productos de importancia media:</p>
      <ul>
        <li>Revisión periódica mensual</li>
        <li>Stock de seguridad moderado</li>
        <li>Análisis de rotación trimestral</li>
      </ul>
      <h3>Categoría C (50% de productos, 5% del valor)</h3>
      <p>Productos de bajo valor pero necesarios:</p>
      <ul>
        <li>Control simplificado</li>
        <li>Pedidos en lotes grandes</li>
        <li>Revisión semestral</li>
      </ul>
      <p><strong>StockWolf te ayuda:</strong> El sistema calcula automáticamente la clasificación ABC de tu inventario basándose en el valor de consumo de cada producto.</p>
    `
  },
  {
    icon: '🏆',
    category: 'ISO 9001',
    title: 'Preparación para auditoría de calidad: checklist 2026',
    excerpt: 'Todo lo que necesitas saber para pasar tu auditoría ISO sin contratiempos y mantener la certificación al día.',
    date: '2 Abr 2026',
    readTime: '8 min',
    gradient: 'bg-gradient-to-br from-emerald-500 to-teal-600',
    content: `
      <p>La auditoría de calidad puede ser un proceso estresante, pero con la preparación adecuada, tu empresa puede aprobar sin problemas. Aquí te presentamos un checklist completo:</p>
      <h3>Documentación Requerida</h3>
      <ul>
        <li>Manual de Calidad actualizado</li>
        <li>Procedimientos documentados (al menos 6 obligatorios)</li>
        <li>Registros de capacitación del personal</li>
        <li>Actas de revisión por la dirección</li>
        <li>Informes de auditorías internas</li>
      </ul>
      <h3>Aspectos Operativos</h3>
      <ul>
        <li>Matriz de identificación de riesgos y oportunidades</li>
        <li>Indicadores de desempeño definidos y medidos</li>
        <li>Proceso de gestión de no conformidades</li>
        <li>Acciones correctivas implementadas</li>
      </ul>
      <h3>Tips para el día de la auditoría</h3>
      <ul>
        <li>Ten toda la documentación organizada y accesible</li>
        <li>Asegúrate de que el personal conozca sus procedimientos</li>
        <li>Muestra evidencia de mejora continua</li>
        <li>Sé honesto sobre las áreas de oportunidad</li>
      </ul>
      <p><strong>Consejo CJ:</strong> Realiza auditorías internas trimestrales para mantener tu sistema siempre listo.</p>
    `
  },
  {
    icon: '📱',
    category: 'Tecnología',
    title: '5 señales de que necesitas un sistema de inventario',
    excerpt: 'Si experimentas alguna de estas señales, es hora de modernizar tu gestión y dar el salto a la digitalización.',
    date: '28 Mar 2026',
    readTime: '4 min',
    gradient: 'bg-gradient-to-br from-amber-500 to-orange-600',
    content: `
      <p>Muchas empresas operan con Excel o incluso papel durante años antes de darse cuenta de que necesitan un cambio. Estas son las señales de alerta:</p>
      <h3>1. Pérdidas de inventario sin explicación</h3>
      <p>Si tus conteos físicos no coinciden con tu sistema y no sabes dónde se fue la mercancía, es momento de automatizar. Un sistema profesional rastrea cada movimiento.</p>
      <h3>2. Stock-outs frecuentes</h3>
      <p>Quedarte sin producto justo cuando un cliente lo necesita es costoso. Las alertas automáticas de stock bajo previenen esta situación.</p>
      <h3>3. Exceso de inventario</h3>
      <p>Tener dinero "congelado" en productos que no se mueven afecta tu flujo de efectivo. El análisis de rotación te ayuda a optimizar.</p>
      <h3>4. Múltiples almacenes sin visibilidad</h3>
      <p>Si tienes inventario disperso y no sabes exactamente qué hay en cada ubicación, necesitas centralizar tu información.</p>
      <h3>5. Toma de decisiones a ciegas</h3>
      <p>Si tus compras se basan en "intuición" en lugar de datos históricos y proyecciones, estás perdiendo dinero.</p>
      <p><strong>La buena noticia:</strong> Implementar StockWolf toma menos de un día y puedes empezar gratis.</p>
    `
  }
]

// Interactive Demo
const demoActiveTab = ref('Dashboard')
const demoMenu = ['Dashboard', 'Productos', 'Movimientos', 'Reportes', 'Configuración']
const demoStats = [
  { label: 'Productos', value: '156', change: '12%' },
  { label: 'Valor Total', value: '$45.2k', change: '8%' },
  { label: 'Stock Bajo', value: '8', change: '-3' },
  { label: 'Mov. Hoy', value: '+24', change: '15%' }
]
const demoBars = [
  { in: 65, out: 45 },
  { in: 80, out: 60 },
  { in: 55, out: 70 },
  { in: 90, out: 75 }
]
const demoProducts = [
  { name: 'Laptop HP Pavilion', sku: 'LAP-HP-001', stock: 25, price: '12,499', image: '💻' },
  { name: 'Monitor Dell 27"', sku: 'MON-DL-027', stock: 8, price: '5,299', image: '🖥️' },
  { name: 'Teclado Mecánico', sku: 'TEC-MK-001', stock: 42, price: '1,299', image: '⌨️' },
  { name: 'Mouse Inalámbrico', sku: 'MOU-WL-001', stock: 5, price: '399', image: '🖱️' },
  { name: 'Webcam HD Pro', sku: 'CAM-HD-001', stock: 15, price: '899', image: '📷' }
]

// ChatBot - MEJORADO con input
const chatOpen = ref(false)
const chatInput = ref('')
const chatTyping = ref(false)
const chatMessages = ref([
  { from: 'bot', text: '¡Hola! Soy el asistente de StockWolf. 🐺\n\n¿En qué puedo ayudarte hoy?' }
])
const chatOptions = ['Ver planes', 'Agendar demo', 'Soporte', 'Hablar con humano']
const chatMessagesContainer = ref(null)

const scrollChatToBottom = () => {
  nextTick(() => {
    if (chatMessagesContainer.value) {
      chatMessagesContainer.value.scrollTop = chatMessagesContainer.value.scrollHeight
    }
  })
}

const sendChatMessage = (option) => {
  chatMessages.value.push({ from: 'user', text: option })
  chatTyping.value = true
  scrollChatToBottom()
  
  setTimeout(() => {
    chatTyping.value = false
    let response = ''
    switch(option) {
      case 'Ver planes':
        response = 'Tenemos 3 planes:\n\n🆓 Gratis: $0\n⭐ Profesional: $299/mes\n👑 Ilimitado: $799/mes\n\n¿Te gustaría conocer más detalles de alguno?'
        break
      case 'Agendar demo':
        response = 'Perfecto. Puedes:\n\n1️⃣ Desplazarte hasta "Solicitar Demo" en la página\n2️⃣ O te redirijo a WhatsApp ahora mismo'
        setTimeout(() => openWhatsApp(), 2000)
        break
      case 'Soporte':
        response = 'Nuestro equipo de soporte está disponible:\n\n📅 Lunes a Viernes\n🕘 9:00 AM - 6:00 PM\n\n¿Cuál es tu consulta? Puedo intentar ayudarte o conectarte con un agente.'
        break
      case 'Hablar con humano':
        response = 'Te conectaré con uno de nuestros consultores de CJ Consultoría...\n\nAbriendo WhatsApp ahora 🚀'
        setTimeout(() => openWhatsApp(), 1500)
        break
      default:
        response = 'Entiendo. Déjame conectarte con un asesor para ayudarte mejor.'
    }
    chatMessages.value.push({ from: 'bot', text: response })
    scrollChatToBottom()
  }, 1000)
}

const sendUserMessage = () => {
  if (!chatInput.value.trim()) return
  
  const userMsg = chatInput.value.trim()
  chatMessages.value.push({ from: 'user', text: userMsg })
  chatInput.value = ''
  chatTyping.value = true
  scrollChatToBottom()
  
  setTimeout(() => {
    chatTyping.value = false
    let response = ''
    const lowerMsg = userMsg.toLowerCase()
    
    if (lowerMsg.includes('precio') || lowerMsg.includes('costo') || lowerMsg.includes('cuanto')) {
      response = 'Nuestros planes son:\n\n🆓 Gratis: $0/mes\n⭐ Profesional: $299/mes\n👑 Ilimitado: $799/mes\n\nTodos con prueba gratuita. ¿Te interesa alguno?'
    } else if (lowerMsg.includes('demo') || lowerMsg.includes('prueba')) {
      response = '¡Excelente! Puedes:\n\n✅ Probar gratis por 14 días sin tarjeta\n✅ Agendar una demo personalizada de 30 min\n\n¿Qué prefieres?'
    } else if (lowerMsg.includes('hola') || lowerMsg.includes('buenos')) {
      response = '¡Hola! 👋\n\nSoy tu asistente de StockWolf. ¿En qué puedo ayudarte hoy?'
    } else if (lowerMsg.includes('gracias')) {
      response = '¡De nada! 😊\n\n¿Hay algo más en lo que pueda ayudarte?'
    } else {
      response = 'Gracias por tu mensaje. Para darte una mejor atención, te conectaré con uno de nuestros consultores...'
      setTimeout(() => openWhatsApp(), 2000)
    }
    
    chatMessages.value.push({ from: 'bot', text: response })
    scrollChatToBottom()
  }, 800)
}

const handleScroll = () => {
  scrolled.value = window.scrollY > 50
}

const scrollToSection = (sectionId) => {
  mobileMenuOpen.value = false
  selectedArticle.value = null
  document.body.style.overflow = ''
  if (sectionId === 'top') {
    window.scrollTo({ top: 0, behavior: 'smooth' })
    return
  }
  const element = document.getElementById(sectionId)
  if (element) {
    const offset = 80
    const elementPosition = element.getBoundingClientRect().top
    const offsetPosition = elementPosition + window.pageYOffset - offset
    window.scrollTo({ top: offsetPosition, behavior: 'smooth' })
  }
}

const openWhatsApp = () => {
  const phone = '524776940272'
  const message = encodeURIComponent('Hola Carlos, estoy interesado en StockWolf. ¿Podemos agendar una llamada? 🐺')
  window.open(`https://wa.me/${phone}?text=${message}`, '_blank', 'noopener,noreferrer')
}

const sendEmail = () => {
  const email = 'ing.carlosurbina@gmail.com'
  const subject = encodeURIComponent('Consulta sobre StockWolf 🐺')
  const body = encodeURIComponent('Hola Carlos,\n\nEstoy interesado en StockWolf para mi empresa.\n\nSaludos,')
  window.location.href = `mailto:${email}?subject=${subject}&body=${body}`
}

// Pricing Plans
const pricingPlans = [
  {
    name: 'Gratis',
    price: '0',
    period: 'mes',
    description: 'Para empezar sin costo',
    features: ['1 Almacén', '100 Productos', '1 Usuario', 'Reportes básicos', 'Soporte por email'],
    cta: 'Registrarse Gratis',
    popular: false
  },
  {
    name: 'Profesional',
    price: '299',
    period: 'mes',
    description: 'Ideal para negocios en crecimiento',
    features: ['10 Almacenes', '500 Productos', '5 Usuarios', 'Reportes avanzados', 'Etiquetas DYMO', 'Soporte prioritario', '🎁 1 mes GRATIS'],
    cta: 'Prueba Gratis 1 Mes',
    popular: true
  },
  {
    name: 'Ilimitado',
    price: '799',
    period: 'mes',
    description: 'Todo ilimitado para tu empresa',
    features: ['Almacenes ilimitados', 'Productos ilimitados', 'Usuarios ilimitados', 'API completa', 'Consultoría dedicada', 'Soporte 24/7'],
    cta: 'Elegir Plan',
    popular: false
  }
]

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  document.body.style.overflow = ''
})
</script>

<style scoped>
@keyframes float {
  0%, 100% { transform: translateY(0) rotate(45deg); }
  50% { transform: translateY(-20px) rotate(45deg); }
}

@keyframes gridMove {
  0% { transform: translateY(0); }
  100% { transform: translateY(50px); }
}

@keyframes particle {
  0%, 100% { 
    transform: translateY(0) scale(1);
    opacity: 0.5;
  }
  50% { 
    transform: translateY(-100px) scale(1.2);
    opacity: 1;
  }
}

@keyframes drawLine {
  0% { stroke-dashoffset: 1000; }
  100% { stroke-dashoffset: 0; }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

.animate-particle {
  animation: particle 8s ease-in-out infinite;
}

.animate-draw-line {
  stroke-dasharray: 1000;
  stroke-dashoffset: 1000;
  animation: drawLine 5s ease-out forwards;
}

@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}

.animate-fade-in-up {
  animation: fade-in-up 0.3s ease-out;
}

.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}

/* Smooth scroll behavior */
html {
  scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(46, 125, 232, 0.1);
}

::-webkit-scrollbar-thumb {
  background: rgba(46, 125, 232, 0.3);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(46, 125, 232, 0.5);
}

/* Prose styles for article content */
.prose h3 {
  font-size: 1.35rem;
  font-weight: 700;
  margin-top: 1.75rem;
  margin-bottom: 1rem;
  color: inherit;
}

.prose ul {
  list-style-type: disc;
  padding-left: 1.5rem;
  margin-bottom: 1.25rem;
}

.prose ul li {
  margin-bottom: 0.5rem;
  line-height: 1.7;
}

.prose p {
  margin-bottom: 1.25rem;
  line-height: 1.8;
}

.prose strong {
  font-weight: 700;
  color: #2E7DE8;
}

.prose-invert {
  color: #d1d5db;
}

.prose-invert h3 {
  color: white;
}
</style>
