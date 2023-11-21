<script lang="ts" setup>
import BootstrapLoader from '@/components/BootstrapLoader.vue'

withDefaults(
  defineProps<{
    isLoading?: boolean
  }>(),
  {
    isLoading: false
  }
)
</script>

<template>
  <div class="row">
    <slot name="filters"></slot>
  </div>
  <div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <slot name="header"></slot>
      </thead>
      <tbody>
        <tr v-if="isLoading">
          <td colspan="100">
            <bootstrap-loader />
          </td>
        </tr>
        <slot v-else name="body"></slot>
      </tbody>
    </table>
  </div>
</template>
