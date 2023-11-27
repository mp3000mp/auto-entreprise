<script lang="ts" setup>
import { computed, ref } from 'vue'
import Mp3000Icon from '@/components/Mp3000Icon.vue'
import type { ListTender } from '@/stores/tender/types'
import { useTenderStore } from '@/stores/tender'
import type { Opportunity } from '@/stores/opportunity/types'

const tenderStore = useTenderStore()
defineEmits(['show-form'])
const props = defineProps<{
  tender: ListTender
  opportunity: Opportunity
  isDeletable: boolean
  withDetails: boolean
}>()

const isRemoving = ref(false)
const confirmMessage = computed(
  () => 'Confirmer la suppression de ' + props.opportunity.ref + ' version ' + props.tender.version
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
    <td v-if="withDetails">
      <router-link :to="{ name: 'opportunity', params: { id: opportunity.id } }">{{
        opportunity.ref
      }}</router-link>
    </td>
    <td v-if="withDetails">
      <router-link :to="{ name: 'company', params: { id: opportunity.company.id } }">{{
        opportunity.company.name
      }}</router-link>
    </td>
    <td>{{ tender.status.label }}</td>
    <td>{{ tender.soldDays }}</td>
    <td>{{ tender.soldDays * tender.averageDailyRate }}€</td>
    <td>{{ tender.workedDays }}</td>
    <td v-if="withDetails">{{ tender.createdAt.format('YYYY-MM-DD') }}</td>
  </tr>
</template>
