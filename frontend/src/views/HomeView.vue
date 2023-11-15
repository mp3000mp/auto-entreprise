<script lang="ts" setup>
import { ref } from 'vue'

const isLoading = ref(false)
// todo
</script>

<template>
  <ul class="nav nav-tabs">
    <li class="spinner-border mt-1" v-if="isLoading">
      <span class="visually-hidden">Loading...</span>
    </li>
  </ul>
  <div>todo</div>
</template>
