<script lang="ts" setup>
import { computed, ref } from 'vue'
import { useTenderStore } from '@/stores/tender'

import type { TenderRow } from '@/stores/tender/types'
import Mp3000Icon from '@/components/Mp3000Icon.vue'

const tenderStore = useTenderStore()
defineEmits(['show-form'])
const props = defineProps<{
  tenderRow: TenderRow
  averageDailyRate: number
}>()

const isRemoving = ref(false)
const confirmMessage = computed(
  () => 'Confirmer la suppression de ' + props.tenderRow.description + ' ?'
)

async function remove() {
  isRemoving.value = true
  await tenderStore.deleteTenderRow(props.tenderRow.id)
  isRemoving.value = false
}
</script>

<template>
  <tr>
    <td>
      <a href="#" class="me-1" @click.prevent="$emit('show-form')" title="Editer">
        <font-awesome-icon :icon="['fa', 'pen-to-square']" />
      </a>
      <mp3000-icon
        class="me-1"
        :confirm-message="confirmMessage"
        @click="remove()"
        icon="trash"
        title="Supprimer"
        :is-loading="isRemoving"
      />
      <span>{{ tenderRow.position }}. {{ tenderRow.title }}</span>
    </td>
    <td>{{ tenderRow.description }}</td>
    <td>{{ tenderRow.soldDays }}</td>
    <td>{{ tenderRow.soldDays * averageDailyRate }}€</td>
  </tr>
</template>
