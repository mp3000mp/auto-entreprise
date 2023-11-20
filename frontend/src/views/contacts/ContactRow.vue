<script lang="ts" setup>
import { computed, ref } from 'vue'
import Mp3000Icon from '@/components/Mp3000Icon.vue'
import type { ListContact } from '@/stores/contact/types'
import { useContactStore } from '@/stores/contact'

const contactStore = useContactStore()
defineEmits(['show-form'])
const props = defineProps<{
  contact: ListContact
  isDeletable: boolean
}>()

const isRemoving = ref(false)
const confirmMessage = computed(
  () => 'Confirmer la suppression de ' + props.contact.firstName + ' ' + props.contact.lastName
)

async function remove() {
  isRemoving.value = true
  await contactStore.delete(props.contact.id)
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
      <router-link :to="{ name: 'contact', params: { id: contact.id } }"
        >{{ contact.firstName }} {{ contact.lastName }}</router-link
      >
    </td>
    <td>
      <router-link :to="{ name: 'company', params: { id: contact.company.id } }">{{
        contact.company.name
      }}</router-link>
    </td>
    <td>{{ contact.email }}</td>
    <td>{{ contact.phone }}</td>
  </tr>
</template>
