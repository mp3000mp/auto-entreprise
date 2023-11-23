<script lang="ts" setup>
import { computed, ref } from 'vue'
import Mp3000Icon from '@/components/Mp3000Icon.vue'
import { useOpportunityStore } from '@/stores/opportunity'
import type { ListOpportunity } from '@/stores/opportunity/types'

const opportunityStore = useOpportunityStore()
defineEmits(['show-form'])
const props = defineProps<{
  opportunity: ListOpportunity
  isDeletable: boolean
}>()

const isRemoving = ref(false)
const confirmMessage = computed(() => 'Confirmer la suppression de ' + props.opportunity.ref)

async function remove() {
  isRemoving.value = true
  await opportunityStore.delete(props.opportunity.id)
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
      <router-link :to="{ name: 'opportunity', params: { id: opportunity.id } }">{{
        opportunity.ref
      }}</router-link>
    </td>
    <td>
      <router-link :to="{ name: 'company', params: { id: opportunity.company.id } }">{{
        opportunity.company.name
      }}</router-link>
    </td>
    <td>{{ opportunity.status.label }}</td>
    <td>
      {{
        (opportunity.lastTender?.soldDays ?? 0) * (opportunity.lastTender?.averageDailyRate ?? 0)
      }}€
    </td>
    <td>{{ opportunity.lastTender?.soldDays ?? 0 }}</td>
    <td>{{ opportunity.lastTender?.workedDays ?? 0 }}</td>
    <td>{{ opportunity.createdAt.format('YYYY-MM-DD') }}</td>
  </tr>
</template>
