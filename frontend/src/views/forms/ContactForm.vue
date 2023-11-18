<script lang="ts" setup>
import { ref, watch } from 'vue'
import { useContactStore } from '@/stores/contact'

import BootstrapModal from '@/components/BootstrapModal.vue'
import type { Contact } from '@/stores/contact/types'

import Mp3000Button from '@/components/Mp3000Button.vue'

import ContactForm from "@/views/forms/ContactForm.vue";

const contactStore = useContactStore()
const emit = defineEmits(['stop-showing'])
const props = defineProps<{
  contact: Contact | null
  isShowing: boolean
}>()

const isLoading = ref(false)
const currentContact = ref(getEmptyContact())
const errorMessage = ref('')

function getEmptyContact(): Contact {
  return {
    id: 0,
    firstName: '',
    lastName: '',
    company: {id: 0, name: ''},
    email: '',
    phone: '',
    comments: '',
  }
}

function validate(contact: Contact): string {
  if (contact.firstName === '') {
    return 'Prénom non valide'
  }
  if (contact.lastName === '') {
    return 'Nom non valide'
  }
  if (contact.company.id === 0) {
    return 'Client non valide'
  }
  return ''
}

async function submit() {
  errorMessage.value = validate(currentContact.value)
  if (errorMessage.value !== '') {
    return
  }
  await props.contact
      ? contactStore.editContact(currentContact.value)
      : contactStore.addContact(currentContact.value)
  emit('stop-showing')
}

watch(
    () => props.contact,
    () => {
      currentContact.value = props.contact ? { ...props.contact } : getEmptyContact()
    }
)
</script>

<template>
  <bootstrap-modal :is-showing="isShowing" @stop-showing="emit('stop-showing')">
    <template v-slot:header>
      <h5>{{ contact ? 'Edition' : 'Nouveau' }} client</h5>
    </template>
    <template v-slot:body>
      <span>todo</span>
    </template>
    <template v-slot:footer>
      <span class="text-danger">{{ errorMessage }}</span>
      <mp3000-button
          @click.prevent="emit('stop-showing')"
          :disabled="isLoading"
          :outline="true"
          label="Annuler"
      />
      <mp3000-button @click.prevent="submit" :is-loading="isLoading" label="Valider" />
    </template>
  </bootstrap-modal>
</template>
