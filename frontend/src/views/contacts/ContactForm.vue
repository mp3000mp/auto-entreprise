<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue'
import type { Ref } from 'vue'
import { useContactStore } from '@/stores/contact'
import { useCompanyStore } from '@/stores/company'

import BootstrapModal from '@/components/BootstrapModal.vue'
import type { Contact, NewContact } from '@/stores/contact/types'

import Mp3000Button from '@/components/Mp3000Button.vue'

const contactStore = useContactStore()
const companyStore = useCompanyStore()
const emit = defineEmits(['stop-showing'])
const props = defineProps<{
  contactId: number | null
  isShowing: boolean
}>()

const isLoading = ref(false)
const currentContact = ref(getEmptyContact()) as Ref<Contact | NewContact>
const errorMessage = ref('')

const contact = computed(() => contactStore.currentContact)
const companies = computed(() => companyStore.companies)

function getEmptyContact(): NewContact {
  return {
    firstName: '',
    lastName: '',
    company: { id: 0, name: '' },
    email: '',
    phone: '',
    comments: ''
  }
}

function validate(contact: Contact | NewContact): string {
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
  await ('id' in currentContact.value
    ? contactStore.edit(currentContact.value)
    : contactStore.add(currentContact.value))
  emit('stop-showing')
}

async function refresh() {
  if (props.contactId) {
    isLoading.value = true
    await contactStore.fetchOne(props.contactId)
    isLoading.value = false
    currentContact.value = { ...contact.value }
  } else {
    currentContact.value = getEmptyContact()
  }
}

watch(
  () => props.contactId,
  async () => refresh()
)

onMounted(async () => {
  isLoading.value = true
  await companyStore.fetch()
  isLoading.value = false
})
</script>

<template>
  <bootstrap-modal :is-showing="isShowing" @stop-showing="emit('stop-showing')">
    <template v-slot:header>
      <h5>{{ contact ? 'Edition' : 'Nouveau' }} client</h5>
    </template>
    <template v-slot:body>
      <div class="form-group">
        <label>Nom</label>
        <input
          type="text"
          class="form-control"
          v-model="currentContact.lastName"
          :disabled="isLoading"
        />
      </div>
      <div class="form-group">
        <label>Prénom</label>
        <input
          type="text"
          class="form-control"
          v-model="currentContact.firstName"
          :disabled="isLoading"
        />
      </div>
      <div class="form-group">
        <label>Client</label>
        <select class="form-select" v-model="currentContact.company.id" :disabled="isLoading">
          <option v-for="company in companies" :key="company.id" :value="company.id">
            {{ company.name }}
          </option>
        </select>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input
          type="text"
          class="form-control"
          v-model="currentContact.email"
          :disabled="isLoading"
        />
      </div>
      <div class="form-group">
        <label>Téléphone</label>
        <input
          type="text"
          class="form-control"
          v-model="currentContact.phone"
          :disabled="isLoading"
        />
      </div>
      <div class="form-group">
        <label>Commentaires</label>
        <textarea class="form-control" v-model="currentContact.comments" :disabled="isLoading" />
      </div>
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
