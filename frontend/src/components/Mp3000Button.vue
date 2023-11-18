<script lang="ts" setup>
withDefaults(
  defineProps<{
    disabled?: boolean
    isLoading?: boolean
    label: string
    outline?: boolean
  }>(),
  {
    disabled: false,
    isLoading: false,
    outline: false
  }
)
</script>

<template>
  <button
    class="btn"
    type="submit"
    :class="{
      disabled: disabled || isLoading,
      'btn-outline-primary': outline,
      'btn-primary': !outline
    }"
  >
    <span v-if="isLoading" class="spinner-border spinner-border-sm" role="status"></span>
    <span v-else>{{ label }}</span>
  </button>
</template>
