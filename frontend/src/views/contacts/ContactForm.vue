<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue'
import type { Ref } from 'vue'
import { useContactStore } from '@/stores/contact'
import { useCompanyStore } from '@/stores/company'

import BootstrapModal from '@/components/BootstrapModal.vue'
import type { Contact, NewContact } from '@/stores/contact/types'

import Mp3000Button from '@/components/Mp3000Button.vue'
import BootstrapLoader from '@/components/BootstrapLoader.vue'
import type { Company } from '@/stores/company/types'

const contactStore = useContactStore()
const companyStore = useCompanyStore()
const emit = defineEmits(['stop-showing'])
const props = withDefaults(
  defineProps<{
    contact: Contact | null
    company?: Company | null
    isShowing: boolean
    isLoading?: boolean
  }>(),
  {
    company: null,
    isLoading: false
  }
)

const isSubmitting = ref(false)
const isCompaniesLoading = ref(false)
const currentContact = ref(getEmptyContact()) as Ref<Contact | NewContact>
const errorMessage = ref('')

const companies = computed(() => companyStore.companies)

function getEmptyContact(): NewContact {
  return {
    firstName: '',
    lastName: '',
    company: props.company ? props.company : { id: 0, name: '' },
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
  if (contact.email === '') {
    return 'Email non valide'
  }
  return ''
}

async function submit() {
  errorMessage.value = validate(currentContact.value)
  if (errorMessage.value !== '') {
    return
  }
  isSubmitting.value = true
  await ('id' in currentContact.value
    ? contactStore.edit(currentContact.value)
    : contactStore.add(currentContact.value))
  emit('stop-showing')
  isSubmitting.value = false
}

function refresh() {
  if (props.contact) {
    currentContact.value = { ...props.contact }
    if (props.company) {
      currentContact.value.company = props.company
    }
  } else {
    currentContact.value = getEmptyContact()
  }
}

watch(
  () => props.contact,
  () => refresh()
)

onMounted(async () => {
  refresh()
  isCompaniesLoading.value = true
  await companyStore.fetch()
  isCompaniesLoading.value = false
})
</script>

<template>
  <bootstrap-modal
    :is-showing="isShowing"
    :is-loading="isLoading"
    @stop-showing="emit('stop-showing')"
  >
    <template v-slot:header>
      <h5>{{ contact ? 'Edition' : 'Nouveau' }} client</h5>
    </template>
    <template v-slot:body>
      <div class="form-group">
        <label>Prénom</label>
        <input
          type="text"
          class="form-control"
          v-model="currentContact.firstName"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Nom</label>
        <input
          type="text"
          class="form-control"
          v-model="currentContact.lastName"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group" v-if="null === company">
        <label>Client</label>
        <bootstrap-loader v-if="isCompaniesLoading" />
        <select
          v-else
          class="form-select"
          v-model="currentContact.company.id"
          :disabled="isSubmitting"
        >
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
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Téléphone</label>
        <input
          type="text"
          class="form-control"
          v-model="currentContact.phone"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Commentaires</label>
        <textarea class="form-control" v-model="currentContact.comments" :disabled="isSubmitting" />
      </div>
    </template>
    <template v-slot:footer>
      <span class="text-danger">{{ errorMessage }}</span>
      <mp3000-button
        @click.prevent="emit('stop-showing')"
        :disabled="isSubmitting"
        :outline="true"
        label="Annuler"
      />
      <mp3000-button @click.prevent="submit" :is-loading="isSubmitting" label="Valider" />
    </template>
  </bootstrap-modal>
</template>
