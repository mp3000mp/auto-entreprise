<script lang="ts" setup>
import { useSecurityStore } from '@/stores/security'
import { useRouter } from 'vue-router'
import { computed, ref } from 'vue'

const securityStore = useSecurityStore()
const router = useRouter()

const currentUser = computed(() => securityStore.currentUser)

const links = [
  {
    label: 'Opportunités',
    to: { name: 'opportunities' }
  },
  {
    label: 'Devis',
    to: { name: 'tenders' }
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

const expandedMenu = ref(false)
function toggleMenu() {
  expandedMenu.value = !expandedMenu.value
}
</script>

<template>
  <header class="container-fluid border-bottom">
    <nav class="d-flex align-items-center justify-content-between main-nav" :class="{expanded: expandedMenu}">
      <div class="sub-main-nav d-flex align-items-center">
        <h1 class="navbar-brand">
          <router-link :to="{ name: 'home' }">MP3000 AE</router-link>
        </h1>
        <div class="main-nav-icon me-3" :class="{'mt-2': expandedMenu}" @click="toggleMenu">
          <font-awesome-icon :icon="['fa', 'bars']" />
        </div>
        <ul class="nav" v-if="null !== currentUser">
          <li class="nav-item" v-for="link in links" :key="link.label">
            <router-link class="nav-link" :to="link.to">{{ link.label }}</router-link>
          </li>
        </ul>
      </div>
      <ul class="nav justify-content-end" v-if="null !== currentUser">
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
