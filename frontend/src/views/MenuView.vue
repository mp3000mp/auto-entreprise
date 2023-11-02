<script lang="ts" setup>
import { computed } from 'vue'
import { useAdminStore } from '@/stores/admin'
import { useRouter } from 'vue-router'

const adminStore = useAdminStore()
const router = useRouter()

const currentUser = computed(() => adminStore.currentUser)

async function logout() {
  await adminStore.logout()
  router.push({ name: 'login' })
}
</script>

<template>
  <header class="container-fluid border-bottom">
    <nav class="d-flex align-items-center justify-content-between">
      <h1 class="navbar-brand">
        <router-link :to="{ name: 'home' }">MP3000 AE</router-link>
      </h1>
      <ul class="nav justify-content-end">
        <li class="nav-item" v-if="currentUser">
          <router-link class="nav-link" :to="{ name: 'admin' }">Admin</router-link>
        </li>
        <li class="nav-item" v-if="currentUser">
          <a href="#" @click.prevent="logout" class="nav-link">Logout</a>
        </li>
      </ul>
    </nav>
  </header>
</template>
