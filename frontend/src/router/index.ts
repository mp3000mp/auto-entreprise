import { createRouter, createWebHistory } from 'vue-router'
import UsersView from '../views/users/UsersView.vue'
import LoginView from '../views/security/LoginView.vue'

import apiClient from '@/misc/api-client'

import { useSecurityStore } from '@/stores/security'
import OpportunitiesView from '@/views/opportunities/OpportunitiesView.vue'
import TendersView from '@/views/tenders/TendersView.vue'
import ContactsView from '@/views/contacts/ContactsView.vue'
import CompaniesView from '@/views/companies/CompaniesView.vue'
import CompanyView from '@/views/companies/CompanyView.vue'
import ContactView from '@/views/contacts/ContactView.vue'
import ReportingView from '@/views/reporting/ReportingView.vue'
import AccountView from '@/views/security/AccountView.vue'
import CostsView from '@/views/costs/CostsView.vue'
import OpportunityView from '@/views/opportunities/OpportunityView.vue'
import TenderView from '@/views/tenders/TenderView.vue'

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
      // component: HomeView
      component: OpportunitiesView
    },
    {
      path: '/opportunities',
      name: 'opportunities',
      component: OpportunitiesView
    },
    {
      path: '/opportunities/:id',
      name: 'opportunity',
      component: OpportunityView,
      props: (route) => ({ opportunityId: Number(route.params.id) })
    },
    {
      path: '/tenders',
      name: 'tenders',
      component: TendersView
    },
    {
      path: '/tenders/:id',
      name: 'tender',
      component: TenderView,
      props: (route) => ({ tenderId: Number(route.params.id) })
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
      props: (route) => ({ contactId: Number(route.params.id) })
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
      props: (route) => ({ companyId: Number(route.params.id) })
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
    await securityStore.checkIsLoggedIn()
  }

  if (securityStore.currentUser === null && to.name !== 'login') {
    return next({ name: 'login', query: { redirect: to.href } })
  }
  if (securityStore.currentUser !== null && to.name === 'login') {
    return next({ name: 'home' })
  }
  next()
})

apiClient.setOnUnauthorizedCallback((response) => {
  if (response.message === 'twoFactorAuthRequired') {
    const securityStore = useSecurityStore()
    securityStore.twoFactorAuthRequired = true
  }
  router.push({ name: 'login', query: { redirect: router.currentRoute.value.path } })
})

export default router
