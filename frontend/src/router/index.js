import { createRouter, createWebHistory } from 'vue-router'
import DashboardView from '../views/dashboardView.vue'
import ProductsView from '../views/productsView.vue'
import ProductionView from '../views/productionView.vue'
import SalesView from '../views/salesView.vue'
import LoginView from '../views/loginView.vue' 

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: DashboardView,
    },
    {
      path: '/products',
      name: 'products',
      component: ProductsView,
    },
    {
      path: '/production',
      name: 'production',
      component: ProductionView,
    },
    {
      path: '/sales',
      name: 'sales',
      component: SalesView,
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
    }
  ],
})

export default router
