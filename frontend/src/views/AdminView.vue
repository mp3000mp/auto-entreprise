<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import { useAdminStore } from '@/stores/admin'

const adminStore = useAdminStore()

const isLoading = ref(false)

const users = computed(() => adminStore.users)

onMounted(async () => {
  isLoading.value = true
  await adminStore.fetchUsers()
  isLoading.value = false
})
</script>

<template>
  <ul class="nav nav-tabs">
    <li class="spinner-border mt-1" v-if="isLoading">
      <span class="visually-hidden">Loading...</span>
    </li>
    <li class="nav-item" v-for="user in users" :key="user.id">
      <span>{{ user.id }}</span>
      <span>{{ user.username }}</span>
      <span>{{ user.email }}</span>
    </li>
  </ul>
</template>
