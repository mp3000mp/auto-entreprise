<script lang="ts" setup>
import config from '@/misc/config'
</script>

<template>
  <footer class="container-fluid">
    <font-awesome-icon icon="magic" /> Invoked from the magic kingdom by
    <a href="https://github.com/mp3000mp/auto-entreprise">
      mp3000
      <font-awesome-icon icon="hand-sparkles" />
    </a>
    <div>Version: {{ config.version }}</div>
  </footer>
</template>

<style lang="scss">
footer {
  font-size: 0.8rem;
  font-style: italic;
  text-align: center;
}
</style>
