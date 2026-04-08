<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-slate-900 dark:to-slate-800 p-6">
    <!-- Header with User Info -->
    <div class="max-w-7xl mx-auto mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h1 class="text-4xl font-bold text-slate-800 dark:text-white mb-2">
            Bienvenido, {{ authStore.user?.name }}
          </h1>
          <p class="text-slate-500 dark:text-slate-400">
            {{ authStore.tenant?.name }} • 
            <span :class="['px-2 py-1 rounded-full text-xs font-medium', roleBadgeClass]">
              {{ roleLabel }}
            </span>
          </p>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center gap-3">
          <!-- Dashboard Button -->
          <button @click="$router.push('/dashboard')" 
            class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 rounded-xl shadow-sm border border-slate-200 dark:border-slate-600 hover:shadow-md transition-all text-slate-700 dark:text-slate-200 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="hidden md:block">Dashboard</span>
          </button>

          <!-- Dark Mode Toggle -->
          <button @click="toggleDarkMode" 
            class="p-3 bg-white dark:bg-slate-700 rounded-xl shadow-sm border border-slate-200 dark:border-slate-600 hover:shadow-md transition-all"
            :title="isDark ? 'Modo Claro' : 'Modo Oscuro'">
            <svg v-if="isDark" class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg v-else class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
          </button>

          <!-- User Menu -->
          <div class="relative">
            <button @click="showUserMenu = !showUserMenu" 
              class="flex items-center gap-3 p-2 pr-4 bg-white dark:bg-slate-700 rounded-xl shadow-sm border border-slate-200 dark:border-slate-600 hover:shadow-md transition-all">
              <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">
                {{ userInitials }}
              </div>
              <span class="hidden md:block text-slate-700 dark:text-slate-200 font-medium">Mi Cuenta</span>
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- Dropdown -->
            <div v-if="showUserMenu" class="absolute right-0 mt-2 w-72 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-600 z-50">
              <div class="p-4 border-b border-slate-100 dark:border-slate-700">
                <p class="font-semibold text-slate-800 dark:text-white">{{ authStore.user?.name }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ authStore.user?.email }}</p>
              </div>
              
              <div class="p-4 border-b border-slate-100 dark:border-slate-700">
                <p class="text-xs font-semibold text-slate-500 uppercase mb-2">Mis Permisos</p>
                <div class="flex flex-wrap gap-1">
                  <span v-for="perm in userPermissions.slice(0, 5)" :key="perm" 
                    class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded text-xs">
                    {{ formatPermission(perm) }}
                  </span>
                  <span v-if="userPermissions.length > 5" class="px-2 py-1 text-slate-400 text-xs">
                    +{{ userPermissions.length - 5 }} más
                  </span>
                </div>
                <button v-if="!isAdmin" @click="requestPermission" 
                  class="mt-3 w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                  Solicitar más permisos
                </button>
              </div>

              <div class="p-2">
                <button @click="goToSettings" 
                  class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  Configuración
                </button>
                <button @click="handleLogout" 
                  class="w-full text-left px-3 py-2 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-900/30 text-rose-600 flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>
                  Cerrar Sesión
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="max-w-7xl mx-auto">
      <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-4">Acciones Rápidas</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <button v-if="hasPermission('products.create')" @click="$router.push('/products/new')" 
          class="group p-6 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-lg hover:border-blue-300 dark:hover:border-blue-500 transition-all text-left">
          <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <p class="font-semibold text-slate-800 dark:text-white">Nuevo Producto</p>
          <p class="text-sm text-slate-500 dark:text-slate-400">Agregar al catálogo</p>
        </button>

        <button v-if="hasPermission('movements.create')" @click="$router.push('/movements/new')" 
          class="group p-6 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-lg hover:border-emerald-300 dark:hover:border-emerald-500 transition-all text-left">
          <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
            </svg>
          </div>
          <p class="font-semibold text-slate-800 dark:text-white">Nuevo Movimiento</p>
          <p class="text-sm text-slate-500 dark:text-slate-400">Entrada o salida</p>
        </button>

        <button v-if="hasPermission('transfers.create')" @click="$router.push('/transfers/new')" 
          class="group p-6 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-lg hover:border-indigo-300 dark:hover:border-indigo-500 transition-all text-left">
          <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
          </div>
          <p class="font-semibold text-slate-800 dark:text-white">Transferencia</p>
          <p class="text-sm text-slate-500 dark:text-slate-400">Entre almacenes</p>
        </button>

        <button @click="$router.push('/products/search')" 
          class="group p-6 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-lg hover:border-amber-300 dark:hover:border-amber-500 transition-all text-left">
          <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <p class="font-semibold text-slate-800 dark:text-white">Buscar Producto</p>
          <p class="text-sm text-slate-500 dark:text-slate-400">Por código o nombre</p>
        </button>
      </div>

      <!-- Main Modules Grid -->
      <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-4">Módulos</h2>
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <!-- Dashboard -->
        <button @click="$router.push('/dashboard')" 
          class="group p-5 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-blue-300 transition-all text-left">
          <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
            <span v-if="alertCount > 0" class="px-2 py-1 bg-rose-500 text-white text-xs rounded-full">{{ alertCount }}</span>
          </div>
          <p class="font-semibold text-slate-800 dark:text-white">Dashboard</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Gráficos y alertas</p>
        </button>

        <!-- Products -->
        <button v-if="hasPermission('products.view')" @click="$router.push('/products')" 
          class="group p-5 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-emerald-300 transition-all text-left">
          <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <p class="font-semibold text-slate-800 dark:text-white">Productos</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Catálogo completo</p>
        </button>

        <!-- Warehouses -->
        <button v-if="hasPermission('warehouses.view')" @click="$router.push('/warehouses')" 
          class="group p-5 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-indigo-300 transition-all text-left">
          <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <p class="font-semibold text-slate-800 dark:text-white">Almacenes</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Gestión de ubicaciones</p>
        </button>

        <!-- General Warehouse -->
        <button @click="$router.push('/warehouse/general')" 
          class="group p-5 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-purple-300 transition-all text-left">
          <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
          </div>
          <p class="font-semibold text-slate-800 dark:text-white">Almacén General</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Vista consolidada</p>
        </button>

        <!-- Movements -->
        <button v-if="hasPermission('movements.view')" @click="$router.push('/movements')" 
          class="group p-5 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-amber-300 transition-all text-left">
          <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
            </svg>
          </div>
          <p class="font-semibold text-slate-800 dark:text-white">Movimientos</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Entradas y salidas</p>
        </button>

        <!-- Transfers -->
        <button v-if="hasPermission('transfers.view')" @click="$router.push('/transfers')" 
          class="group p-5 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-cyan-300 transition-all text-left">
          <div class="w-10 h-10 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
          </div>
          <p class="font-semibold text-slate-800 dark:text-white">Transferencias</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Entre almacenes</p>
        </button>

        <!-- Reports -->
        <button v-if="hasPermission('reports.view')" @click="$router.push('/reports')" 
          class="group p-5 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-rose-300 transition-all text-left">
          <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/30 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <p class="font-semibold text-slate-800 dark:text-white">Reportes</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Análisis y estadísticas</p>
        </button>

        <!-- Import -->
        <button v-if="hasPermission('import.execute')" @click="$router.push('/import')" 
          class="group p-5 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-lime-300 transition-all text-left">
          <div class="w-10 h-10 bg-lime-100 dark:bg-lime-900/30 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-lime-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
          </div>
          <p class="font-semibold text-slate-800 dark:text-white">Importar</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Carga masiva</p>
        </button>

        <!-- Users (Admin only) -->
        <button v-if="isAdmin" @click="$router.push('/users')" 
          class="group p-5 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-violet-300 transition-all text-left">
          <div class="w-10 h-10 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <p class="font-semibold text-slate-800 dark:text-white">Usuarios</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Gestión de equipo</p>
        </button>

        <!-- Settings -->
        <button @click="$router.push('/settings')" 
          class="group p-5 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-gray-300 transition-all text-left">
          <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>
          <p class="font-semibold text-slate-800 dark:text-white">Configuración</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Ajustes del sistema</p>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const showUserMenu = ref(false)
