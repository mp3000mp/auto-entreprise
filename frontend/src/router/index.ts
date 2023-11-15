import { createRouter, createWebHistory } from 'vue-router'
import UsersView from '../views/UsersView.vue'
import LoginView from '../views/LoginView.vue'

import { useSecurityStore } from '@/stores/security'
import HomeView from '@/views/HomeView.vue'
import OpportunitiesView from "@/views/OpportunitiesView.vue";
import TendersView from "@/views/TendersView.vue";
import ContactsView from "@/views/ContactsView.vue";
import CompaniesView from "@/views/CompaniesView.vue";
import ReportingView from "@/views/ReportingView.vue";
import AccountView from "@/views/AccountView.vue";
import CostsView from "@/views/CostsView.vue";

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
      path: '/opportunities',
      name: 'opportunities',
      component: OpportunitiesView
    },
    {
      path: '/tenders',
      name: 'tenders',
      component: TendersView
    },
    {
      path: '/contacts',
      name: 'contacts',
      component: ContactsView
    },
    {
      path: '/companies',
      name: 'companies',
      component: CompaniesView
    },
    {
      path: '/costs',
      name: 'costs',
      component: CostsView
    },
    {
      path: '/reporting',
      name: 'reporting',
      component: ReportingView
    },
    {
      path: '/account',
      name: 'account',
      component: AccountView
    },
    {
      path: '/users',
      name: 'users',
      component: UsersView
    },
  ]
})

router.beforeEach((to, from, next) => {
  const adminStore = useSecurityStore()
  if (adminStore.currentUser === null && to.name !== 'login') {
    return next({ name: 'login' })
  }
  if (adminStore.currentUser !== null && to.name === 'login') {
    return next({ name: 'home' })
  }
  next()
})

export default router
