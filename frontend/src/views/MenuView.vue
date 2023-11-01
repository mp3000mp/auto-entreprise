<script lang="ts" setup>
import { computed } from 'vue'
import { useAdminStore } from '@/stores/admin'

const adminStore = useAdminStore()

const currentUser = computed(() => adminStore.currentUser)

// todo login/logout
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
      </ul>
    </nav>
  </header>
</template>