const alertCount = ref(0)
const isDark = ref(false)

const userInitials = computed(() => {
  const name = authStore.user?.name || ''
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

const roleLabel = computed(() => {
  const roles = {
    admin: 'Administrador',
    manager: 'Gerente',
    operator: 'Operador',
    viewer: 'Visualizador'
  }
  return roles[authStore.user?.role] || 'Usuario'
})

const roleBadgeClass = computed(() => {
  const classes = {
    admin: 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300',
    manager: 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300',
    operator: 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300',
    viewer: 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300'
  }
  return classes[authStore.user?.role] || classes.viewer
})

const isAdmin = computed(() => authStore.user?.role === 'admin')

const userPermissions = computed(() => {
  return authStore.user?.permissions || []
})

function hasPermission(permission) {
  if (isAdmin.value) return true
  return userPermissions.value.includes(permission)
}

function formatPermission(perm) {
  const map = {
    'products.view': 'Ver productos',
    'products.create': 'Crear productos',
    'products.edit': 'Editar productos',
    'movements.view': 'Ver movimientos',
    'movements.create': 'Crear movimientos',
    'transfers.view': 'Ver transferencias',
    'transfers.create': 'Crear transferencias',
    'reports.view': 'Ver reportes',
  }
  return map[perm] || perm
}

function toggleDarkMode() {
  isDark.value = !isDark.value
  document.documentElement.classList.toggle('dark', isDark.value)
  localStorage.setItem('darkMode', isDark.value)
}

function requestPermission() {
  const message = prompt('¿Qué permiso necesitas y por qué?')
  if (message) {
    alert('Solicitud enviada al administrador. Te notificaremos cuando sea aprobada.')
  }
}

function goToSettings() {
  showUserMenu.value = false
  router.push('/settings')
}

function handleLogout() {
  authStore.logout()
  router.push('/login')
}

onMounted(() => {
  // Load dark mode preference
  isDark.value = localStorage.getItem('darkMode') === 'true'
  document.documentElement.classList.toggle('dark', isDark.value)
  
  // Load alert count
  // fetchAlertCount()
})
</script>
