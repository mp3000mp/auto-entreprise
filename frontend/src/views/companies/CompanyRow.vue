<script lang="ts" setup>
import { computed, ref } from 'vue'
import Mp3000Icon from '@/components/Mp3000Icon.vue'
import { useCompanyStore } from '@/stores/company'
import type { ListCompany } from '@/stores/company/types'

const companyStore = useCompanyStore()
defineEmits(['show-form'])
const props = defineProps<{
  company: ListCompany
  isDeletable: boolean
}>()

const isRemoving = ref(false)
const confirmMessage = computed(() => 'Confirmer la suppression de ' + props.company.name)

async function remove() {
  isRemoving.value = true
  await companyStore.delete(props.company.id)
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
      <router-link :to="{ name: 'company', params: { id: company.id } }">{{
        company.name
      }}</router-link>
    </td>
  </tr>
</template>
