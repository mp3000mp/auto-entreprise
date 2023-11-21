<script lang="ts" setup>
import type { Sorter } from '@/misc/sorter'

const props = defineProps<{
  label: string
  property: string
  sorter: Sorter<any>
}>()

function sort() {
  console.log('sort')
  console.log(props.sorter)
  if (props.sorter.isAsc(props.property) === false) {
    console.log(props.sorter)
    props.sorter.removeSort(props.property)
  } else {
    console.log(props.sorter)
    props.sorter.addSort(props.property)
  }
}
</script>

<template>
  <th @click.prevent="sort()" class="sort-header cp">
    <span>{{ label }}</span>
    <template v-if="sorter.getPriority(property) > 0">
      <font-awesome-icon
        class="ms-1"
        :icon="['fa', sorter.isAsc(property) ? 'sort-down' : 'sort-up']"
      />
      <span class="sort-priority" :class="[sorter.isAsc(property) ? 'sort-down' : 'sort-up']">
        {{ sorter.getPriority(property) }}
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
