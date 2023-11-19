<script lang="ts" setup>
withDefaults(
    defineProps<{
      isLoading?: boolean
    }>(),
    {
      isLoading: false,
    }
)
</script>

<template>
  <div class="text-center my-5" v-if="isLoading">
    <div class="spinner-border">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
  <div class="table-responsive" v-else>
    <div class="row">
      <slot name="filters"></slot>
    </div>
    <table class="table table-hover">
      <thead>
      <slot name="header"></slot>
      </thead>
      <tbody>
      <slot name="body"></slot>
      </tbody>
    </table>
  </div>
</template>
