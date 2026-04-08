import { createRouter, createWebHashHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
  {
    path: '/',
    name: 'Landing',
    component: () => import('../views/landing/LandingPage.vue'),
    meta: { public: true },
  },
  {
    path: '/menu',
    name: 'MainMenu',
    component: () => import('../views/mainmenu/MainMenu.vue'),
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
  {
    path: '/products/:id/edit',
    name: 'ProductEdit',
    component: () => import('../views/products/ProductForm.vue'),
  },
  // Movements
  {
    path: '/movements',
    name: 'Movements',
    component: () => import('../views/movements/MovementList.vue'),
  },
  {
    path: '/movements/new',
    name: 'MovementNew',
    component: () => import('../views/movements/MovementForm.vue'),
  },
  // Warehouses
  {
    path: '/warehouses',
    name: 'Warehouses',
    component: () => import('../views/warehouses/WarehouseList.vue'),
  },
  {
    path: '/warehouse/general',
    name: 'GeneralWarehouse',
    component: () => import('../views/warehouse/GeneralWarehouse.vue'),
  },
  // Transfers
  {
    path: '/transfers',
    name: 'Transfers',
    component: () => import('../views/transfers/TransferList.vue'),
  },
  {
    path: '/transfers/new',
    name: 'TransferNew',
    component: () => import('../views/transfers/TransferForm.vue'),
  },
  {
    path: '/transfers/:id',
    name: 'TransferDetail',
    component: () => import('../views/transfers/TransferDetail.vue'),
  },
  // Product Lots
  {
    path: '/lots',
    name: 'Lots',
    component: () => import('../views/lots/LotList.vue'),
  },
  {
    path: '/lots/new',
    name: 'LotNew',
    component: () => import('../views/lots/LotForm.vue'),
  },
  // Product Search
  {
    path: '/products/search',
    name: 'ProductSearch',
    component: () => import('../views/products/ProductSearch.vue'),
  },
  // Kardex
  {
    path: '/kardex',
    name: 'Kardex',
    component: () => import('../views/inventory/Kardex.vue'),
  },
  // Categories
  {
    path: '/categories',
    name: 'Categories',
    component: () => import('../views/categories/CategoryList.vue'),
  },
  // Reports
  {
    path: '/reports',
    name: 'Reports',
    component: () => import('../views/reports/Reports.vue'),
  },
  {
    path: '/reports/abc',
    name: 'ABCReport',
    component: () => import('../views/reports/ABCReport.vue'),
  },
  // Receipts (Vales)
  {
    path: '/receipts',
    name: 'Receipts',
    component: () => import('../views/receipts/ReceiptList.vue'),
  },
  // Import
  {
    path: '/import',
    name: 'Import',
    component: () => import('../views/import/ImportProducts.vue'),
  },
  // Settings
  {
    path: '/settings',
    name: 'Settings',
    component: () => import('../views/settings/Settings.vue'),
  },
  // Suppliers
  {
    path: '/suppliers',
    name: 'Suppliers',
    component: () => import('../views/suppliers/SuppliersList.vue'),
  },
  // Users
  {
    path: '/users',
    name: 'Users',
    component: () => import('../views/users/UsersList.vue'),
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

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  if (!authStore.user && authStore.token) {
    try {
      await authStore.fetchUser()
    } catch {
      authStore.clearAuth()
    }
  }

  if (!to.meta.public && !authStore.isAuthenticated) {
    next('/login')
  } else if (to.meta.public && authStore.isAuthenticated && to.path !== '/') {
    next('/menu')
  } else {
    next()
  }
})

export default router
