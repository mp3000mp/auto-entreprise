<script lang="ts" setup>
import { computed, ref } from 'vue'
import Mp3000Icon from '@/components/Mp3000Icon.vue'
import type { ListTender } from '@/stores/tender/types'
import { useTenderStore } from '@/stores/tender'

const tenderStore = useTenderStore()
defineEmits(['show-form'])
const props = defineProps<{
  tender: ListTender
  isDeletable: boolean
}>()

const isRemoving = ref(false)
const confirmMessage = computed(
  () =>
    'Confirmer la suppression de ' +
    props.tender.opportunity.ref +
    ' version ' +
    props.tender.version
)

async function remove() {
  isRemoving.value = true
  await tenderStore.delete(props.tender.id)
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
        v-if="isDeletable"
        :confirm-message="confirmMessage"
        @click="remove()"
        icon="trash"
        title="Supprimer"
        :is-loading="isRemoving"
      />
      <router-link :to="{ name: 'tender', params: { id: tender.id } }">{{
        tender.version
      }}</router-link>
    </td>
    <td>
      <router-link :to="{ name: 'opportunity', params: { id: tender.opportunity.id } }">{{
        tender.opportunity.ref
      }}</router-link>
    </td>
    <td>
      <router-link :to="{ name: 'company', params: { id: tender.opportunity.company.id } }">{{
        tender.opportunity.company.name
      }}</router-link>
    </td>
    <td>{{ tender.status.label }}</td>
    <td>{{ tender.soldDays * tender.averageDailyRate }}€</td>
    <td>{{ tender.soldDays }}</td>
    <td>{{ tender.workedDays }}</td>
    <td>{{ tender.createdAt.format('YYYY-MM-DD') }}</td>
  </tr>
</template>
