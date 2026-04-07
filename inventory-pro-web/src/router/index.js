import { createRouter, createWebHashHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
  // Redirección específica para /home
  {
    path: '/home',
    redirect: '/',
  },
  {
    path: '/home/:pathMatch(.*)*',
    redirect: (to) => {
      return { path: '/' + to.params.pathMatch[0], hash: to.hash }
    },
  },
  
  // Ruta raíz - redirige según autenticación
  {
    path: '/',
    redirect: () => {
      const token = localStorage.getItem('token')
      return token ? '/dashboard' : '/login'
    },
  },
  
  // Auth routes
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/auth/Login.vue'),
    meta: { public: true },
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/auth/Register.vue'),
    meta: { public: true },
  },
  
  // Dashboard (main)
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('../views/dashboard/Dashboard.vue'),
  },
  
  // Products
  {
    path: '/products',
    name: 'Products',
    component: () => import('../views/products/ProductList.vue'),
  },
  {
    path: '/products/new',
    name: 'ProductNew',
    component: () => import('../views/products/ProductForm.vue'),
  },
  
  // 404
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('../views/NotFound.vue'),
  },
]

const router = createRouter({
  history: createWebHashHistory(),
  routes,
})

// Navigation guard
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  // Initialize auth if token exists
  if (!authStore.user && authStore.token) {
    try {
      await authStore.fetchUser()
    } catch {
      authStore.clearAuth()
    }
  }

  // Check authentication
  if (!to.meta.public && !authStore.isAuthenticated) {
    next('/login')
  } else if (to.meta.public && authStore.isAuthenticated && to.path !== '/') {
    next('/dashboard')
  } else {
    next()
  }
})

export default router
