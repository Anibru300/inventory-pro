<template>
  <div class="min-h-screen" :class="isDark ? 'bg-slate-900' : 'bg-white'">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300" 
      :class="[scrolled ? (isDark ? 'bg-slate-900/95 backdrop-blur-md shadow-lg' : 'bg-white/95 backdrop-blur-md shadow-lg') : 'bg-transparent']">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
          <!-- Logo -->
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
            <div>
              <span class="text-xl font-bold" :class="isDark ? 'text-white' : 'text-slate-900'">Inventory</span>
              <span class="text-xl font-bold text-blue-600">Pro</span>
            </div>
          </div>

          <!-- Desktop Menu -->
          <div class="hidden md:flex items-center gap-8">
            <a href="#features" class="text-sm font-medium transition-colors" :class="isDark ? 'text-slate-300 hover:text-white' : 'text-slate-600 hover:text-slate-900'">Características</a>
            <a href="#how-it-works" class="text-sm font-medium transition-colors" :class="isDark ? 'text-slate-300 hover:text-white' : 'text-slate-600 hover:text-slate-900'">Cómo Funciona</a>
            <a href="#pricing" class="text-sm font-medium transition-colors" :class="isDark ? 'text-slate-300 hover:text-white' : 'text-slate-600 hover:text-slate-900'">Precios</a>
            <a href="#consulting" class="text-sm font-medium transition-colors" :class="isDark ? 'text-slate-300 hover:text-white' : 'text-slate-600 hover:text-slate-900'">Consultoría</a>
          </div>

          <!-- CTA Buttons -->
          <div class="flex items-center gap-4">
            <button @click="toggleDarkMode" class="p-2 rounded-lg transition-colors" :class="isDark ? 'text-slate-300 hover:bg-slate-800' : 'text-slate-600 hover:bg-slate-100'">
              <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
              </svg>
            </button>
            <router-link to="/login" class="hidden sm:block text-sm font-medium transition-colors" :class="isDark ? 'text-slate-300 hover:text-white' : 'text-slate-600 hover:text-slate-900'">
              Iniciar Sesión
            </router-link>
            <router-link to="/register" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40">
              Prueba Gratis
            </router-link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
      <!-- Background Elements -->
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div class="text-center lg:text-left">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-6 border"
              :class="isDark ? 'bg-blue-500/10 border-blue-500/20 text-blue-400' : 'bg-blue-50 border-blue-200 text-blue-700'">
              <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
              <span class="text-sm font-medium">Nuevo: Módulo de Transferencias entre Almacenes</span>
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6" :class="isDark ? 'text-white' : 'text-slate-900'">
              Controla tu inventario con
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">precisión militar</span>
            </h1>
            
            <p class="text-lg md:text-xl mb-8 max-w-2xl mx-auto lg:mx-0" :class="isDark ? 'text-slate-400' : 'text-slate-600'">
              Sistema ERP profesional para gestión de inventario multi-almacén. 
              Desde $299/mes. Ideal para pymes, distribuidoras y manufactureras.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
              <router-link to="/register" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all shadow-xl shadow-blue-600/25 hover:shadow-blue-600/40 flex items-center justify-center gap-2">
                Comenzar Prueba Gratis
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </router-link>
              <a href="#demo" class="px-8 py-4 border-2 rounded-xl font-semibold transition-all flex items-center justify-center gap-2"
                :class="isDark ? 'border-slate-700 text-slate-300 hover:border-slate-600 hover:bg-slate-800' : 'border-slate-200 text-slate-700 hover:border-slate-300 hover:bg-slate-50'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Ver Demo
              </a>
            </div>

            <!-- Trust Badges -->
            <div class="mt-10 flex flex-wrap items-center justify-center lg:justify-start gap-6">
              <div class="flex items-center gap-2" :class="isDark ? 'text-slate-400' : 'text-slate-500'">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm">14 días gratis</span>
              </div>
              <div class="flex items-center gap-2" :class="isDark ? 'text-slate-400' : 'text-slate-500'">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm">Sin tarjeta de crédito</span>
              </div>
              <div class="flex items-center gap-2" :class="isDark ? 'text-slate-400' : 'text-slate-500'">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm">Soporte 24/7</span>
              </div>
            </div>
          </div>

          <!-- Hero Image / Dashboard Preview -->
          <div class="relative">
            <div class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl opacity-20 blur-2xl"></div>
            <div class="relative rounded-2xl overflow-hidden shadow-2xl border"
              :class="isDark ? 'border-slate-700 bg-slate-800' : 'border-slate-200 bg-white'">
              <img src="/images/dashboard-preview.jpg" alt="Inventory Pro Dashboard" class="w-full" 
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'" />
              <div class="hidden w-full aspect-video items-center justify-center" :class="isDark ? 'bg-slate-800' : 'bg-slate-100'">
                <div class="text-center p-8">
                  <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                  </div>
                  <p class="text-lg font-semibold" :class="isDark ? 'text-white' : 'text-slate-800'">Dashboard Interactivo</p>
                  <p class="text-sm mt-1" :class="isDark ? 'text-slate-400' : 'text-slate-500'">Visualiza tu inventario en tiempo real</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 border-y" :class="isDark ? 'border-slate-800 bg-slate-900/50' : 'border-slate-200 bg-slate-50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
          <div>
            <p class="text-3xl md:text-4xl font-bold text-blue-600">500+</p>
            <p class="text-sm mt-1" :class="isDark ? 'text-slate-400' : 'text-slate-600'">Empresas Activas</p>
          </div>
          <div>
            <p class="text-3xl md:text-4xl font-bold text-blue-600">2M+</p>
            <p class="text-sm mt-1" :class="isDark ? 'text-slate-400' : 'text-slate-600'">Productos Gestionados</p>
          </div>
          <div>
            <p class="text-3xl md:text-4xl font-bold text-blue-600">99.9%</p>
            <p class="text-sm mt-1" :class="isDark ? 'text-slate-400' : 'text-slate-600'">Uptime Garantizado</p>
          </div>
          <div>
            <p class="text-3xl md:text-4xl font-bold text-blue-600">24/7</p>
            <p class="text-sm mt-1" :class="isDark ? 'text-slate-400' : 'text-slate-600'">Soporte Técnico</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-slate-900'">
            Todo lo que necesitas para gestionar tu inventario
          </h2>
          <p class="text-lg" :class="isDark ? 'text-slate-400' : 'text-slate-600'">
            Desde el control básico hasta análisis avanzados, Inventory Pro tiene las herramientas que tu negocio necesita.
          </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <FeatureCard 
            v-for="feature in features" 
            :key="feature.title"
            v-bind="feature"
          />
        </div>
      </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-20" :class="isDark ? 'bg-slate-900/50' : 'bg-slate-50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-slate-900'">
            ¿Cómo funciona?
          </h2>
          <p class="text-lg" :class="isDark ? 'text-slate-400' : 'text-slate-600'">
            Comienza a gestionar tu inventario profesionalmente en 4 simples pasos
          </p>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
          <StepCard 
            v-for="(step, index) in steps" 
            :key="step.title"
            :step="index + 1"
            v-bind="step"
          />
        </div>
      </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-slate-900'">
            Planes y Precios
          </h2>
          <p class="text-lg" :class="isDark ? 'text-slate-400' : 'text-slate-600'">
            Elige el plan que mejor se adapte a las necesidades de tu negocio
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
          <PricingCard 
            v-for="plan in pricingPlans" 
            :key="plan.name"
            v-bind="plan"
            :is-dark="isDark"
          />
        </div>

        <!-- Enterprise CTA -->
        <div class="mt-16 text-center">
          <p class="text-lg mb-4" :class="isDark ? 'text-slate-300' : 'text-slate-700'">
            ¿Necesitas un plan personalizado para tu empresa?
          </p>
          <a href="#contact" class="inline-flex items-center gap-2 px-8 py-4 border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl font-semibold transition-all">
            Contactar Ventas
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </a>
        </div>
      </div>
    </section>

    <!-- Consulting Section - CJ -->
    <section id="consulting" class="py-20" :class="isDark ? 'bg-slate-900/50' : 'bg-slate-50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-6"
              :class="isDark ? 'bg-blue-500/10 text-blue-400' : 'bg-blue-100 text-blue-700'">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              <span class="text-sm font-medium">Consultoría Especializada</span>
            </div>

            <h2 class="text-3xl md:text-4xl font-bold mb-6" :class="isDark ? 'text-white' : 'text-slate-900'">
              Consultoría CJ: Tu aliado en transformación digital
            </h2>

            <p class="text-lg mb-6" :class="isDark ? 'text-slate-300' : 'text-slate-600'">
              Más que un software, te acompañamos en todo el proceso de implementación y optimización 
              de tus procesos de inventario. Nuestro equipo de consultores expertos te ayudará a 
              maximizar el retorno de inversión.
            </p>

            <div class="space-y-4 mb-8">
              <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-slate-900'">Implementación Personalizada</h4>
                  <p class="text-sm" :class="isDark ? 'text-slate-400' : 'text-slate-600'">Adaptamos el sistema a tus procesos específicos de negocio</p>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-slate-900'">Capacitación Presencial y Virtual</h4>
                  <p class="text-sm" :class="isDark ? 'text-slate-400' : 'text-slate-600'">Tus equipos preparados para aprovechar al máximo la herramienta</p>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-slate-900'">Soporte Prioritario 24/7</h4>
                  <p class="text-sm" :class="isDark ? 'text-slate-400' : 'text-slate-600'">Asistencia técnica cuando la necesites, sin importar la hora</p>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-semibold" :class="isDark ? 'text-white' : 'text-slate-900'">Migración de Datos</h4>
                  <p class="text-sm" :class="isDark ? 'text-slate-400' : 'text-slate-600'">Transición segura desde Excel u otros sistemas</p>
                </div>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
              <a href="#contact" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all shadow-lg shadow-blue-600/25 text-center">
                Agendar Consultoría
              </a>
              <a href="tel:+521234567890" class="px-8 py-4 border-2 rounded-xl font-semibold transition-all text-center flex items-center justify-center gap-2"
                :class="isDark ? 'border-slate-600 text-slate-300 hover:border-slate-500' : 'border-slate-300 text-slate-700 hover:border-slate-400'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                Llamar Ahora
              </a>
            </div>
          </div>

          <div class="relative">
            <div class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl opacity-10 blur-2xl"></div>
            <div class="relative rounded-2xl p-8 border"
              :class="isDark ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'">
              
              <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center">
                  <span class="text-2xl font-bold text-white">CJ</span>
                </div>
                <div>
                  <h3 class="text-xl font-bold" :class="isDark ? 'text-white' : 'text-slate-900'">Consultoría CJ</h3>
                  <p class="text-sm" :class="isDark ? 'text-slate-400' : 'text-slate-500'">Transformación Digital</p>
                </div>
              </div>

              <div class="space-y-4">
                <div class="flex items-center gap-3 p-4 rounded-xl" :class="isDark ? 'bg-slate-700/50' : 'bg-slate-50'">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <span :class="isDark ? 'text-slate-300' : 'text-slate-700'">Ciudad de México, México</span>
                </div>
                <div class="flex items-center gap-3 p-4 rounded-xl" :class="isDark ? 'bg-slate-700/50' : 'bg-slate-50'">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  <span :class="isDark ? 'text-slate-300' : 'text-slate-700'">contacto@cjconsultoria.com</span>
                </div>
                <div class="flex items-center gap-3 p-4 rounded-xl" :class="isDark ? 'bg-slate-700/50' : 'bg-slate-50'">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  <span :class="isDark ? 'text-slate-300' : 'text-slate-700'">+52 (55) 1234-5678</span>
                </div>
              </div>

              <div class="mt-6 pt-6 border-t" :class="isDark ? 'border-slate-700' : 'border-slate-200'">
                <p class="text-sm text-center" :class="isDark ? 'text-slate-400' : 'text-slate-500'">
                  Certificados en gestión de inventarios y transformación digital
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-3xl overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-700"></div>
          <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAzNGg0djRoLTR6IiBzdHJva2U9InJnYmEoMjU1LDI1NSwyNTUsMC4xKSIvPjwvZz48L3N2Zz4=')] opacity-30"></div>
          
          <div class="relative p-12 md:p-16 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
              ¿Listo para transformar tu gestión de inventario?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
              Únete a más de 500 empresas que ya confían en Inventory Pro para optimizar sus operaciones.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <router-link to="/register" class="px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all shadow-lg">
                Comenzar Prueba Gratis
              </router-link>
              <a href="#contact" class="px-8 py-4 border-2 border-white text-white rounded-xl font-semibold hover:bg-white/10 transition-all">
                Hablar con Ventas
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t" :class="isDark ? 'border-slate-800 bg-slate-950' : 'border-slate-200 bg-slate-50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
          <!-- Brand -->
          <div class="col-span-2">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
              </div>
              <span class="text-xl font-bold" :class="isDark ? 'text-white' : 'text-slate-900'">Inventory Pro</span>
            </div>
            <p class="text-sm mb-4 max-w-sm" :class="isDark ? 'text-slate-400' : 'text-slate-600'">
              Sistema ERP profesional para gestión de inventario. Desarrollado por Consultoría CJ.
            </p>
            <div class="flex gap-4">
              <a href="#" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors" :class="isDark ? 'bg-slate-800 text-slate-400 hover:bg-slate-700 hover:text-white' : 'bg-white text-slate-600 hover:bg-slate-100'">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
              </a>
              <a href="#" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors" :class="isDark ? 'bg-slate-800 text-slate-400 hover:bg-slate-700 hover:text-white' : 'bg-white text-slate-600 hover:bg-slate-100'">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
              </a>
              <a href="#" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors" :class="isDark ? 'bg-slate-800 text-slate-400 hover:bg-slate-700 hover:text-white' : 'bg-white text-slate-600 hover:bg-slate-100'">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
              </a>
            </div>
          </div>

          <!-- Links -->
          <div>
            <h4 class="font-semibold mb-4" :class="isDark ? 'text-white' : 'text-slate-900'">Producto</h4>
            <ul class="space-y-2 text-sm">
              <li><a href="#features" class="transition-colors" :class="isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900'">Características</a></li>
              <li><a href="#pricing" class="transition-colors" :class="isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900'">Precios</a></li>
              <li><a href="#" class="transition-colors" :class="isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900'">Integraciones</a></li>
              <li><a href="#" class="transition-colors" :class="isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900'">API</a></li>
            </ul>
          </div>

          <div>
            <h4 class="font-semibold mb-4" :class="isDark ? 'text-white' : 'text-slate-900'">Soporte</h4>
            <ul class="space-y-2 text-sm">
              <li><a href="#" class="transition-colors" :class="isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900'">Centro de Ayuda</a></li>
              <li><a href="#" class="transition-colors" :class="isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900'">Documentación</a></li>
              <li><a href="#" class="transition-colors" :class="isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900'">Tutoriales</a></li>
              <li><a href="#consulting" class="transition-colors" :class="isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900'">Consultoría</a></li>
            </ul>
          </div>
        </div>

        <div class="pt-8 border-t flex flex-col md:flex-row justify-between items-center gap-4" :class="isDark ? 'border-slate-800' : 'border-slate-200'">
          <p class="text-sm" :class="isDark ? 'text-slate-500' : 'text-slate-500'">
            © 2026 Inventory Pro. Todos los derechos reservados. Desarrollado por Consultoría CJ.
          </p>
          <div class="flex gap-6 text-sm">
            <a href="#" class="transition-colors" :class="isDark ? 'text-slate-500 hover:text-slate-300' : 'text-slate-500 hover:text-slate-700'">Privacidad</a>
            <a href="#" class="transition-colors" :class="isDark ? 'text-slate-500 hover:text-slate-300' : 'text-slate-500 hover:text-slate-700'">Términos</a>
            <a href="#" class="transition-colors" :class="isDark ? 'text-slate-500 hover:text-slate-300' : 'text-slate-500 hover:text-slate-700'">Cookies</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useDarkMode } from '../../composables/useDarkMode'
