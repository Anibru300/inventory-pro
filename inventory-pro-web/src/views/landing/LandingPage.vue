<template>
  <div class="min-h-screen overflow-x-hidden" :class="isDark ? 'bg-[#0B1F3A]' : 'bg-white'">
    <!-- Animated Professional Background -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
      <!-- Base Gradient -->
      <div class="absolute inset-0 bg-gradient-to-br from-[#0B1F3A] via-[#0f2642] to-[#1a3a5c]"></div>
      
      <!-- Animated Orbs -->
      <div class="absolute top-0 left-1/4 w-[600px] h-[600px] bg-[#2E7DE8]/20 rounded-full blur-[100px] animate-pulse"></div>
      <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-[#2E7DE8]/15 rounded-full blur-[80px]" style="animation: pulse 4s ease-in-out infinite; animation-delay: 1s;"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-r from-[#2E7DE8]/10 to-transparent rounded-full blur-[120px]" style="animation: pulse 6s ease-in-out infinite; animation-delay: 2s;"></div>
      
      <!-- Silver Accent Lines -->
      <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#C0C0C0]/30 to-transparent"></div>
      <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#C0C0C0]/30 to-transparent"></div>
      
      <!-- Animated Grid Pattern -->
      <div class="absolute inset-0 opacity-[0.03]" 
        style="background-image: 
          linear-gradient(rgba(46,125,232,0.5) 1px, transparent 1px),
          linear-gradient(90deg, rgba(46,125,232,0.5) 1px, transparent 1px);
        background-size: 50px 50px;
        animation: gridMove 20s linear infinite;">
      </div>
      
      <!-- Floating Geometric Shapes -->
      <div class="absolute top-20 left-[10%] w-20 h-20 border-2 border-[#2E7DE8]/20 rotate-45 animate-float" style="animation-delay: 0s;"></div>
      <div class="absolute top-40 right-[15%] w-16 h-16 border-2 border-[#C0C0C0]/20 rounded-full animate-float" style="animation-delay: 1s;"></div>
      <div class="absolute bottom-32 left-[20%] w-24 h-24 border-2 border-[#2E7DE8]/10 rotate-12 animate-float" style="animation-delay: 2s;"></div>
      <div class="absolute top-1/3 right-[10%] w-12 h-12 bg-[#2E7DE8]/5 rotate-45 animate-float" style="animation-delay: 1.5s;"></div>
      <div class="absolute bottom-1/4 right-[25%] w-32 h-1 bg-gradient-to-r from-transparent via-[#C0C0C0]/20 to-transparent animate-float" style="animation-delay: 0.5s;"></div>
      
      <!-- Connection Lines (Consulting Network Effect) -->
      <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <linearGradient id="lineGrad" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#2E7DE8;stop-opacity:0" />
            <stop offset="50%" style="stop-color:#2E7DE8;stop-opacity:0.3" />
            <stop offset="100%" style="stop-color:#2E7DE8;stop-opacity:0" />
          </linearGradient>
        </defs>
        <!-- Animated connection lines -->
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
      
      <!-- Binary/Code Rain Effect (subtle) -->
      <div class="absolute top-0 right-0 w-32 h-full opacity-[0.02] overflow-hidden font-mono text-[#2E7DE8] text-xs leading-none">
        <div class="animate-rain">
          10110<br>01001<br>11010<br>00101<br>11100<br>01011<br>10101<br>01110<br>10011<br>00111
        </div>
      </div>
      
      <!-- Glow Effect behind content areas -->
      <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[90%] h-[400px] bg-gradient-to-b from-[#2E7DE8]/5 to-transparent rounded-full blur-3xl"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300" 
      :class="[scrolled ? (isDark ? 'bg-[#0B1F3A]/95 backdrop-blur-md shadow-lg border-b border-[#2E7DE8]/20' : 'bg-white/95 backdrop-blur-md shadow-lg') : 'bg-transparent']">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
          <!-- Logo -->
          <div class="flex items-center gap-3 cursor-pointer" @click="scrollToSection('top')">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg overflow-hidden border-2 border-[#C0C0C0] bg-white">
              <img src="/logo-cj.png" alt="CJ Consultoría" class="w-full h-full object-cover" />
            </div>
            <div>
              <span class="text-xl font-bold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">StockMaster</span>
              <span class="text-xl font-bold text-[#2E7DE8]">Pro</span>
            </div>
          </div>

          <!-- Desktop Menu -->
          <div class="hidden md:flex items-center gap-8">
            <button @click="scrollToSection('features')" class="text-sm font-medium transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-300' : 'text-gray-600'">Características</button>
            <button @click="scrollToSection('how-it-works')" class="text-sm font-medium transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-300' : 'text-gray-600'">Cómo Funciona</button>
            <button @click="scrollToSection('pricing')" class="text-sm font-medium transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-300' : 'text-gray-600'">Precios</button>
            <button @click="scrollToSection('consulting')" class="text-sm font-medium transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-300' : 'text-gray-600'">Consultoría</button>
          </div>

          <!-- CTA Buttons -->
          <div class="flex items-center gap-4">
            <button @click="toggleDarkMode" class="p-2 rounded-lg transition-colors" :class="isDark ? 'text-gray-300 hover:bg-[#1a3050]' : 'text-gray-600 hover:bg-gray-100'">
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
            <router-link to="/register" class="hidden sm:block px-5 py-2.5 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] hover:from-[#1e6ad1] hover:to-[#2E7DE8] text-white rounded-xl font-medium transition-all shadow-lg shadow-[#2E7DE8]/30 hover:shadow-[#2E7DE8]/50">
              Prueba Gratis
            </router-link>
            
            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg" :class="isDark ? 'text-white hover:bg-[#1a3050]' : 'text-gray-700 hover:bg-gray-100'">
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
            <button @click="scrollToSection('features'); mobileMenuOpen = false" class="text-left px-4 py-2 rounded-lg transition-colors" :class="isDark ? 'text-gray-300 hover:bg-[#1a3050]' : 'text-gray-600 hover:bg-gray-100'">Características</button>
            <button @click="scrollToSection('how-it-works'); mobileMenuOpen = false" class="text-left px-4 py-2 rounded-lg transition-colors" :class="isDark ? 'text-gray-300 hover:bg-[#1a3050]' : 'text-gray-600 hover:bg-gray-100'">Cómo Funciona</button>
            <button @click="scrollToSection('pricing'); mobileMenuOpen = false" class="text-left px-4 py-2 rounded-lg transition-colors" :class="isDark ? 'text-gray-300 hover:bg-[#1a3050]' : 'text-gray-600 hover:bg-gray-100'">Precios</button>
            <button @click="scrollToSection('consulting'); mobileMenuOpen = false" class="text-left px-4 py-2 rounded-lg transition-colors" :class="isDark ? 'text-gray-300 hover:bg-[#1a3050]' : 'text-gray-600 hover:bg-gray-100'">Consultoría</button>
            <router-link to="/login" @click="mobileMenuOpen = false" class="text-left px-4 py-2 rounded-lg transition-colors" :class="isDark ? 'text-gray-300 hover:bg-[#1a3050]' : 'text-gray-600 hover:bg-gray-100'">Iniciar Sesión</router-link>
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
              :class="isDark ? 'bg-[#2E7DE8]/10 border-[#2E7DE8]/30 text-[#2E7DE8]' : 'bg-blue-50 border-blue-200 text-[#2E7DE8]'">
              <span class="flex h-2 w-2 rounded-full bg-[#2E7DE8] animate-pulse"></span>
              <span class="text-sm font-medium">Desarrollado por CJ Consultoría</span>
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
              StockMaster Pro
              <span class="block text-2xl md:text-3xl lg:text-4xl mt-2 text-[#2E7DE8]" style="font-family: 'Playfair Display', serif; font-style: italic;">
                Transformamos procesos en resultados sostenibles
              </span>
            </h1>
            
            <p class="text-lg md:text-xl mb-8 max-w-2xl mx-auto lg:mx-0" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
              Sistema ERP profesional para gestión de inventario multi-almacén. 
              Desarrollado por <strong class="text-[#2E7DE8]">CJ Consultoría</strong> para PYMEs y emprendedores.
              <br><span class="text-[#2E7DE8] font-semibold">Estrategia • Orden • Resultados</span>
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
              <router-link to="/register" class="px-8 py-4 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] hover:from-[#1e6ad1] hover:to-[#2E7DE8] text-white rounded-xl font-semibold transition-all shadow-xl shadow-[#2E7DE8]/30 hover:shadow-[#2E7DE8]/50 flex items-center justify-center gap-2 group">
                Comenzar Prueba Gratis
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </router-link>
              <button @click="scrollToSection('demo-form')" class="px-8 py-4 border-2 rounded-xl font-semibold transition-all flex items-center justify-center gap-2 border-[#C0C0C0] text-[#0B1F3A] hover:border-[#2E7DE8] hover:text-[#2E7DE8]" :class="isDark ? 'text-white border-gray-600 hover:border-[#2E7DE8]' : ''">
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
                <span class="text-sm">14 días gratis</span>
              </div>
              <div class="flex items-center gap-2" :class="isDark ? 'text-gray-400' : 'text-gray-500'">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm">Sin tarjeta</span>
              </div>
              <div class="flex items-center gap-2" :class="isDark ? 'text-gray-400' : 'text-gray-500'">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm">Soporte técnico</span>
              </div>
            </div>
          </div>

          <!-- Hero Image -->
          <div class="relative flex justify-center items-center">
            <div class="absolute -inset-4 bg-gradient-to-r from-[#2E7DE8] to-[#C0C0C0] rounded-full opacity-20 blur-3xl"></div>
            <div class="relative w-[400px] h-[400px] lg:w-[500px] lg:h-[500px] flex items-center justify-center">
              <img src="/logo-cj-sin-fondo.png" alt="CJ Consultoría" class="w-full h-full object-contain drop-shadow-2xl" style="transform: none;" />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 border-y relative" :class="isDark ? 'border-[#2E7DE8]/20 bg-[#0B1F3A]/30' : 'border-gray-200 bg-gray-50/50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
          <div class="p-4 rounded-2xl backdrop-blur-sm group hover:scale-105 transition-transform" :class="isDark ? 'bg-[#0B1F3A]/50' : 'bg-white/50'">
            <p class="text-3xl md:text-4xl font-bold text-[#2E7DE8]">
              <AnimatedCounter :target="500" suffix="+" />
            </p>
            <p class="text-sm mt-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">PYMEs confían</p>
          </div>
          <div class="p-4 rounded-2xl backdrop-blur-sm group hover:scale-105 transition-transform" :class="isDark ? 'bg-[#0B1F3A]/50' : 'bg-white/50'">
            <p class="text-3xl md:text-4xl font-bold text-[#2E7DE8]">
              <AnimatedCounter :target="25" suffix="%" prefix="+" />
            </p>
            <p class="text-sm mt-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Eficiencia promedio</p>
          </div>
          <div class="p-4 rounded-2xl backdrop-blur-sm group hover:scale-105 transition-transform" :class="isDark ? 'bg-[#0B1F3A]/50' : 'bg-white/50'">
            <p class="text-3xl md:text-4xl font-bold text-[#2E7DE8]">
              <AnimatedCounter :target="80" suffix="%" />
            </p>
            <p class="text-sm mt-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Tiempo ahorrado</p>
          </div>
          <div class="p-4 rounded-2xl backdrop-blur-sm group hover:scale-105 transition-transform" :class="isDark ? 'bg-[#0B1F3A]/50' : 'bg-white/50'">
            <p class="text-3xl md:text-4xl font-bold text-[#2E7DE8]">99.9%</p>
            <p class="text-sm mt-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Uptime garantizado</p>
          </div>
        </div>
      </div>
    </section>


    <!-- Features Section -->
    <section id="features" class="py-20 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <span class="inline-block px-4 py-1 rounded-full text-sm font-medium mb-4 border" :class="isDark ? 'bg-[#2E7DE8]/10 border-[#2E7DE8]/30 text-[#2E7DE8]' : 'bg-blue-50 border-blue-200 text-[#2E7DE8]'">
            Características
          </span>
          <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Todo lo que necesitas para gestionar tu inventario
          </h2>
          <p class="text-lg" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
            Desde el control básico hasta análisis avanzados, StockMaster Pro tiene las herramientas que tu negocio necesita.
          </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <FeatureCard 
            v-for="feature in features" 
            :key="feature.title"
            v-bind="feature"
            :is-dark="isDark"
          />
        </div>
      </div>
    </section>

    <!-- Trust Badges -->
    <section class="py-12 relative" :class="isDark ? 'bg-[#0B1F3A]/20' : 'bg-gray-100/50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <p class="text-center text-sm mb-8" :class="isDark ? 'text-gray-500' : 'text-gray-500'">
          EMPRESAS QUE CONFIAN EN NOSOTROS
        </p>
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-60">
          <div v-for="(client, idx) in trustedClients" :key="idx" 
            class="flex items-center gap-2 px-4 py-2 rounded-lg"
            :class="isDark ? 'bg-[#1a3050]/50' : 'bg-white'">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#2E7DE8] to-[#1e6ad1] flex items-center justify-center text-white font-bold text-xs">
              {{ client.initials }}
            </div>
            <span class="font-medium" :class="isDark ? 'text-gray-300' : 'text-gray-700'">{{ client.name }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Video Demo Section -->
    <section id="video-demo" class="py-20 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-12">
          <span class="inline-block px-4 py-1 rounded-full text-sm font-medium mb-4 border" :class="isDark ? 'bg-rose-500/10 border-rose-500/30 text-rose-400' : 'bg-rose-50 border-rose-200 text-rose-600'">
            🎬 Demo en Video
          </span>
          <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Mira StockMaster Pro en acción
          </h2>
          <p class="text-lg" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
            Descubre cómo transformar tu gestión de inventario en menos de 3 minutos
          </p>
        </div>

        <div class="max-w-4xl mx-auto">
          <div class="relative rounded-2xl overflow-hidden shadow-2xl border-4"
            :class="isDark ? 'border-[#2E7DE8]/30' : 'border-gray-200'">
            <div class="aspect-video bg-gradient-to-br from-[#0B1F3A] to-[#1a3a5c] flex items-center justify-center relative group cursor-pointer"
              @click="playVideo">
              <div class="absolute inset-0 opacity-20">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(46,125,232,0.3) 1px, transparent 0); background-size: 30px 30px;"></div>
              </div>
              <div class="relative z-10 w-24 h-24 rounded-full bg-[#2E7DE8] flex items-center justify-center shadow-lg shadow-[#2E7DE8]/50 group-hover:scale-110 transition-transform">
                <svg class="w-10 h-10 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M8 5v14l11-7z"/>
                </svg>
              </div>
              <div class="absolute bottom-0 left-0 right-0 p-8 bg-gradient-to-t from-black/80 to-transparent">
                <p class="text-white font-semibold text-lg">Tour completo de StockMaster Pro</p>
                <p class="text-gray-300 text-sm">Duración: 2:45 minutos</p>
              </div>
              <div class="absolute top-4 right-4 px-3 py-1 rounded-lg bg-black/60 text-white text-sm font-medium">
                2:45
              </div>
            </div>
            <div v-if="videoPlaying" class="absolute inset-0 bg-black">
              <iframe 
                class="w-full h-full"
                src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1" 
                title="StockMaster Pro Demo"
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
              </iframe>
            </div>
          </div>
          
          <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div v-for="(chapter, idx) in videoChapters" :key="idx" 
              class="p-4 rounded-xl border cursor-pointer transition-all hover:border-[#2E7DE8]"
              :class="isDark ? 'bg-[#0B1F3A]/50 border-gray-700 hover:bg-[#1a3050]' : 'bg-white border-gray-200 hover:bg-gray-50'"
              @click="playVideo">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#2E7DE8]/20 flex items-center justify-center text-[#2E7DE8] font-bold text-sm">
                  {{ chapter.time }}
                </div>
                <div>
                  <p class="font-medium text-sm" :class="isDark ? 'text-white' : 'text-gray-900'">{{ chapter.title }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Screenshots Carousel -->
    <section id="screenshots" class="py-20 relative" :class="isDark ? 'bg-[#0B1F3A]/30' : 'bg-gray-50/50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-12">
          <span class="inline-block px-4 py-1 rounded-full text-sm font-medium mb-4 border" :class="isDark ? 'bg-blue-500/10 border-blue-500/30 text-blue-400' : 'bg-blue-50 border-blue-200 text-blue-600'">
            📸 Screenshots
          </span>
          <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Explora la interfaz
          </h2>
          <p class="text-lg" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
            Diseño moderno, intuitivo y profesional
          </p>
        </div>

        <div class="relative">
          <div class="rounded-2xl overflow-hidden shadow-2xl border mb-6"
            :class="isDark ? 'border-[#2E7DE8]/30' : 'border-gray-200'">
            <div class="aspect-[16/10] bg-gradient-to-br from-[#0B1F3A] to-[#1a3a5c] p-4">
              <div class="w-full h-full rounded-lg overflow-hidden bg-[#0B1F3A] border"
                :class="isDark ? 'border-gray-700' : 'border-gray-300'">
                <div class="h-8 bg-[#1a3050] flex items-center px-3 gap-2">
                  <div class="flex gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                    <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                  </div>
                  <div class="flex-1 mx-4">
                    <div class="h-5 bg-[#0B1F3A] rounded text-xs flex items-center px-2 text-gray-400">
                      app.stockmasterpro.com/{{ screenshots[activeScreenshot].id }}
                    </div>
                  </div>
                </div>
                <div class="p-6 h-[calc(100%-2rem)] overflow-hidden">
                  <div class="h-full rounded-lg flex flex-col"
                    :class="isDark ? 'bg-[#0B1F3A]' : 'bg-white'">
                    <div class="h-12 border-b flex items-center px-4" :class="isDark ? 'border-gray-700' : 'border-gray-200'">
                      <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded bg-gradient-to-br from-[#2E7DE8] to-[#1e6ad1]"></div>
                        <span class="font-semibold" :class="isDark ? 'text-white' : 'text-gray-900'">StockMaster Pro</span>
                      </div>
                      <div class="ml-auto flex gap-2">
                        <div class="w-8 h-8 rounded-full" :class="isDark ? 'bg-gray-700' : 'bg-gray-200'"></div>
                      </div>
                    </div>
                    <div class="flex-1 p-4">
                      <component :is="screenshots[activeScreenshot].component" :is-dark="isDark" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="flex justify-center gap-4 flex-wrap">
            <button v-for="(screenshot, idx) in screenshots" :key="idx"
              @click="activeScreenshot = idx"
              class="w-24 h-16 rounded-lg overflow-hidden border-2 transition-all"
              :class="activeScreenshot === idx ? 'border-[#2E7DE8] ring-2 ring-[#2E7DE8]/30' : (isDark ? 'border-gray-700 opacity-60' : 'border-gray-200 opacity-60')">
              <div class="w-full h-full bg-gradient-to-br from-[#0B1F3A] to-[#1a3a5c] flex items-center justify-center">
                <component :is="screenshot.miniComponent" class="scale-50" :is-dark="isDark" />
              </div>
            </button>
          </div>

          <button @click="prevScreenshot" 
            class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-12 h-12 rounded-full bg-[#2E7DE8] text-white shadow-lg flex items-center justify-center hover:scale-110 transition-transform hidden md:flex">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <button @click="nextScreenshot"
            class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-12 h-12 rounded-full bg-[#2E7DE8] text-white shadow-lg flex items-center justify-center hover:scale-110 transition-transform hidden md:flex">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </div>
    </section>

    <!-- Demo Interactive Section -->
    <section id="demo-interactive" class="py-20 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-12">
          <span class="inline-block px-4 py-1 rounded-full text-sm font-medium mb-4 border" :class="isDark ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400' : 'bg-emerald-50 border-emerald-200 text-emerald-600'">
            🎮 Demo Interactiva
          </span>
          <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Pruébalo sin registrarte
          </h2>
          <p class="text-lg" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
            Explora el sistema con datos de prueba. Nada de información real requerida.
          </p>
        </div>

        <div class="rounded-2xl overflow-hidden shadow-2xl border"
          :class="isDark ? 'border-[#2E7DE8]/30' : 'border-gray-200'">
          <div class="p-4 border-b flex items-center justify-between" 
            :class="isDark ? 'bg-[#1a3050] border-gray-700' : 'bg-gray-50 border-gray-200'">
            <div class="flex items-center gap-3">
              <div class="w-3 h-3 rounded-full bg-rose-500"></div>
              <div class="w-3 h-3 rounded-full bg-amber-500"></div>
              <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
              <span class="ml-4 text-sm font-medium" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
                Modo Demo - Datos de prueba
              </span>
            </div>
            <router-link to="/register" 
              class="px-4 py-2 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] text-white rounded-lg text-sm font-medium hover:shadow-lg transition-all">
              Crear cuenta real →
            </router-link>
          </div>

          <div class="grid lg:grid-cols-[250px,1fr] min-h-[500px]" :class="isDark ? 'bg-[#0B1F3A]' : 'bg-white'">
            <div class="p-4 border-r" :class="isDark ? 'border-gray-700' : 'border-gray-200'">
              <div class="space-y-2">
                <div v-for="item in demoMenu" :key="item"
                  class="px-4 py-3 rounded-lg cursor-pointer transition-colors"
                  :class="demoActiveTab === item 
                    ? 'bg-[#2E7DE8] text-white' 
                    : (isDark ? 'hover:bg-[#1a3050] text-gray-400' : 'hover:bg-gray-100 text-gray-600')"
                  @click="demoActiveTab = item">
                  {{ item }}
                </div>
              </div>
              <div class="mt-6 p-4 rounded-xl border" :class="isDark ? 'bg-amber-500/10 border-amber-500/30' : 'bg-amber-50 border-amber-200'">
                <p class="text-sm" :class="isDark ? 'text-amber-400' : 'text-amber-700'">
                  <strong>ℹ️ Modo Demo</strong><br>
                  Los cambios no se guardan. Para usar el sistema real, crea una cuenta gratuita.
                </p>
              </div>
            </div>

            <div class="p-6">
              <div v-if="demoActiveTab === 'Dashboard'" class="space-y-6">
                <h3 class="text-xl font-semibold" :class="isDark ? 'text-white' : 'text-gray-900'">Dashboard</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                  <div v-for="stat in demoStats" :key="stat.label" 
                    class="p-4 rounded-xl border"
                    :class="isDark ? 'bg-[#1a3050]/50 border-gray-700' : 'bg-gray-50 border-gray-200'">
                    <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-500'">{{ stat.label }}</p>
                    <p class="text-2xl font-bold" :class="isDark ? 'text-white' : 'text-gray-900'">{{ stat.value }}</p>
                  </div>
                </div>
                <div class="p-6 rounded-xl border" :class="isDark ? 'border-gray-700' : 'border-gray-200'">
                  <h4 class="font-medium mb-4" :class="isDark ? 'text-white' : 'text-gray-900'">Movimientos del mes</h4>
                  <div class="flex items-end gap-2 h-32">
                    <div v-for="(bar, idx) in demoBars" :key="idx" 
                      class="flex-1 rounded-t transition-all hover:opacity-80 cursor-pointer"
                      :style="{ height: bar + '%', backgroundColor: idx % 2 === 0 ? '#2E7DE8' : '#10b981' }"
                      :title="'Semana ' + (idx + 1)">
                    </div>
                  </div>
                  <div class="flex justify-between mt-2 text-xs" :class="isDark ? 'text-gray-500' : 'text-gray-400'">
                    <span>Sem 1</span>
                    <span>Sem 2</span>
                    <span>Sem 3</span>
                    <span>Sem 4</span>
                  </div>
                </div>
              </div>

              <div v-else-if="demoActiveTab === 'Productos'" class="space-y-6">
                <div class="flex justify-between items-center">
                  <h3 class="text-xl font-semibold" :class="isDark ? 'text-white' : 'text-gray-900'">Productos</h3>
                  <button class="px-4 py-2 bg-[#2E7DE8] text-white rounded-lg text-sm">+ Nuevo Producto</button>
                </div>
                <div class="overflow-x-auto">
                  <table class="w-full">
                    <thead>
                      <tr :class="isDark ? 'text-gray-400 border-b border-gray-700' : 'text-gray-600 border-b border-gray-200'">
                        <th class="text-left py-3">Producto</th>
                        <th class="text-left py-3">SKU</th>
                        <th class="text-right py-3">Stock</th>
                        <th class="text-right py-3">Precio</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="product in demoProducts" :key="product.sku" 
                        class="border-b cursor-pointer hover:bg-[#2E7DE8]/5 transition-colors"
                        :class="isDark ? 'border-gray-700 text-gray-300' : 'border-gray-200 text-gray-700'">
                        <td class="py-3">{{ product.name }}</td>
                        <td class="py-3 font-mono text-sm text-[#2E7DE8]">{{ product.sku }}</td>
                        <td class="py-3 text-right">
                          <span :class="product.stock < 10 ? 'text-rose-400' : 'text-emerald-400'">{{ product.stock }}</span>
                        </td>
                        <td class="py-3 text-right">${{ product.price }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div v-else class="flex items-center justify-center h-64">
                <div class="text-center">
                  <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#2E7DE8]/20 flex items-center justify-center">
                    <svg class="w-8 h-8 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <p class="mb-4" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
                    Explora esta sección en la versión completa
                  </p>
                  <router-link to="/register" class="px-6 py-3 bg-[#2E7DE8] text-white rounded-xl inline-block">
                    Crear cuenta gratis
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <span class="inline-block px-4 py-1 rounded-full text-sm font-medium mb-4 border" :class="isDark ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400' : 'bg-emerald-50 border-emerald-200 text-emerald-600'">
            Testimonios
          </span>
          <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Lo que dicen nuestros clientes
          </h2>
          <p class="text-lg" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
            Historias reales de empresas que transformaron su gestión de inventario
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div v-for="(testimonial, idx) in testimonials" :key="idx" 
            class="p-8 rounded-2xl border transition-all hover:shadow-xl hover:-translate-y-1"
            :class="isDark ? 'bg-[#0B1F3A]/60 border-[#2E7DE8]/20' : 'bg-white border-gray-200'">
            <div class="flex gap-1 mb-4">
              <svg v-for="n in 5" :key="n" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </div>
            <p class="mb-6 italic" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
              "{{ testimonial.quote }}"
            </p>
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#2E7DE8] to-[#1e6ad1] flex items-center justify-center text-white font-bold">
                {{ testimonial.name.charAt(0) }}
              </div>
              <div>
                <p class="font-semibold" :class="isDark ? 'text-white' : 'text-gray-900'">{{ testimonial.name }}</p>
                <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-500'">{{ testimonial.role }}</p>
                <p class="text-xs text-[#2E7DE8]">{{ testimonial.company }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- NUEVO: Formulario de Solicitud de Demo (reemplaza Calendly) -->
    <section id="demo-form" class="py-20 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-start">
          <div>
            <span class="inline-block px-4 py-1 rounded-full text-sm font-medium mb-4 border" :class="isDark ? 'bg-purple-500/10 border-purple-500/30 text-purple-400' : 'bg-purple-50 border-purple-200 text-purple-600'">
              📅 Solicita una Demo Personalizada
            </span>
            <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
              ¿Prefieres una demostración personalizada?
            </h2>
            <p class="text-lg mb-6" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
              Completa el formulario y nos pondremos en contacto contigo en menos de 24 horas para agendar una videollamada de 30 minutos.
            </p>
            
            <div class="space-y-4">
              <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#2E7DE8]/20 flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-gray-900'">30 minutos</h4>
                  <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Suficiente para conocer tus necesidades y mostrarte la solución</p>
                </div>
              </div>
              
              <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#2E7DE8]/20 flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-gray-900'">Videollamada</h4>
                  <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Por Zoom, Google Meet o WhatsApp, como prefieras</p>
                </div>
              </div>
              
              <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#2E7DE8]/20 flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-gray-900'">Sin compromiso</h4>
                  <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Conoceremos tu caso y te daremos recomendaciones, sin presión</p>
                </div>
              </div>
            </div>
            
            <div class="mt-8 p-4 rounded-xl border" :class="isDark ? 'border-gray-700 bg-[#1a3050]/30' : 'border-gray-200 bg-gray-50'">
              <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
                <strong class="text-[#2E7DE8]">Respuesta garantizada:</strong> Te contactaremos en menos de 24 horas hábiles.
              </p>
            </div>
          </div>
          
          <!-- Formulario de Demo -->
          <div class="rounded-2xl overflow-hidden border shadow-xl"
            :class="isDark ? 'border-[#2E7DE8]/30 bg-[#0B1F3A]' : 'border-gray-200 bg-white'">
            <div class="p-6 border-b" :class="isDark ? 'border-gray-700' : 'border-gray-200'">
              <h3 class="font-semibold" :class="isDark ? 'text-white' : 'text-gray-900'">Solicitar demostración</h3>
              <p class="text-sm mt-1" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Completa tus datos y nos pondremos en contacto</p>
            </div>
            <div class="p-6">
              <form @submit.prevent="submitDemoRequest" class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">Nombre *</label>
                    <input 
                      v-model="demoForm.name" 
                      type="text" 
                      required
                      class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] transition-all"
                      :class="isDark ? 'bg-[#1a3050] border-gray-700 text-white placeholder-gray-500' : 'bg-white border-gray-300 text-gray-900'"
                      placeholder="Tu nombre"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">Empresa *</label>
                    <input 
                      v-model="demoForm.company" 
                      type="text" 
                      required
                      class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] transition-all"
                      :class="isDark ? 'bg-[#1a3050] border-gray-700 text-white placeholder-gray-500' : 'bg-white border-gray-300 text-gray-900'"
                      placeholder="Nombre de tu empresa"
                    />
                  </div>
                </div>
                
                <div class="grid md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">Email *</label>
                    <input 
                      v-model="demoForm.email" 
                      type="email" 
                      required
                      class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] transition-all"
                      :class="isDark ? 'bg-[#1a3050] border-gray-700 text-white placeholder-gray-500' : 'bg-white border-gray-300 text-gray-900'"
                      placeholder="tu@email.com"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">Teléfono *</label>
                    <input 
                      v-model="demoForm.phone" 
                      type="tel" 
                      required
                      class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] transition-all"
                      :class="isDark ? 'bg-[#1a3050] border-gray-700 text-white placeholder-gray-500' : 'bg-white border-gray-300 text-gray-900'"
                      placeholder="+52 123 456 7890"
                    />
                  </div>
                </div>
                
                <div>
                  <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">Número de empleados</label>
                  <select 
                    v-model="demoForm.employees"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] transition-all"
                    :class="isDark ? 'bg-[#1a3050] border-gray-700 text-white' : 'bg-white border-gray-300 text-gray-900'"
                  >
                    <option value="">Selecciona una opción</option>
                    <option value="1-5">1-5 empleados</option>
                    <option value="6-20">6-20 empleados</option>
                    <option value="21-50">21-50 empleados</option>
                    <option value="51+">Más de 50 empleados</option>
                  </select>
                </div>
                
                <div>
                  <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">¿Qué necesitas gestionar?</label>
                  <select 
                    v-model="demoForm.inventoryType"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] transition-all"
                    :class="isDark ? 'bg-[#1a3050] border-gray-700 text-white' : 'bg-white border-gray-300 text-gray-900'"
                  >
                    <option value="">Selecciona una opción</option>
                    <option value="productos">Productos físicos</option>
                    <option value="insumos">Insumos y materias primas</option>
                    <option value="equipos">Equipos y herramientas</option>
                    <option value="mixto">Combinación de los anteriores</option>
                  </select>
                </div>
                
                <div>
                  <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">Mensaje (opcional)</label>
                  <textarea 
                    v-model="demoForm.message" 
                    rows="3"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8] transition-all resize-none"
                    :class="isDark ? 'bg-[#1a3050] border-gray-700 text-white placeholder-gray-500' : 'bg-white border-gray-300 text-gray-900'"
                    placeholder="Cuéntanos brevemente sobre tus necesidades..."
                  ></textarea>
                </div>
                
                <div class="flex items-start gap-3">
                  <input 
                    id="demo-consent" 
                    v-model="demoForm.consent"
                    type="checkbox" 
                    required
                    class="mt-1 w-4 h-4 rounded border-gray-300 text-[#2E7DE8] focus:ring-[#2E7DE8]"
                  />
                  <label for="demo-consent" class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
                    Acepto que CJ Consultoría se comunique conmigo vía email o teléfono para coordinar la demostración. 
                    <a href="/aviso-privacidad.html" target="_blank" class="text-[#2E7DE8] hover:underline">Aviso de Privacidad</a>.
                  </label>
                </div>
                
                <button 
                  type="submit"
                  :disabled="demoSubmitting"
                  class="w-full px-6 py-4 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] hover:from-[#1e6ad1] hover:to-[#2E7DE8] text-white rounded-xl font-semibold transition-all shadow-lg flex items-center justify-center gap-2 disabled:opacity-70"
                >
                  <svg v-if="demoSubmitting" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <span>{{ demoSubmitting ? 'Enviando...' : 'Solicitar Demo' }}</span>
                </button>
                
                <p v-if="demoSuccess" class="text-center text-emerald-400 text-sm">
                  ✓ ¡Solicitud enviada! Te contactaremos pronto.
                </p>
                <p v-if="demoError" class="text-center text-rose-400 text-sm">
                  {{ demoError }}
                </p>
              </form>
              
              <!-- Alternativas de contacto -->
              <div class="mt-6 pt-6 border-t" :class="isDark ? 'border-gray-700' : 'border-gray-200'">
                <p class="text-center text-sm mb-4" :class="isDark ? 'text-gray-500' : 'text-gray-500'">O contáctanos directamente:</p>
                <div class="grid grid-cols-2 gap-3">
                  <button @click="openWhatsApp" 
                    class="p-3 rounded-xl border flex items-center justify-center gap-2 transition-colors"
                    :class="isDark ? 'border-[#25D366] text-[#25D366] hover:bg-[#25D366]/10' : 'border-[#25D366] text-[#25D366] hover:bg-[#25D366]/5'">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    WhatsApp
                  </button>
                  <button @click="sendEmail"
                    class="p-3 rounded-xl border flex items-center justify-center gap-2 transition-colors"
                    :class="isDark ? 'border-[#2E7DE8] text-[#2E7DE8] hover:bg-[#2E7DE8]/10' : 'border-[#2E7DE8] text-[#2E7DE8] hover:bg-[#2E7DE8]/5'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Email
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- Blog Section con Modal de Artículos -->
    <section id="blog" class="py-20 relative" :class="isDark ? 'bg-[#0B1F3A]/30' : 'bg-gray-50/50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
          <div>
            <span class="inline-block px-4 py-1 rounded-full text-sm font-medium mb-4 border" :class="isDark ? 'bg-cyan-500/10 border-cyan-500/30 text-cyan-400' : 'bg-cyan-50 border-cyan-200 text-cyan-600'">
              📝 Recursos y Conocimiento
            </span>
            <h2 class="text-3xl md:text-4xl font-bold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
              Aprende a optimizar tu negocio
            </h2>
            <p class="mt-2 text-lg" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
              Guías, tutoriales y mejores prácticas
            </p>
          </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <article v-for="(post, idx) in blogPosts" :key="idx" 
            class="rounded-2xl overflow-hidden border transition-all hover:shadow-xl hover:-translate-y-1 group cursor-pointer"
            :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-white border-gray-200'"
            @click="openArticle(post)">
            <div class="h-48 bg-gradient-to-br from-[#2E7DE8]/20 to-[#1a3a5c] flex items-center justify-center relative overflow-hidden">
              <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, rgba(46,125,232,0.5) 1px, transparent 0); background-size: 20px 20px;"></div>
              <div class="text-6xl">{{ post.icon }}</div>
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 rounded-full text-xs font-medium" :class="isDark ? 'bg-[#2E7DE8]/30 text-[#2E7DE8]' : 'bg-blue-100 text-blue-700'">
                  {{ post.category }}
                </span>
              </div>
            </div>
            
            <div class="p-6">
              <div class="flex items-center gap-2 text-sm mb-3" :class="isDark ? 'text-gray-500' : 'text-gray-500'">
                <span>{{ post.date }}</span>
                <span>•</span>
                <span>{{ post.readTime }}</span>
              </div>
              
              <h3 class="font-semibold text-lg mb-2 group-hover:text-[#2E7DE8] transition-colors" :class="isDark ? 'text-white' : 'text-gray-900'">
                {{ post.title }}
              </h3>
              
              <p class="text-sm mb-4" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
                {{ post.excerpt }}
              </p>
              
              <div class="flex items-center text-[#2E7DE8] font-medium text-sm">
                Leer artículo completo
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- Modal de Artículo -->
    <div v-if="selectedArticle" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="selectedArticle = null"></div>
      <div class="relative w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-2xl"
        :class="isDark ? 'bg-[#0B1F3A] border border-[#2E7DE8]/30' : 'bg-white border border-gray-200'">
        <!-- Header -->
        <div class="sticky top-0 p-6 border-b flex items-center justify-between"
          :class="isDark ? 'bg-[#0B1F3A] border-gray-700' : 'bg-white border-gray-200'">
          <div>
            <span class="px-3 py-1 rounded-full text-xs font-medium" :class="isDark ? 'bg-[#2E7DE8]/30 text-[#2E7DE8]' : 'bg-blue-100 text-blue-700'">
              {{ selectedArticle.category }}
            </span>
            <h2 class="text-xl font-bold mt-2" :class="isDark ? 'text-white' : 'text-gray-900'">{{ selectedArticle.title }}</h2>
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
        <div class="p-6">
          <div class="flex items-center gap-2 text-sm mb-6" :class="isDark ? 'text-gray-500' : 'text-gray-500'">
            <span>{{ selectedArticle.date }}</span>
            <span>•</span>
            <span>{{ selectedArticle.readTime }} de lectura</span>
          </div>
          
          <div class="prose max-w-none" :class="isDark ? 'prose-invert' : ''">
            <div v-html="selectedArticle.content"></div>
          </div>
          
          <!-- CTA al final del artículo -->
          <div class="mt-8 p-6 rounded-xl border" :class="isDark ? 'bg-[#1a3050]/50 border-[#2E7DE8]/30' : 'bg-blue-50 border-blue-200'">
            <h4 class="font-semibold mb-2" :class="isDark ? 'text-white' : 'text-gray-900'">¿Listo para aplicar esto en tu negocio?</h4>
            <p class="text-sm mb-4" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
              StockMaster Pro te ayuda a implementar estas mejores prácticas de forma automática.
            </p>
            <div class="flex gap-3">
              <router-link to="/register" class="px-6 py-3 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] text-white rounded-xl font-medium text-sm hover:shadow-lg transition-all">
                Prueba Gratis
              </router-link>
              <button @click="scrollToSection('demo-form'); selectedArticle = null" 
                class="px-6 py-3 border rounded-xl font-medium text-sm transition-colors"
                :class="isDark ? 'border-gray-600 text-gray-300 hover:border-[#2E7DE8]' : 'border-gray-300 text-gray-700 hover:border-[#2E7DE8]'">
                Solicitar Demo
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ROI Calculator Section -->
    <section id="roi-calculator" class="py-20 relative" :class="isDark ? 'bg-[#0B1F3A]/30' : 'bg-gray-50/50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div>
            <span class="inline-block px-4 py-1 rounded-full text-sm font-medium mb-4 border" :class="isDark ? 'bg-amber-500/10 border-amber-500/30 text-amber-400' : 'bg-amber-50 border-amber-200 text-amber-600'">
              Calculadora de ROI
            </span>
            <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
              Calcula tu ahorro mensual
            </h2>
            <p class="text-lg mb-8" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
              Descubre cuánto puedes ahorrar al automatizar tu gestión de inventario con StockMaster Pro.
            </p>
            
            <div class="space-y-6">
              <div>
                <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">
                  ¿Cuántas horas pierdes semanalmente en inventario?
                </label>
                <input 
                  v-model.number="roiCalculator.hoursPerWeek" 
                  type="range" 
                  min="5" 
                  max="40" 
                  class="w-full accent-[#2E7DE8]"
                />
                <div class="flex justify-between mt-1">
                  <span class="text-xs" :class="isDark ? 'text-gray-500' : 'text-gray-400'">5h</span>
                  <span class="font-semibold text-[#2E7DE8]">{{ roiCalculator.hoursPerWeek }} horas</span>
                  <span class="text-xs" :class="isDark ? 'text-gray-500' : 'text-gray-400'">40h</span>
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">
                  Valor por hora de tu tiempo ($MXN)
                </label>
                <input 
                  v-model.number="roiCalculator.hourlyRate" 
                  type="number" 
                  class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-[#2E7DE8]"
                  :class="isDark ? 'bg-[#1a3050] border-gray-700 text-white' : 'bg-white border-gray-300'"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium mb-2" :class="isDark ? 'text-gray-300' : 'text-gray-700'">
                  Pérdidas mensuales por inventario desorganizado ($MXN)
                </label>
                <input 
                  v-model.number="roiCalculator.monthlyLosses" 
                  type="range" 
                  min="0" 
                  max="50000" 
                  step="1000"
                  class="w-full accent-[#2E7DE8]"
                />
                <div class="flex justify-between mt-1">
                  <span class="text-xs" :class="isDark ? 'text-gray-500' : 'text-gray-400'">$0</span>
                  <span class="font-semibold text-[#2E7DE8]">${{ roiCalculator.monthlyLosses.toLocaleString() }}</span>
                  <span class="text-xs" :class="isDark ? 'text-gray-500' : 'text-gray-400'">$50k</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="p-8 rounded-2xl border"
            :class="isDark ? 'bg-gradient-to-br from-[#1a3050] to-[#0B1F3A] border-[#2E7DE8]/30' : 'bg-gradient-to-br from-white to-gray-50 border-gray-200'">
            <h3 class="text-xl font-semibold mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Tu ahorro estimado</h3>
            
            <div class="space-y-6">
              <div class="p-4 rounded-xl" :class="isDark ? 'bg-[#2E7DE8]/10' : 'bg-blue-50'">
                <p class="text-sm mb-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Ahorro mensual en tiempo</p>
                <p class="text-3xl font-bold text-[#2E7DE8]">${{ monthlyTimeSavings.toLocaleString() }}</p>
                <p class="text-xs mt-1" :class="isDark ? 'text-gray-500' : 'text-gray-500'">
                  {{ Math.round(roiCalculator.hoursPerWeek * 4 * 0.8) }} horas liberadas
                </p>
              </div>
              
              <div class="p-4 rounded-xl" :class="isDark ? 'bg-emerald-500/10' : 'bg-emerald-50'">
                <p class="text-sm mb-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Evitas pérdidas por</p>
                <p class="text-3xl font-bold text-emerald-400">${{ roiCalculator.monthlyLosses.toLocaleString() }}</p>
                <p class="text-xs mt-1" :class="isDark ? 'text-gray-500' : 'text-gray-500'">Reducción del 90% en mermas</p>
              </div>
              
              <div class="p-6 rounded-xl border-2 border-[#2E7DE8]/30" :class="isDark ? 'bg-[#0B1F3A]' : 'bg-white'">
                <p class="text-sm mb-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">ROI Mensual Total</p>
                <p class="text-4xl font-bold text-[#2E7DE8]">${{ totalMonthlyROI.toLocaleString() }}</p>
                <p class="text-sm mt-2" :class="isDark ? 'text-emerald-400' : 'text-emerald-600'">
                  Inversión en plan Profesional: $299/mes
                </p>
                <p class="text-lg font-semibold mt-2" :class="isDark ? 'text-white' : 'text-gray-900'">
                  Retorno: <span class="text-emerald-400">{{ roiMultiplier }}x</span> tu inversión
                </p>
              </div>
            </div>
            
            <router-link to="/register" 
              class="w-full mt-6 px-6 py-4 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] hover:from-[#1e6ad1] hover:to-[#2E7DE8] text-white rounded-xl font-semibold transition-all shadow-lg text-center block">
              Comenzar Prueba Gratis
            </router-link>
          </div>
        </div>
      </div>
    </section>

    <!-- Comparison Section -->
    <section class="py-20 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <span class="inline-block px-4 py-1 rounded-full text-sm font-medium mb-4 border" :class="isDark ? 'bg-purple-500/10 border-purple-500/30 text-purple-400' : 'bg-purple-50 border-purple-200 text-purple-600'">
            Comparativa
          </span>
          <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            ¿Por qué elegir StockMaster Pro?
          </h2>
          <p class="text-lg" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
            Comparación honesta con otras soluciones del mercado
          </p>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full rounded-2xl overflow-hidden">
            <thead>
              <tr :class="isDark ? 'bg-[#1a3050]' : 'bg-gray-100'">
                <th class="text-left py-4 px-6 font-semibold" :class="isDark ? 'text-white' : 'text-gray-900'">Característica</th>
                <th class="text-center py-4 px-6 font-bold bg-[#2E7DE8]/20 text-[#2E7DE8]">StockMaster Pro</th>
                <th class="text-center py-4 px-6" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Excel</th>
                <th class="text-center py-4 px-6" :class="isDark ? 'text-gray-400' : 'text-gray-500'">SAP/Odoo</th>
              </tr>
            </thead>
            <tbody :class="isDark ? 'divide-y divide-gray-700' : 'divide-y divide-gray-200'">
              <tr v-for="(row, idx) in comparisonTable" :key="idx" :class="isDark ? 'bg-[#0B1F3A]/40' : 'bg-white'">
                <td class="py-4 px-6 font-medium" :class="isDark ? 'text-gray-300' : 'text-gray-700'">{{ row.feature }}</td>
                <td class="py-4 px-6 text-center">
                  <span v-if="row.stockmaster === true" class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400">✓</span>
                  <span v-else class="text-[#2E7DE8] font-semibold">{{ row.stockmaster }}</span>
                </td>
                <td class="py-4 px-6 text-center">
                  <span v-if="row.excel === true" class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400">✓</span>
                  <span v-else-if="row.excel === false" class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-rose-500/20 text-rose-400">✗</span>
                  <span v-else class="text-gray-500">{{ row.excel }}</span>
                </td>
                <td class="py-4 px-6 text-center">
                  <span v-if="row.competitor === true" class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400">✓</span>
                  <span v-else-if="row.competitor === false" class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-rose-500/20 text-rose-400">✗</span>
                  <span v-else class="text-gray-500">{{ row.competitor }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <div class="mt-8 text-center">
          <p class="text-sm mb-4" :class="isDark ? 'text-gray-400' : 'text-gray-500'">
            *SAP y Odoo son excelentes opciones para empresas grandes con presupuestos de $50,000+ mensuales
          </p>
        </div>
      </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 relative" :class="isDark ? 'bg-[#0B1F3A]/30' : 'bg-gray-50/50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <span class="inline-block px-4 py-1 rounded-full text-sm font-medium mb-4 border" :class="isDark ? 'bg-[#2E7DE8]/10 border-[#2E7DE8]/30 text-[#2E7DE8]' : 'bg-blue-50 border-blue-200 text-[#2E7DE8]'">
            Proceso
          </span>
          <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            ¿Cómo funciona?
          </h2>
          <p class="text-lg" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
            Comienza a gestionar tu inventario profesionalmente en 4 simples pasos
          </p>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
          <StepCard 
            v-for="(step, index) in steps" 
            :key="step.title"
            :step="index + 1"
            v-bind="step"
            :is-dark="isDark"
          />
        </div>
      </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 relative">
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#2E7DE8]/5 to-transparent pointer-events-none"></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <span class="inline-block px-4 py-1 rounded-full text-sm font-medium mb-4 border" :class="isDark ? 'bg-[#2E7DE8]/10 border-[#2E7DE8]/30 text-[#2E7DE8]' : 'bg-blue-50 border-blue-200 text-[#2E7DE8]'">
            Precios
          </span>
          <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Planes y Precios
          </h2>
          <p class="text-lg" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
            Inversión accesible para PYMEs y emprendedores
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto items-center">
          <PricingCard 
            v-for="plan in pricingPlans" 
            :key="plan.name"
            v-bind="plan"
            :is-dark="isDark"
          />
        </div>

        <div class="mt-16 text-center">
          <p class="text-lg mb-4" :class="isDark ? 'text-gray-300' : 'text-gray-700'">
            ¿Necesitas un plan personalizado para tu empresa?
          </p>
          <button @click="openWhatsApp" class="inline-flex items-center gap-2 px-8 py-4 bg-[#25D366] hover:bg-[#128C7E] text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl active:scale-95">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Contactar por WhatsApp
          </button>
        </div>
      </div>
    </section>


    <!-- Consulting Section -->
    <section id="consulting" class="py-20 relative" :class="isDark ? 'bg-[#0B1F3A]/50' : 'bg-gray-50/50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div>
            <span class="inline-block px-4 py-1 rounded-full text-sm font-medium mb-4 border" :class="isDark ? 'bg-[#2E7DE8]/10 border-[#2E7DE8]/30 text-[#2E7DE8]' : 'bg-blue-50 border-blue-200 text-[#2E7DE8]'">
              Consultoría Especializada
            </span>

            <h2 class="text-3xl md:text-4xl font-bold mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
              CJ Consultoría: Tu socio estratégico
            </h2>

            <p class="text-lg mb-4" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
              <strong class="text-[#2E7DE8]">Misión:</strong> Empoderar a PYMEs y emprendedores mediante la transformación de sus procesos en resultados sostenibles.
            </p>

            <p class="text-lg mb-6" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
              <strong class="text-[#2E7DE8]">Visión:</strong> Ser la consultoría de referencia para PYMEs y emprendedores en Latinoamérica.
            </p>

            <div class="grid grid-cols-2 gap-4 mb-8">
              <div class="p-4 rounded-xl border backdrop-blur-sm" :class="isDark ? 'bg-[#0B1F3A]/50 border-[#2E7DE8]/20' : 'bg-white/50 border-gray-200'">
                <h4 class="font-semibold mb-2 flex items-center gap-2 text-[#2E7DE8]">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  Procedimientos
                </h4>
                <ul class="text-sm space-y-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
                  <li>• Mapeo de procesos</li>
                  <li>• Manuales de procedimientos</li>
                  <li>• Indicadores (KPIs)</li>
                </ul>
              </div>
              <div class="p-4 rounded-xl border backdrop-blur-sm" :class="isDark ? 'bg-[#0B1F3A]/50 border-[#2E7DE8]/20' : 'bg-white/50 border-gray-200'">
                <h4 class="font-semibold mb-2 flex items-center gap-2 text-[#2E7DE8]">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Sistemas de Gestión
                </h4>
                <ul class="text-sm space-y-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
                  <li>• ISO 9001 (Calidad)</li>
                  <li>• ISO 14001 (Ambiental)</li>
                  <li>• ISO 45001 (Seguridad)</li>
                </ul>
              </div>
              <div class="p-4 rounded-xl border backdrop-blur-sm" :class="isDark ? 'bg-[#0B1F3A]/50 border-[#2E7DE8]/20' : 'bg-white/50 border-gray-200'">
                <h4 class="font-semibold mb-2 flex items-center gap-2 text-[#2E7DE8]">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                  </svg>
                  Mejora Continua
                </h4>
                <ul class="text-sm space-y-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
                  <li>• Kaizen, Lean, 5S</li>
                  <li>• PDCA</li>
                  <li>• Six Sigma</li>
                </ul>
              </div>
              <div class="p-4 rounded-xl border backdrop-blur-sm" :class="isDark ? 'bg-[#0B1F3A]/50 border-[#2E7DE8]/20' : 'bg-white/50 border-gray-200'">
                <h4 class="font-semibold mb-2 flex items-center gap-2 text-[#2E7DE8]">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  Excel y Análisis
                </h4>
                <ul class="text-sm space-y-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
                  <li>• Dashboards y macros</li>
                  <li>• Power BI</li>
                  <li>• Análisis de datos</li>
                </ul>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
              <button @click="openWhatsApp" class="px-8 py-4 bg-[#25D366] hover:bg-[#128C7E] text-white rounded-xl font-semibold transition-all shadow-lg flex items-center justify-center gap-2 active:scale-95">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                WhatsApp
              </button>
              <button @click="sendEmail" class="px-8 py-4 bg-[#2E7DE8] hover:bg-[#1e6ad1] text-white rounded-xl font-semibold transition-all shadow-lg flex items-center justify-center gap-2 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Email
              </button>
            </div>
          </div>

          <div class="relative">
            <div class="absolute -inset-4 bg-gradient-to-r from-[#2E7DE8] to-[#C0C0C0] rounded-2xl opacity-20 blur-2xl"></div>
            <div class="relative rounded-2xl p-8 border-2 backdrop-blur-sm"
              :class="isDark ? 'bg-[#0B1F3A]/80 border-[#2E7DE8]/30' : 'bg-white/80 border-[#C0C0C0]'">
              
              <div class="flex items-center gap-4 mb-6">
                <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-[#C0C0C0] shadow-lg">
                  <img src="/logo-cj.png" alt="CJ Consultoría" class="w-full h-full object-cover" />
                </div>
                <div>
                  <h3 class="text-xl font-bold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">Carlos Urbina</h3>
                  <p class="text-sm" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Consultor Principal</p>
                </div>
              </div>

              <p class="text-sm mb-6 italic" :class="isDark ? 'text-gray-300' : 'text-gray-600'" style="font-family: 'Playfair Display', serif;">
                "Transformamos procesos en resultados sostenibles"
              </p>

              <div class="space-y-4">
                <button @click="openWhatsApp" class="w-full flex items-center gap-3 p-4 rounded-xl transition-all border hover:shadow-md active:scale-95" :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20 hover:border-[#25D366]' : 'bg-gray-50 border-gray-200 hover:border-[#25D366]'">
                  <div class="w-10 h-10 rounded-full bg-[#25D366] flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                  </div>
                  <div class="text-left">
                    <p class="text-xs" :class="isDark ? 'text-gray-400' : 'text-gray-500'">WhatsApp</p>
                    <p class="font-medium" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">+52 477 694 0272</p>
                  </div>
                </button>

                <button @click="sendEmail" class="w-full flex items-center gap-3 p-4 rounded-xl transition-all border hover:shadow-md active:scale-95" :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20 hover:border-[#2E7DE8]' : 'bg-gray-50 border-gray-200 hover:border-[#2E7DE8]'">
                  <div class="w-10 h-10 rounded-full bg-[#2E7DE8] flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div class="text-left">
                    <p class="text-xs" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Email</p>
                    <p class="font-medium break-all" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">ing.carlosurbina@gmail.com</p>
                  </div>
                </button>

                <div class="flex items-center gap-3 p-4 rounded-xl border" :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/20' : 'bg-gray-50 border-gray-200'">
                  <div class="w-10 h-10 rounded-full bg-[#C0C0C0] flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#0B1F3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </div>
                  <div class="text-left">
                    <p class="text-xs" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Ubicación</p>
                    <p class="font-medium" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">León, Guanajuato, México</p>
                  </div>
                </div>
              </div>

              <div class="mt-6 pt-6 border-t" :class="isDark ? 'border-gray-700' : 'border-gray-200'">
                <p class="text-xs text-center" :class="isDark ? 'text-gray-400' : 'text-gray-500'">
                  Certificaciones: ISO 9001 | Lean Six Sigma | Excel Avanzado
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Values Section -->
    <section class="py-16 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-12">
          <h2 class="text-2xl md:text-3xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Nuestros Valores
          </h2>
        </div>
        <div class="grid md:grid-cols-5 gap-6">
          <div v-for="value in values" :key="value.name" class="text-center p-6 rounded-xl border transition-all hover:shadow-lg backdrop-blur-sm"
            :class="isDark ? 'bg-[#0B1F3A]/50 border-[#2E7DE8]/20 hover:border-[#2E7DE8]/50' : 'bg-white/50 border-gray-200 hover:border-[#2E7DE8]'">
            <div class="text-3xl mb-3">{{ value.icon }}</div>
            <h3 class="font-semibold mb-2 text-sm" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">{{ value.name }}</h3>
            <p class="text-xs" :class="isDark ? 'text-gray-400' : 'text-gray-600'">{{ value.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 relative">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="relative rounded-3xl overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-[#0B1F3A] to-[#2E7DE8]"></div>
          <div class="absolute inset-0 opacity-30">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 40px 40px;"></div>
          </div>
          
          <div class="relative p-12 md:p-16 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6" style="font-family: 'Montserrat', sans-serif;">
              ¿Listo para transformar tu gestión de inventario?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
              Únete a las PYMEs que ya confían en StockMaster Pro y CJ Consultoría.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <router-link to="/register" class="px-8 py-4 bg-white text-[#2E7DE8] rounded-xl font-semibold hover:bg-blue-50 transition-all shadow-lg active:scale-95">
                Comenzar Prueba Gratis
              </router-link>
              <button @click="openWhatsApp" class="px-8 py-4 border-2 border-white text-white rounded-xl font-semibold hover:bg-white/10 transition-all flex items-center justify-center gap-2 active:scale-95">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                WhatsApp
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t relative" :class="isDark ? 'border-[#2E7DE8]/20 bg-[#0B1F3A]' : 'border-gray-200 bg-gray-50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
          <!-- Brand -->
          <div class="col-span-2">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-12 h-12 rounded-xl overflow-hidden border-2 border-[#C0C0C0] shadow-lg">
                <img src="/logo-cj.png" alt="CJ Consultoría" class="w-full h-full object-cover" />
              </div>
              <span class="text-xl font-bold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">StockMaster Pro</span>
            </div>
            <p class="text-sm mb-4 max-w-sm" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
              Sistema ERP profesional para gestión de inventario. Desarrollado por 
              <strong class="text-[#2E7DE8]">CJ Consultoría</strong> - Transformamos procesos en resultados sostenibles.
            </p>
            <div class="flex gap-4">
              <button @click="openWhatsApp" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors bg-[#25D366] text-white hover:bg-[#128C7E] active:scale-95">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
              </button>
              <button @click="sendEmail" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors" :class="isDark ? 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white active:scale-95' : 'bg-white text-gray-600 hover:bg-gray-100 active:scale-95'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              </button>
            </div>
          </div>

          <!-- Links -->
          <div>
            <h4 class="font-semibold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Producto</h4>
            <ul class="space-y-2 text-sm">
              <li><button @click="scrollToSection('features')" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Características</button></li>
              <li><button @click="scrollToSection('pricing')" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Precios</button></li>
              <li><router-link to="/login" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Iniciar Sesión</router-link></li>
              <li><router-link to="/register" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Registrarse</router-link></li>
            </ul>
          </div>

          <div>
            <h4 class="font-semibold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">CJ Consultoría</h4>
            <ul class="space-y-2 text-sm">
              <li><button @click="scrollToSection('consulting')" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Nosotros</button></li>
              <li><button @click="openWhatsApp" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Contacto WhatsApp</button></li>
              <li><button @click="sendEmail" class="transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Contacto Email</button></li>
              <li><span class="text-xs" :class="isDark ? 'text-gray-500' : 'text-gray-400'">León, Gto. México</span></li>
            </ul>
          </div>
        </div>

        <div class="pt-8 border-t flex flex-col md:flex-row justify-between items-center gap-4" :class="isDark ? 'border-gray-800' : 'border-gray-200'">
          <p class="text-sm" :class="isDark ? 'text-gray-500' : 'text-gray-500'">
            © 2026 StockMaster Pro & CJ Consultoría. Todos los derechos reservados.
          </p>
          <div class="flex gap-6 text-sm">
            <a href="/terminos-condiciones.html" target="_blank" class="text-xs transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-500' : 'text-gray-500'">
              Términos y Condiciones
            </a>
            <a href="/aviso-privacidad.html" target="_blank" class="text-xs transition-colors hover:text-[#2E7DE8]" :class="isDark ? 'text-gray-500' : 'text-gray-500'">
              Aviso de Privacidad
            </a>
            <span class="text-xs" :class="isDark ? 'text-gray-500' : 'text-gray-500'">
              <strong class="text-[#2E7DE8]">Tel:</strong> 477 694 0272
            </span>
          </div>
        </div>
      </div>
    </footer>

    <!-- ChatBot Flotante -->
    <div class="fixed bottom-6 right-6 z-50">
      <div v-if="chatOpen" 
        class="mb-4 w-80 rounded-2xl shadow-2xl overflow-hidden border animate-fade-in-up"
        :class="isDark ? 'bg-[#0B1F3A] border-[#2E7DE8]/30' : 'bg-white border-gray-200'">
        <div class="p-4 bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1]">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
              <span class="text-white font-bold">CJ</span>
            </div>
            <div>
              <p class="font-semibold text-white">Asistente CJ</p>
              <p class="text-xs text-blue-100">Respondo en minutos</p>
            </div>
            <button @click="chatOpen = false" class="ml-auto text-white/80 hover:text-white">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        
        <div class="h-64 overflow-y-auto p-4 space-y-4" :class="isDark ? 'bg-[#0B1F3A]' : 'bg-gray-50'">
          <div v-for="(msg, idx) in chatMessages" :key="idx" 
            :class="['flex', msg.from === 'user' ? 'justify-end' : 'justify-start']">
            <div :class="['max-w-[80%] p-3 rounded-2xl text-sm',
              msg.from === 'user' 
                ? 'bg-[#2E7DE8] text-white rounded-br-md' 
                : isDark ? 'bg-[#1a3050] text-gray-300 rounded-bl-md' : 'bg-white text-gray-700 border rounded-bl-md']">
              {{ msg.text }}
            </div>
          </div>
        </div>
        
        <div class="p-3 border-t" :class="isDark ? 'border-gray-700 bg-[#0B1F3A]' : 'border-gray-200 bg-white'">
          <div class="flex flex-wrap gap-2">
            <button v-for="option in chatOptions" :key="option"
              @click="sendChatMessage(option)"
              class="px-3 py-1.5 text-xs rounded-full border transition-colors"
              :class="isDark ? 'border-[#2E7DE8]/30 text-[#2E7DE8] hover:bg-[#2E7DE8]/10' : 'border-blue-200 text-blue-600 hover:bg-blue-50'">
              {{ option }}
            </button>
          </div>
        </div>
      </div>
      
      <button v-if="!chatOpen" @click="chatOpen = true"
        class="w-14 h-14 rounded-full bg-gradient-to-r from-[#2E7DE8] to-[#1e6ad1] text-white shadow-lg shadow-[#2E7DE8]/40 flex items-center justify-center hover:scale-110 transition-transform">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
      </button>
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
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useDarkMode } from '../../composables/useDarkMode'
import FeatureCard from './components/FeatureCard.vue'
import StepCard from './components/StepCard.vue'
import PricingCard from './components/PricingCard.vue'
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

// NUEVO: Formulario de Demo
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
    // Simulación de envío - En producción conectarías con tu API
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    // Aquí iría la llamada real a tu API:
    // await fetch('https://tu-api.com/demo-requests', {
    //   method: 'POST',
    //   headers: { 'Content-Type': 'application/json' },
    //   body: JSON.stringify(demoForm.value)
    // })
    
    // Por ahora simulamos éxito y enviamos email
    const emailBody = encodeURIComponent(
      `Nueva solicitud de demostración StockMaster Pro\n\n` +
      `Nombre: ${demoForm.value.name}\n` +
      `Empresa: ${demoForm.value.company}\n` +
      `Email: ${demoForm.value.email}\n` +
      `Teléfono: ${demoForm.value.phone}\n` +
      `Empleados: ${demoForm.value.employees}\n` +
      `Tipo de inventario: ${demoForm.value.inventoryType}\n` +
      `Mensaje: ${demoForm.value.message || 'N/A'}\n\n` +
      `---\nEnviado desde el sitio web StockMaster Pro`
    )
    
    window.location.href = `mailto:ing.carlosurbina@gmail.com?subject=Solicitud Demo StockMaster Pro - ${demoForm.value.company}&body=${emailBody}`
    
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

// NUEVO: Modal de Artículos
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

// Trusted Clients
const trustedClients = [
  { name: 'ADIS Diseño', initials: 'AD' },
  { name: 'Caoba Muebles', initials: 'CM' },
  { name: 'DecoSonora', initials: 'DS' },
  { name: 'Muebles Río Rico', initials: 'RR' },
  { name: 'CJ Consultoría', initials: 'CJ' }
]

// Comparison Table
const comparisonTable = [
  { feature: 'Multi-almacén', stockmaster: true, excel: false, competitor: true },
  { feature: 'Reportes avanzados', stockmaster: true, excel: false, competitor: true },
  { feature: 'Alertas automáticas', stockmaster: true, excel: false, competitor: true },
  { feature: 'App móvil', stockmaster: true, excel: false, competitor: true },
  { feature: 'Fácil de usar', stockmaster: true, excel: true, competitor: false },
  { feature: 'Soporte en español', stockmaster: true, excel: false, competitor: false },
  { feature: 'Setup en < 1 día', stockmaster: true, excel: true, competitor: false },
  { feature: 'Precio mensual', stockmaster: '$299-$799', excel: '$0', competitor: '$2,000+' },
  { feature: 'Sin contratos forzosos', stockmaster: true, excel: true, competitor: false },
  { feature: 'Consultoría incluida', stockmaster: true, excel: false, competitor: false }
]

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

// Video Demo
const videoPlaying = ref(false)
const playVideo = () => {
  videoPlaying.value = true
}
const videoChapters = [
  { time: '0:00', title: 'Introducción' },
  { time: '0:45', title: 'Dashboard en vivo' },
  { time: '1:30', title: 'Gestión de productos' },
  { time: '2:15', title: 'Reportes y análisis' }
]

// Screenshots Carrusel
const activeScreenshot = ref(0)
const screenshots = [
  { 
    id: 'dashboard', 
    component: { props: ['isDark'], template: '<div class="h-full flex flex-col gap-3 p-4"><div class="grid grid-cols-4 gap-3"><div v-for="i in 4" :key="i" class="h-16 rounded-lg" :class="isDark ? `bg-[#1a3050]` : `bg-gray-100`"></div></div><div class="flex-1 rounded-lg" :class="isDark ? `bg-[#1a3050]` : `bg-gray-100`"></div></div>' },
    miniComponent: { template: '<div class="w-full h-full flex gap-1 p-1"><div class="flex-1 bg-[#2E7DE8]/30 rounded"></div><div class="flex-1 bg-[#2E7DE8]/30 rounded"></div></div>' }
  },
  { 
    id: 'products', 
    component: { props: ['isDark'], template: '<div class="h-full p-4"><div class="space-y-2"><div v-for="i in 5" :key="i" class="h-10 rounded-lg flex items-center px-3 gap-3" :class="isDark ? `bg-[#1a3050]` : `bg-gray-100`"><div class="w-8 h-8 rounded bg-[#2E7DE8]/30"></div><div class="flex-1 h-3 rounded" :class="isDark ? `bg-gray-700` : `bg-gray-300`"></div></div></div></div>' },
    miniComponent: { template: '<div class="w-full h-full flex flex-col gap-1 p-1"><div class="h-2 bg-[#2E7DE8]/30 rounded w-full"></div><div class="h-2 bg-[#2E7DE8]/30 rounded w-3/4"></div><div class="h-2 bg-[#2E7DE8]/30 rounded w-5/6"></div></div>' }
  },
  { 
    id: 'reports', 
    component: { props: ['isDark'], template: '<div class="h-full p-4"><div class="grid grid-cols-2 gap-3 h-full"><div v-for="i in 4" :key="i" class="rounded-lg flex items-center justify-center" :class="isDark ? `bg-[#1a3050]` : `bg-gray-100`"><div class="w-16 h-16 rounded-full border-4" :class="i === 1 ? `border-emerald-500` : i === 2 ? `border-blue-500` : `border-amber-500`"></div></div></div></div>' },
    miniComponent: { template: '<div class="w-full h-full flex items-center justify-center"><div class="w-8 h-8 rounded-full border-2 border-[#2E7DE8]"></div></div>' }
  },
  { 
    id: 'movements', 
    component: { props: ['isDark'], template: '<div class="h-full p-4"><div class="space-y-2"><div class="h-8 rounded flex items-center px-3 gap-2" :class="isDark ? `bg-emerald-500/20` : `bg-emerald-100`"><div class="w-2 h-2 rounded-full bg-emerald-500"></div><div class="flex-1 h-2 rounded" :class="isDark ? `bg-emerald-500/30` : `bg-emerald-300`"></div></div><div class="h-8 rounded flex items-center px-3 gap-2" :class="isDark ? `bg-rose-500/20` : `bg-rose-100`"><div class="w-2 h-2 rounded-full bg-rose-500"></div><div class="flex-1 h-2 rounded" :class="isDark ? `bg-rose-500/30` : `bg-rose-300`"></div></div><div v-for="i in 3" :key="i" class="h-8 rounded" :class="isDark ? `bg-[#1a3050]` : `bg-gray-100`"></div></div></div>' },
    miniComponent: { template: '<div class="w-full h-full flex flex-col gap-1 p-1"><div class="h-3 bg-emerald-500/30 rounded"></div><div class="h-3 bg-rose-500/30 rounded"></div></div>' }
  }
]

const nextScreenshot = () => {
  activeScreenshot.value = (activeScreenshot.value + 1) % screenshots.length
}
const prevScreenshot = () => {
  activeScreenshot.value = (activeScreenshot.value - 1 + screenshots.length) % screenshots.length
}

// Interactive Demo
const demoActiveTab = ref('Dashboard')
const demoMenu = ['Dashboard', 'Productos', 'Movimientos', 'Reportes', 'Configuración']
const demoStats = [
  { label: 'Productos', value: '156' },
  { label: 'Valor Total', value: '$45.2k' },
  { label: 'Stock Bajo', value: '8' },
  { label: 'Mov. Hoy', value: '+24' }
]
const demoBars = [45, 72, 58, 85]
const demoProducts = [
  { name: 'Laptop HP Pavilion', sku: 'LAP-HP-001', stock: 25, price: '12,499' },
  { name: 'Monitor Dell 27"', sku: 'MON-DL-027', stock: 8, price: '5,299' },
  { name: 'Teclado Mecánico', sku: 'TEC-MK-001', stock: 42, price: '1,299' },
  { name: 'Mouse Inalámbrico', sku: 'MOU-WL-001', stock: 5, price: '399' },
  { name: 'Webcam HD', sku: 'CAM-HD-001', stock: 15, price: '899' }
]

// Blog Posts - CON CONTENIDO COMPLETO
const blogPosts = [
  {
    icon: '📊',
    category: 'Gestión',
    title: 'Cómo implementar análisis ABC en tu inventario',
    excerpt: 'Descubre cómo clasificar tus productos por valoración para optimizar tu capital de trabajo.',
    date: '5 Abr 2026',
    readTime: '5 min',
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
      <p><strong>StockMaster Pro te ayuda:</strong> El sistema calcula automáticamente la clasificación ABC de tu inventario basándose en el valor de consumo de cada producto.</p>
    `
  },
  {
    icon: '🏆',
    category: 'ISO 9001',
    title: 'Preparación para auditoría de calidad: checklist 2026',
    excerpt: 'Todo lo que necesitas saber para pasar tu auditoría ISO sin contratiempos.',
    date: '2 Abr 2026',
    readTime: '8 min',
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
    excerpt: 'Si experimentas alguna de estas señales, es hora de modernizar tu gestión.',
    date: '28 Mar 2026',
    readTime: '4 min',
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
      <p><strong>La buena noticia:</strong> Implementar StockMaster Pro toma menos de un día y puedes empezar gratis.</p>
    `
  }
]

// ChatBot
const chatOpen = ref(false)
const chatMessages = ref([
  { from: 'bot', text: '¡Hola! Soy el asistente de CJ Consultoría. ¿En qué puedo ayudarte hoy?' }
])
const chatOptions = ['Ver planes', 'Agendar demo', 'Soporte', 'Hablar con humano']

const sendChatMessage = (option) => {
  chatMessages.value.push({ from: 'user', text: option })
  
  setTimeout(() => {
    let response = ''
    switch(option) {
      case 'Ver planes':
        response = 'Tenemos 3 planes: Gratis ($0), Profesional ($299/mes) e Ilimitado ($799/mes). ¿Cuál te interesa?'
        break
      case 'Agendar demo':
        response = 'Perfecto, desplázate hasta la sección "Solicitar Demo" o te puedo redirigir a WhatsApp.'
        setTimeout(() => scrollToSection('demo-form'), 1000)
        break
      case 'Soporte':
        response = 'Nuestro equipo de soporte está disponible de lunes a viernes de 9am a 6pm. ¿Cuál es tu consulta?'
        break
      case 'Hablar con humano':
        response = 'Te conectaré con uno de nuestros consultores. Abriendo WhatsApp...'
        setTimeout(() => openWhatsApp(), 1500)
        break
      default:
        response = 'Entiendo. ¿Te gustaría que te contacte un asesor?'
    }
    chatMessages.value.push({ from: 'bot', text: response })
  }, 500)
}

const openWhatsApp = () => {
  const phone = '524776940272'
  const message = encodeURIComponent('Hola Carlos, estoy interesado en StockMaster Pro. ¿Podemos agendar una llamada para conocer más detalles?')
  window.open(`https://wa.me/${phone}?text=${message}`, '_blank', 'noopener,noreferrer')
}

const sendEmail = () => {
  const email = 'ing.carlosurbina@gmail.com'
  const subject = encodeURIComponent('Consulta sobre StockMaster Pro')
  const body = encodeURIComponent('Hola Carlos,\n\nEstoy interesado en StockMaster Pro para mi empresa. Me gustaría recibir más información.\n\nSaludos,')
  window.location.href = `mailto:${email}?subject=${subject}&body=${body}`
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  document.body.style.overflow = ''
})

const features = [
  {
    icon: 'inventory',
    title: 'Control Multi-Almacén',
    description: 'Gestiona inventario en múltiples ubicaciones con transferencias entre almacenes en tiempo real.'
  },
  {
    icon: 'barcode',
    title: 'Códigos de Barras',
    description: 'Escaneo y generación de códigos de barras. Compatible con lectores y etiquetadoras DYMO.'
  },
  {
    icon: 'chart',
    title: 'Reportes Avanzados',
    description: 'Análisis ABC, rotación de inventario, valoración por métodos de costeo (FIFO, LIFO, Promedio).'
  },
  {
    icon: 'alert',
    title: 'Alertas Inteligentes',
    description: 'Notificaciones automáticas de stock bajo, productos por vencer y transferencias pendientes.'
  },
  {
    icon: 'users',
    title: 'Gestión de Usuarios',
    description: 'Roles y permisos personalizables: Administrador, Gerente, Operador y Visualizador.'
  },
  {
    icon: 'mobile',
    title: 'Acceso Multiplataforma',
    description: 'Web responsive. Accede desde cualquier dispositivo, en cualquier lugar.'
  }
]

const steps = [
  {
    icon: 'register',
    title: 'Regístrate',
    description: 'Crea tu cuenta en menos de 2 minutos. No requiere tarjeta de crédito para la prueba.'
  },
  {
    icon: 'configure',
    title: 'Configura',
    description: 'Define tus almacenes, categorías y usuarios. Importa tu inventario desde Excel.'
  },
  {
    icon: 'train',
    title: 'Capacita',
    description: 'Tu equipo aprende a usar el sistema con nuestros tutoriales y soporte personalizado.'
  },
  {
    icon: 'grow',
    title: 'Crece',
    description: 'Optimiza tus procesos, reduce pérdidas y toma decisiones basadas en datos.'
  }
]

const pricingPlans = [
  {
    name: 'Gratis',
    price: '0',
    period: 'mes',
    description: 'Para empezar sin costo',
    features: [
      '1 Almacén',
      '100 Productos',
      '1 Usuario',
      'Reportes básicos',
      'Soporte por email'
    ],
    cta: 'Registrarse Gratis',
    popular: false,
    free: true
  },
  {
    name: 'Profesional',
    price: '299',
    period: 'mes',
    description: 'Ideal para negocios en crecimiento',
    features: [
      '10 Almacenes',
      '500 Productos',
      '5 Usuarios',
      'Reportes avanzados',
      'Etiquetas DYMO',
      'Soporte prioritario',
      '🎁 1 mes GRATIS'
    ],
    cta: 'Prueba Gratis 1 Mes',
    popular: true,
    trial: true
  },
  {
    name: 'Ilimitado',
    price: '799',
    period: 'mes',
    description: 'Todo ilimitado para tu empresa',
    features: [
      'Almacenes ilimitados',
      'Productos ilimitados',
      'Usuarios ilimitados',
      'API completa',
      'Consultoría dedicada',
      'Soporte 24/7'
    ],
    cta: 'Elegir Plan',
    popular: false,
    unlimited: true
  }
]

const values = [
  { icon: '⚡', name: 'Excelencia Práctica', description: '"Lo perfecto es enemigo de lo hecho"' },
  { icon: '🤝', name: 'Cercanía', description: 'Socios estratégicos, no proveedores' },
  { icon: '🐺', name: 'Integridad', description: 'Lealtad, estrategia y trabajo en equipo' },
  { icon: '📊', name: 'Orden', description: 'El caos organizado hacia la excelencia' },
  { icon: '🌱', name: 'Evolución', description: 'Mejorar es una decisión diaria' }
]
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

@keyframes rain {
  0% { transform: translateY(-100%); }
  100% { transform: translateY(100%); }
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

.animate-rain {
  animation: rain 20s linear infinite;
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

@keyframes slide-in {
  from { opacity: 0; transform: translateX(20px); }
  to { opacity: 1; transform: translateX(0); }
}

.animate-fade-in-up {
  animation: fade-in-up 0.3s ease-out;
}

.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}

.animate-slide-in {
  animation: slide-in 0.3s ease-out;
}

/* Smooth scroll behavior */
html {
  scroll-behavior: smooth;
}

/* Custom scrollbar for dark mode */
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
  font-size: 1.25rem;
  font-weight: 600;
  margin-top: 1.5rem;
  margin-bottom: 0.75rem;
}

.prose ul {
  list-style-type: disc;
  padding-left: 1.5rem;
  margin-bottom: 1rem;
}

.prose ul li {
  margin-bottom: 0.5rem;
}

.prose p {
  margin-bottom: 1rem;
  line-height: 1.7;
}

.prose strong {
  font-weight: 600;
  color: #2E7DE8;
}

.prose-invert {
  color: #d1d5db;
}

.prose-invert h3 {
  color: white;
}
</style>
