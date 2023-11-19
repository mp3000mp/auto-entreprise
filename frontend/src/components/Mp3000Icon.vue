<script lang="ts" setup>
withDefaults(
    defineProps<{
      disabled?: boolean
      isLoading?: boolean
      icon: string
    }>(),
    {
      disabled: false,
      isLoading: false,
    }
)
</script>

<template>
  <a href="#">
    <span v-if="isLoading" class="spinner-border spinner-border-sm" role="status"></span>
    <font-awesome-icon v-else :icon="['fa', icon]" />
  </a>
</template>
