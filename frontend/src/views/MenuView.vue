<script lang="ts" setup>
import { useSecurityStore } from '@/stores/security'
import { useRouter } from 'vue-router'

const securityStore = useSecurityStore()
const router = useRouter()

const links = [
  {
    label: 'Opportunités',
    to: { name: 'opportunities' }
  },
  {
    label: 'Devis',
    to: { name: 'opportunities' }
  },
  {
    label: 'Contacts',
    to: { name: 'contacts' }
  },
  {
    label: 'Clients',
    to: { name: 'companies' }
  },
  {
    label: 'Coûts',
    to: { name: 'costs' }
  },
  {
    label: 'Reporting',
    to: { name: 'reporting' }
  }
]
const adminLinks = [
  {
    label: 'Utilisateurs',
    to: { name: 'users' }
  },
  {
    label: 'Mon compte',
    to: { name: 'account' }
  }
]

async function logout() {
  await securityStore.logout()
  router.push({ name: 'login' })
}
</script>

<template>
  <header class="container-fluid border-bottom">
    <nav class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <h1 class="navbar-brand">
          <router-link :to="{ name: 'home' }">MP3000 AE</router-link>
        </h1>
        <ul class="nav">
          <li class="nav-item" v-for="link in links" :key="link.label">
            <router-link class="nav-link" :to="link.to">{{ link.label }}</router-link>
          </li>
        </ul>
      </div>
      <ul class="nav justify-content-end">
        <li class="nav-item" v-for="link in adminLinks" :key="link.label">
          <router-link class="nav-link" :to="link.to">{{ link.label }}</router-link>
        </li>
        <li class="nav-item">
          <a href="#" @click.prevent="logout" class="nav-link">Logout</a>
        </li>
      </ul>
    </nav>
  </header>
</template>
