import { createRouter, createWebHistory } from 'vue-router'
import UsersView from '../views/users/UsersView.vue'
import LoginView from '../views/security/LoginView.vue'

import { useSecurityStore } from '@/stores/security'
import HomeView from '@/views/HomeView.vue'
import OpportunitiesView from '@/views/opportunities/OpportunitiesView.vue'
import TendersView from '@/views/tenders/TendersView.vue'
import ContactsView from '@/views/contacts/ContactsView.vue'
import CompaniesView from '@/views/companies/CompaniesView.vue'
import CompanyView from '@/views/companies/CompanyView.vue'
import ContactView from '@/views/contacts/ContactView.vue'
import ReportingView from '@/views/reporting/ReportingView.vue'
import AccountView from '@/views/security/AccountView.vue'
import CostsView from '@/views/costs/CostsView.vue'

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
      path: '/contacts/:id',
      name: 'contact',
      component: ContactView,
      props: route => ({ contactId: Number(route.params.id) })
    },
    {
      path: '/companies',
      name: 'companies',
      component: CompaniesView
    },
    {
      path: '/companies/:id',
      name: 'company',
      component: CompanyView,
      props: route => ({ companyId: Number(route.params.id) })
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
    }
  ]
})

router.beforeEach(async (to, from, next) => {
  const securityStore = useSecurityStore()

  if (!securityStore.loggedInChecked) {
    await securityStore.checkisLoggedIn()
  }

  if (securityStore.currentUser === null && to.name !== 'login') {
    return next({ name: 'login' })
  }
  if (securityStore.currentUser !== null && to.name === 'login') {
    return next({ name: 'home' })
  }
  next()
})

export default router
