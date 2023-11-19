<script lang="ts" setup>
import {computed, ref, watch} from 'vue'
import type {Ref} from 'vue'
import { useCompanyStore } from '@/stores/company'

import BootstrapModal from '@/components/BootstrapModal.vue'
import type {Company, NewCompany} from '@/stores/company/types'

import Mp3000Button from '@/components/Mp3000Button.vue'

const companyStore = useCompanyStore()
const emit = defineEmits(['stop-showing'])
const props = defineProps<{
  companyId: number | null
  isShowing: boolean
}>()

const isLoading = ref(false)
const currentCompany = ref(getEmptyCompany()) as Ref<Company|NewCompany>
const errorMessage = ref('')

const company = computed(() => companyStore.currentCompany)

function getEmptyCompany(): NewCompany {
  return {
    name: '',
    street1: '',
    street2: '',
    postCode: '',
    city: '',
  }
}

function validate(company: Company|NewCompany): string {
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
  isLoading.value = true
  await (
      'id' in currentCompany.value
      ? companyStore.edit(currentCompany.value)
      : companyStore.add(currentCompany.value)
  )
  emit('stop-showing')
}

async function refresh () {
  if (props.companyId) {
    isLoading.value = true
    await companyStore.fetchOne(props.companyId)
    isLoading.value = false
    currentCompany.value = { ...company.value }
  } else {
    currentCompany.value = getEmptyCompany()
  }
}

watch(
    () => props.companyId,
    async () => refresh()
)
</script>

<template>
  <bootstrap-modal :is-showing="isShowing" @stop-showing="emit('stop-showing')">
    <template v-slot:header>
      <h5>{{ company ? 'Edition' : 'Nouveau' }} client</h5>
    </template>
    <template v-slot:body>
      <div class="form-group">
        <label>Nom</label>
        <input
            type="text"
            class="form-control"
            v-model="currentCompany.name"
            :disabled="isLoading"
        />
      </div>
      <div class="form-group">
        <label>Adresse 1</label>
        <input
            type="text"
            class="form-control"
            v-model="currentCompany.street1"
            :disabled="isLoading"
        />
      </div>
      <div class="form-group">
        <label>Adresse 2</label>
        <input
            type="text"
            class="form-control"
            v-model="currentCompany.street2"
            :disabled="isLoading"
        />
      </div>
      <div class="form-group">
        <label>Ville</label>
        <input
            type="text"
            class="form-control"
            v-model="currentCompany.city"
            :disabled="isLoading"
        />
      </div>
      <div class="form-group">
        <label>Code postal</label>
        <input
            type="text"
            class="form-control"
            v-model="currentCompany.postCode"
            :disabled="isLoading"
        />
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
