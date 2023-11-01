import { createRouter, createWebHistory } from 'vue-router'
import AdminView from '../views/AdminView.vue'
import LoginView from '../views/LoginView.vue'

import { useAdminStore } from '@/stores/admin'
import HomeView from "@/views/HomeView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      alias: '',
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/admin',
      name: 'admin',
      component: AdminView
    }
  ]
})

router.beforeEach((to, from, next) => {
  const adminStore = useAdminStore()
  if (adminStore.currentUser === null && to.name !== 'login') {
    return next({ name: 'login' })
  }
  if (adminStore.currentUser !== null && to.name === 'login') {
    return next({ name: 'home' })
  }
  if (to.name === 'admin' && !adminStore.currentUser?.isAdmin) {
    return next({ name: 'home' })
  }
  next()
})

export default router
