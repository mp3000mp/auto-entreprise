<script lang="ts" setup>
import config from '@/misc/config'
</script>

<template>
  <footer class="container-fluid">Version: {{ config.version }}</footer>
</template>

<style lang="scss">
footer {
  font-size: 0.8rem;
  font-style: italic;
  text-align: center;
}
</style>
