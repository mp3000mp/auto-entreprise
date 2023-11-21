<script setup lang="ts">
withDefaults(
  defineProps<{
    hiddenText?: string
  }>(),
  {
    hiddenText: 'Loading...'
  }
)
</script>
<template>
  <div class="text-center my-5">
    <div class="spinner-border">
      <span class="visually-hidden">{{ hiddenText }}</span>
    </div>
  </div>
</template>
