<script setup lang="ts">
import FooterView from './views/FooterView.vue'
import MenuView from './views/MenuView.vue'
import ErrorToast from './components/ErrorToast.vue'
</script>

<template>
  <MenuView />
  <RouterView class="container-fluid" />
  <FooterView />
  <ErrorToast />
</template>
