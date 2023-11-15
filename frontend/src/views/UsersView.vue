<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import { useUserStore } from '@/stores/user'

const userStore = useUserStore()

const isLoading = ref(false)

const users = computed(() => userStore.users)

onMounted(async () => {
  isLoading.value = true
  await userStore.fetchUsers()
  isLoading.value = false
})
</script>

<template>
  <div>
    <div class="text-center my-5" v-if="isLoading">
      <div class="spinner-border">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <div class="table-responsive" v-else>
      <table class="table table-hover">
        <thead>
        <tr>
          <th>Id</th>
          <th>Email</th>
          <th>Username</th>
          <th>Roles</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.username }}</td>
          <td>{{ user.roles.join(',') }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
