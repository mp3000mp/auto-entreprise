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
  withDetails: boolean
}>()

const isRemoving = ref(false)
const confirmMessage = computed(() => ({
  message: 'Confirmer la suppression de ' + props.contact.firstName + ' ' + props.contact.lastName,
  title: 'Suppression'
}))

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
        :confirm-config="confirmMessage"
        @click="remove()"
        icon="trash"
        title="Supprimer"
        :is-loading="isRemoving"
      />
      <router-link :to="{ name: 'contact', params: { id: contact.id } }"
        >{{ contact.firstName }} {{ contact.lastName }}</router-link
      >
    </td>
    <td v-if="withDetails">
      <router-link :to="{ name: 'company', params: { id: contact.company.id } }">{{
        contact.company.name
      }}</router-link>
    </td>
    <td>{{ contact.email }}</td>
    <td>{{ contact.phone }}</td>
    <td>{{ contact.comments }}</td>
  </tr>
</template>
