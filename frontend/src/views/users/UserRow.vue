<script lang="ts" setup>
import type { User } from '@/stores/user/types'

defineEmits(['show-form'])
defineProps<{
  user: User
}>()
</script>

<template>
  <tr>
    <td>{{ user.id }}</td>
    <td>{{ user.email }}</td>
    <td>{{ user.username }}</td>
    <td>{{ user.roles.join(',') }}</td>
  </tr>
</template>
