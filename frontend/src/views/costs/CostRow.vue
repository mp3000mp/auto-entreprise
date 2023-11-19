<script lang="ts" setup>
import { ref } from 'vue'
import { useCostStore } from '@/stores/cost'

import type { Cost } from '@/stores/cost/types'
import Mp3000Icon from "@/components/Mp3000Icon.vue";

const costStore = useCostStore()
defineEmits(['show-form'])
const props = defineProps<{
  cost: Cost;
}>()

const isRemoving = ref(false)

async function remove() {
  isRemoving.value = true
  await costStore.deleteCost(props.cost.id)
  isRemoving.value = false
}
</script>

<template>
  <tr>
    <td>
      <a href="#" @click.prevent="$emit('show-form')" title="Editer">
        <font-awesome-icon :icon="['fa', 'pen-to-square']" />
      </a>
      <mp3000-icon @click.prevent="remove()" icon="trash" title="Supprimer" :is-loading="isRemoving" />
      {{ cost.type.label }}
    </td>
    <td>{{ cost.date.format('YYYY-MM-DD') }}</td>
    <td>{{ cost.amount.toFixed(2) }}</td>
    <td>{{ cost.description }}</td>
  </tr>
</template>
