import { createRouter, createWebHashHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
  // Redirección de /home a /
  {
    path: '/home',
    redirect: '/',
  },
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
  {
    path: '/',
    component: () => import('../layouts/MainLayout.vue'),
    children: [
      {
        path: '',
        name: 'Dashboard',
        component: () => import('../views/dashboard/Dashboard.vue'),
      },
      {
        path: '/products',
        name: 'Products',
        component: () => import('../views/products/ProductList.vue'),
      },
      // {
      //   path: '/products/:id',
      //   name: 'ProductDetail',
      //   component: () => import('../views/products/ProductDetail.vue'),
      // },
      {
        path: '/products/new',
        name: 'ProductNew',
        component: () => import('../views/products/ProductForm.vue'),
      },
      // {
      //   path: '/inventory/movements',
      //   name: 'StockMovements',
      //   component: () => import('../views/inventory/StockMovements.vue'),
      // },
      // {
      //   path: '/inventory/movements/new',
      //   name: 'StockMovementNew',
      //   component: () => import('../views/inventory/StockMovementForm.vue'),
      // },
      // {
      //   path: '/warehouses',
      //   name: 'Warehouses',
      //   component: () => import('../views/inventory/Warehouses.vue'),
      // },
      // {
      //   path: '/reports',
      //   name: 'Reports',
      //   component: () => import('../views/reports/Reports.vue'),
      // },
      // {
      //   path: '/settings',
      //   name: 'Settings',
      //   component: () => import('../views/settings/Settings.vue'),
      // },
    ],
  },
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
  } else if (to.meta.public && authStore.isAuthenticated) {
    next('/')
  } else {
    next()
  }
})

export default router