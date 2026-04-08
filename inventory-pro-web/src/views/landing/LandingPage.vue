<template>
  <div class="min-h-screen" :class="isDark ? 'bg-[#0B1F3A]' : 'bg-white'">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300" 
      :class="[scrolled ? (isDark ? 'bg-[#0B1F3A]/95 backdrop-blur-md shadow-lg' : 'bg-white/95 backdrop-blur-md shadow-lg') : 'bg-transparent']">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
          <!-- Logo -->
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-[#0B1F3A] to-[#2E7DE8] rounded-xl flex items-center justify-center shadow-lg border-2 border-[#C0C0C0]">
              <span class="text-2xl font-bold text-white">CJ</span>
            </div>
            <div>
              <span class="text-xl font-bold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">CJ</span>
              <span class="text-xl font-bold text-[#2E7DE8]">Consultoría</span>
            </div>
          </div>

          <!-- Desktop Menu -->
          <div class="hidden md:flex items-center gap-8">
            <a href="#features" class="text-sm font-medium transition-colors" :class="isDark ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-[#0B1F3A]'">Características</a>
            <a href="#how-it-works" class="text-sm font-medium transition-colors" :class="isDark ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-[#0B1F3A]'">Cómo Funciona</a>
            <a href="#pricing" class="text-sm font-medium transition-colors" :class="isDark ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-[#0B1F3A]'">Precios</a>
            <a href="#consulting" class="text-sm font-medium transition-colors" :class="isDark ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-[#0B1F3A]'">Consultoría</a>
          </div>

          <!-- CTA Buttons -->
          <div class="flex items-center gap-4">
            <button @click="toggleDarkMode" class="p-2 rounded-lg transition-colors" :class="isDark ? 'text-gray-300 hover:bg-[#0B1F3A]' : 'text-gray-600 hover:bg-gray-100'">
              <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
              </svg>
            </button>
            <router-link to="/login" class="hidden sm:block text-sm font-medium transition-colors" :class="isDark ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-[#0B1F3A]'">
              Iniciar Sesión
            </router-link>
            <router-link to="/register" class="px-5 py-2.5 bg-[#2E7DE8] hover:bg-[#1e6ad1] text-white rounded-xl font-medium transition-all shadow-lg shadow-[#2E7DE8]/25 hover:shadow-[#2E7DE8]/40">
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
        <div class="absolute top-20 left-10 w-72 h-72 bg-[#2E7DE8]/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-[#0B1F3A]/20 rounded-full blur-3xl"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div class="text-center lg:text-left">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-6 border"
              :class="isDark ? 'bg-[#2E7DE8]/10 border-[#2E7DE8]/30 text-[#2E7DE8]' : 'bg-blue-50 border-blue-200 text-[#2E7DE8]'">
              <span class="flex h-2 w-2 rounded-full bg-[#2E7DE8] animate-pulse"></span>
              <span class="text-sm font-medium">Desarrollado por CJ Consultoría</span>
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
              Inventory Pro
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
              <router-link to="/register" class="px-8 py-4 bg-[#2E7DE8] hover:bg-[#1e6ad1] text-white rounded-xl font-semibold transition-all shadow-xl shadow-[#2E7DE8]/25 hover:shadow-[#2E7DE8]/40 flex items-center justify-center gap-2">
                Comenzar Prueba Gratis
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </router-link>
              <a href="#consulting" class="px-8 py-4 border-2 rounded-xl font-semibold transition-all flex items-center justify-center gap-2 border-[#C0C0C0] text-[#0B1F3A] hover:border-[#2E7DE8] hover:text-[#2E7DE8]" :class="isDark ? 'text-white border-gray-600 hover:border-[#2E7DE8]' : ''">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                Contactar
              </a>
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

          <!-- Hero Image / Dashboard Preview -->
          <div class="relative">
            <div class="absolute -inset-4 bg-gradient-to-r from-[#2E7DE8] to-[#0B1F3A] rounded-2xl opacity-20 blur-2xl"></div>
            <div class="relative rounded-2xl overflow-hidden shadow-2xl border-2"
              :class="isDark ? 'border-[#C0C0C0]/30 bg-[#0B1F3A]' : 'border-[#C0C0C0] bg-white'">
              <div class="w-full aspect-video flex items-center justify-center p-8" :class="isDark ? 'bg-[#0B1F3A]' : 'bg-slate-50'">
                <div class="text-center">
                  <div class="w-24 h-24 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-[#0B1F3A] to-[#2E7DE8] flex items-center justify-center shadow-xl border-2 border-[#C0C0C0]">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                  </div>
                  <h3 class="text-2xl font-bold mb-2" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">Inventory Pro</h3>
                  <p class="mb-4" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Sistema ERP de Gestión de Inventario</p>
                  <div class="flex justify-center gap-4 text-sm">
                    <span class="px-3 py-1 rounded-full bg-[#2E7DE8]/10 text-[#2E7DE8] border border-[#2E7DE8]/30">Multi-almacén</span>
                    <span class="px-3 py-1 rounded-full bg-[#2E7DE8]/10 text-[#2E7DE8] border border-[#2E7DE8]/30">Reportes</span>
                    <span class="px-3 py-1 rounded-full bg-[#2E7DE8]/10 text-[#2E7DE8] border border-[#2E7DE8]/30">ISO</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 border-y" :class="isDark ? 'border-gray-800 bg-[#0B1F3A]/50' : 'border-gray-200 bg-gray-50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
          <div>
            <p class="text-3xl md:text-4xl font-bold text-[#2E7DE8]">65%</p>
            <p class="text-sm mt-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">PYMEs sin procesos documentados</p>
          </div>
          <div>
            <p class="text-3xl md:text-4xl font-bold text-[#2E7DE8]">+25%</p>
            <p class="text-sm mt-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Incremento en ventas con ISO</p>
          </div>
          <div>
            <p class="text-3xl md:text-4xl font-bold text-[#2E7DE8]">80%</p>
            <p class="text-sm mt-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Tiempo ahorrado en Excel</p>
          </div>
          <div>
            <p class="text-3xl md:text-4xl font-bold text-[#2E7DE8]">99.9%</p>
            <p class="text-sm mt-1" :class="isDark ? 'text-gray-400' : 'text-gray-600'">Uptime garantizado</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Todo lo que necesitas para gestionar tu inventario
          </h2>
          <p class="text-lg" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
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
    <section id="how-it-works" class="py-20" :class="isDark ? 'bg-[#0B1F3A]/50' : 'bg-gray-50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
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
          />
        </div>
      </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Planes y Precios
          </h2>
          <p class="text-lg" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
            Inversión accesible para PYMEs y emprendedores
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
          <p class="text-lg mb-4" :class="isDark ? 'text-gray-300' : 'text-gray-700'">
            ¿Necesitas un plan personalizado para tu empresa?
          </p>
          <a href="https://wa.me/524776940272" target="_blank" class="inline-flex items-center gap-2 px-8 py-4 border-2 border-[#2E7DE8] text-[#2E7DE8] hover:bg-[#2E7DE8] hover:text-white rounded-xl font-semibold transition-all">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Contactar por WhatsApp
          </a>
        </div>
      </div>
    </section>

    <!-- Consulting Section - CJ -->
    <section id="consulting" class="py-20" :class="isDark ? 'bg-[#0B1F3A]/50' : 'bg-gray-50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-6 border"
              :class="isDark ? 'bg-[#2E7DE8]/10 border-[#2E7DE8]/30 text-[#2E7DE8]' : 'bg-blue-100 border-blue-200 text-[#2E7DE8]'">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              <span class="text-sm font-medium">Consultoría Especializada</span>
            </div>

            <h2 class="text-3xl md:text-4xl font-bold mb-6" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
              CJ Consultoría: Tu socio estratégico en gestión empresarial
            </h2>

            <p class="text-lg mb-4" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
              <strong class="text-[#2E7DE8]">Misión:</strong> Empoderar a PYMEs y emprendedores mediante la transformación de sus procesos en resultados sostenibles, proporcionando soluciones de gestión personalizadas, prácticas y accesibles.
            </p>

            <p class="text-lg mb-6" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
              <strong class="text-[#2E7DE8]">Visión:</strong> Ser la consultoría de referencia para PYMEs y emprendedores en Latinoamérica, reconocida por nuestra metodología práctica, cercanía humana y capacidad de generar resultados sostenibles.
            </p>

            <div class="grid grid-cols-2 gap-4 mb-8">
              <div class="p-4 rounded-xl border" :class="isDark ? 'bg-[#0B1F3A] border-gray-700' : 'bg-white border-gray-200'">
                <h4 class="font-semibold mb-2 flex items-center gap-2" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">
                  <svg class="w-5 h-5 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
              <div class="p-4 rounded-xl border" :class="isDark ? 'bg-[#0B1F3A] border-gray-700' : 'bg-white border-gray-200'">
                <h4 class="font-semibold mb-2 flex items-center gap-2" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">
                  <svg class="w-5 h-5 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
              <div class="p-4 rounded-xl border" :class="isDark ? 'bg-[#0B1F3A] border-gray-700' : 'bg-white border-gray-200'">
                <h4 class="font-semibold mb-2 flex items-center gap-2" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">
                  <svg class="w-5 h-5 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
              <div class="p-4 rounded-xl border" :class="isDark ? 'bg-[#0B1F3A] border-gray-700' : 'bg-white border-gray-200'">
                <h4 class="font-semibold mb-2 flex items-center gap-2" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">
                  <svg class="w-5 h-5 text-[#2E7DE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
              <a href="https://wa.me/524776940272" target="_blank" class="px-8 py-4 bg-[#25D366] hover:bg-[#128C7E] text-white rounded-xl font-semibold transition-all shadow-lg flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                WhatsApp
              </a>
              <a href="mailto:ing.carlosurbina@gmail.com" class="px-8 py-4 bg-[#2E7DE8] hover:bg-[#1e6ad1] text-white rounded-xl font-semibold transition-all shadow-lg flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Email
              </a>
            </div>
          </div>

          <div class="relative">
            <div class="absolute -inset-4 bg-gradient-to-r from-[#2E7DE8] to-[#0B1F3A] rounded-2xl opacity-10 blur-2xl"></div>
            <div class="relative rounded-2xl p-8 border-2"
              :class="isDark ? 'bg-[#0B1F3A] border-[#C0C0C0]/30' : 'bg-white border-[#C0C0C0]'">
              
              <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#0B1F3A] to-[#2E7DE8] flex items-center justify-center border-2 border-[#C0C0C0]">
                  <span class="text-2xl font-bold text-white">CJ</span>
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
                <a href="https://wa.me/524776940272" target="_blank" class="flex items-center gap-3 p-4 rounded-xl transition-colors" :class="isDark ? 'bg-[#0B1F3A] hover:bg-[#1a3050]' : 'bg-gray-50 hover:bg-gray-100'">
                  <div class="w-10 h-10 rounded-full bg-[#25D366] flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-xs" :class="isDark ? 'text-gray-400' : 'text-gray-500'">WhatsApp</p>
                    <p class="font-medium" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">+52 477 694 0272</p>
                  </div>
                </a>

                <a href="mailto:ing.carlosurbina@gmail.com" class="flex items-center gap-3 p-4 rounded-xl transition-colors" :class="isDark ? 'bg-[#0B1F3A] hover:bg-[#1a3050]' : 'bg-gray-50 hover:bg-gray-100'">
                  <div class="w-10 h-10 rounded-full bg-[#2E7DE8] flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div>
                    <p class="text-xs" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Email</p>
                    <p class="font-medium" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">ing.carlosurbina@gmail.com</p>
                  </div>
                </a>

                <div class="flex items-center gap-3 p-4 rounded-xl" :class="isDark ? 'bg-[#0B1F3A]' : 'bg-gray-50'">
                  <div class="w-10 h-10 rounded-full bg-[#C0C0C0] flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#0B1F3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </div>
                  <div>
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
    <section class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-2xl md:text-3xl font-bold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">
            Nuestros Valores
          </h2>
        </div>
        <div class="grid md:grid-cols-5 gap-6">
          <div v-for="value in values" :key="value.name" class="text-center p-6 rounded-xl border transition-all hover:shadow-lg"
            :class="isDark ? 'bg-[#0B1F3A]/50 border-gray-700 hover:border-[#2E7DE8]/50' : 'bg-white border-gray-200 hover:border-[#2E7DE8]'">
            <div class="text-3xl mb-3">{{ value.icon }}</div>
            <h3 class="font-semibold mb-2 text-sm" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">{{ value.name }}</h3>
            <p class="text-xs" :class="isDark ? 'text-gray-400' : 'text-gray-600'">{{ value.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-3xl overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-[#0B1F3A] to-[#2E7DE8]"></div>
          <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAzNGg0djRoLTR6IiBzdHJva2U9InJnYmEoMjU1LDI1NSwyNTUsMC4xKSIvPjwvZz48L3N2Zz4=');"></div>
          
          <div class="relative p-12 md:p-16 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6" style="font-family: 'Montserrat', sans-serif;">
              ¿Listo para transformar tu gestión de inventario?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
              Únete a las PYMEs que ya confían en Inventory Pro y CJ Consultoría para optimizar sus operaciones.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <router-link to="/register" class="px-8 py-4 bg-white text-[#2E7DE8] rounded-xl font-semibold hover:bg-blue-50 transition-all shadow-lg">
                Comenzar Prueba Gratis
              </router-link>
              <a href="https://wa.me/524776940272" target="_blank" class="px-8 py-4 border-2 border-white text-white rounded-xl font-semibold hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                WhatsApp
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t" :class="isDark ? 'border-gray-800 bg-[#0B1F3A]' : 'border-gray-200 bg-gray-50'">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
          <!-- Brand -->
          <div class="col-span-2">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 bg-gradient-to-br from-[#0B1F3A] to-[#2E7DE8] rounded-xl flex items-center justify-center border border-[#C0C0C0]">
                <span class="text-lg font-bold text-white">CJ</span>
              </div>
              <span class="text-xl font-bold" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'" style="font-family: 'Montserrat', sans-serif;">Inventory Pro</span>
            </div>
            <p class="text-sm mb-4 max-w-sm" :class="isDark ? 'text-gray-400' : 'text-gray-600'">
              Sistema ERP profesional para gestión de inventario. Desarrollado por 
              <strong class="text-[#2E7DE8]">CJ Consultoría</strong> - Transformamos procesos en resultados sostenibles.
            </p>
            <div class="flex gap-4">
              <a href="https://wa.me/524776940272" target="_blank" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors bg-[#25D366] text-white hover:bg-[#128C7E]">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
              </a>
              <a href="mailto:ing.carlosurbina@gmail.com" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors" :class="isDark ? 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white' : 'bg-white text-gray-600 hover:bg-gray-100'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              </a>
            </div>
          </div>

          <!-- Links -->
          <div>
            <h4 class="font-semibold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">Producto</h4>
            <ul class="space-y-2 text-sm">
              <li><a href="#features" class="transition-colors" :class="isDark ? 'text-gray-400 hover:text-white' : 'text-gray-600 hover:text-[#0B1F3A]'">Características</a></li>
              <li><a href="#pricing" class="transition-colors" :class="isDark ? 'text-gray-400 hover:text-white' : 'text-gray-600 hover:text-[#0B1F3A]'">Precios</a></li>
              <li><a href="#" class="transition-colors" :class="isDark ? 'text-gray-400 hover:text-white' : 'text-gray-600 hover:text-[#0B1F3A]'">Integraciones</a></li>
              <li><router-link to="/login" class="transition-colors" :class="isDark ? 'text-gray-400 hover:text-white' : 'text-gray-600 hover:text-[#0B1F3A]'">Iniciar Sesión</router-link></li>
            </ul>
          </div>

          <div>
            <h4 class="font-semibold mb-4" :class="isDark ? 'text-white' : 'text-[#0B1F3A]'">CJ Consultoría</h4>
            <ul class="space-y-2 text-sm">
              <li><a href="#consulting" class="transition-colors" :class="isDark ? 'text-gray-400 hover:text-white' : 'text-gray-600 hover:text-[#0B1F3A]'">Nosotros</a></li>
              <li><a href="https://wa.me/524776940272" target="_blank" class="transition-colors" :class="isDark ? 'text-gray-400 hover:text-white' : 'text-gray-600 hover:text-[#0B1F3A]'">Contacto</a></li>
              <li><span class="text-xs" :class="isDark ? 'text-gray-500' : 'text-gray-400'">León, Gto. México</span></li>
            </ul>
          </div>
        </div>

        <div class="pt-8 border-t flex flex-col md:flex-row justify-between items-center gap-4" :class="isDark ? 'border-gray-800' : 'border-gray-200'">
          <p class="text-sm" :class="isDark ? 'text-gray-500' : 'text-gray-500'">
            © 2026 Inventory Pro & CJ Consultoría. Todos los derechos reservados.
          </p>
          <div class="flex gap-6 text-sm">
            <span class="text-xs" :class="isDark ? 'text-gray-500' : 'text-gray-500'">
              <strong class="text-[#2E7DE8]">Tel:</strong> 477 694 0272
            </span>
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
      'Soporte prioritario'
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
      'Personalización completa'
    ],
    cta: 'Contactar',
    popular: false
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
