<script lang="ts" setup>
withDefaults(
  defineProps<{
    label: string
    asc?: boolean | null
    priority?: number
  }>(),
  {
    asc: null,
    priority: 0
  }
)
</script>

<template>
  <th class="sort-header cp">
    <span>{{ label }}</span>
    <template v-if="priority > 0">
      <span>
        <font-awesome-icon class="ms-1" :icon="['fa', asc ? 'sort-down' : 'sort-up']" />
      </span>
      <span class="sort-priority" :class="[asc ? 'sort-down' : 'sort-up']">
        {{ priority }}
      </span>
    </template>
  </th>
</template>

<style lang="scss">
th {
  &.sort-header {
    user-select: none;
  }

  span {
    &.sort-priority {
      font-size: 50%;
      position: relative;
      left: -7px;
    }

    &.sort-down {
      vertical-align: top;
    }

    &.sort-up {
      vertical-align: sub;
    }
  }
}
</style>
