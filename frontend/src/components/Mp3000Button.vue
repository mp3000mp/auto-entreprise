<script lang="ts" setup>
withDefaults(
  defineProps<{
    disabled?: boolean
    isLoading?: boolean
    label: string
  }>(),
  {
    disabled: false,
    isLoading: false
  }
)
</script>

<template>
  <button
    class="btn"
    type="submit"
    :class="{
      disabled: disabled || isLoading
    }"
    :disabled="disabled || isLoading"
  >
    <span v-if="isLoading" class="spinner-border spinner-border-sm" role="status"></span>
    <span v-else>{{ label }}</span>
  </button>
</template>