import FeatureCard from './components/FeatureCard.vue'
import StepCard from './components/StepCard.vue'
import PricingCard from './components/PricingCard.vue'

const { isDark, toggleDarkMode } = useDarkMode()
const scrolled = ref(false)

const handleScroll = () => {
  scrolled.value = window.scrollY > 50
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
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
    description: 'Web responsive y aplicación móvil. Accede desde cualquier dispositivo, en cualquier lugar.'
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
    name: 'Starter',
    price: '299',
    period: 'mes',
    description: 'Perfecto para pequeños negocios',
    features: [
      '1 Almacén',
      '500 Productos',
      '2 Usuarios',
      'Reportes básicos',
      'Soporte por email'
    ],
    cta: 'Comenzar',
    popular: false
  },
  {
    name: 'Professional',
    price: '799',
    period: 'mes',
    description: 'Ideal para empresas en crecimiento',
    features: [
      '5 Almacenes',
      'Productos ilimitados',
      '10 Usuarios',
      'Reportes avanzados',
      'Etiquetas DYMO',
      'Soporte prioritario 24/7'
    ],
    cta: 'Prueba Gratis',
    popular: true
  },
  {
    name: 'Enterprise',
    price: '2,499',
    period: 'mes',
    description: 'Para grandes organizaciones',
    features: [
      'Almacenes ilimitados',
      'Productos ilimitados',
      'Usuarios ilimitados',
      'API completa',
      'Consultoría dedicada',
      'SLA garantizado',
      'Personalización completa'
    ],
    cta: 'Contactar Ventas',
    popular: false
  }
]
</script>
