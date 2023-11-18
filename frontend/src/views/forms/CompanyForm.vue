<script lang="ts" setup>
import { ref, watch } from 'vue'
import { useCompanyStore } from '@/stores/company'

import BootstrapModal from '@/components/BootstrapModal.vue'
import type { Company } from '@/stores/company/types'

import Mp3000Button from '@/components/Mp3000Button.vue'

const companyStore = useCompanyStore()
const emit = defineEmits(['stop-showing'])
const props = defineProps<{
  company: Company | null
  isShowing: boolean
}>()

const isLoading = ref(false)
const currentCompany = ref(getEmptyCompany())
const errorMessage = ref('')

function getEmptyCompany(): Company {
  return {
    id: 0,
    name: '',
    street1: '',
    street2: '',
    postCode: '',
    city: '',
  }
}

function validate(company: Company): string {
  if (company.name === '') {
    return 'Nom non valide'
  }
  return ''
}

async function submit() {
  errorMessage.value = validate(currentCompany.value)
  if (errorMessage.value !== '') {
    return
  }
  await props.company
      ? companyStore.editCompany(currentCompany.value)
      : companyStore.addCompany(currentCompany.value)
  emit('stop-showing')
}

watch(
    () => props.company,
    () => {
      currentCompany.value = props.company ? { ...props.company } : getEmptyCompany()
    }
)
</script>

<template>
  <bootstrap-modal :is-showing="isShowing" @stop-showing="emit('stop-showing')">
    <template v-slot:header>
      <h5>{{ company ? 'Edition' : 'Nouveau' }} client</h5>
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
