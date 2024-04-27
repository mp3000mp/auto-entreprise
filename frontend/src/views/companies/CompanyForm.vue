<script lang="ts" setup>
import { onMounted, ref, watch } from 'vue'
import type { Ref } from 'vue'
import { useCompanyStore } from '@/stores/company'
import Mp3000Button from '@/components/Mp3000Button.vue'
import BootstrapModal from '@/components/BootstrapModal.vue'
import type { Company, NewCompany } from '@/stores/company/types'

const companyStore = useCompanyStore()
const emit = defineEmits(['stop-showing'])
const props = withDefaults(
  defineProps<{
    company: Company | null
    isShowing: boolean
    isLoading?: boolean
  }>(),
  {
    isLoading: false
  }
)

const isSubmitting = ref(false)
const currentCompany = ref(getEmptyCompany()) as Ref<Company | NewCompany>
const errorMessage = ref('')

function getEmptyCompany(): NewCompany {
  return {
    name: '',
    street1: '',
    street2: '',
    postCode: '',
    city: ''
  }
}

function validate(company: Company | NewCompany): string {
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
  isSubmitting.value = true
  await ('id' in currentCompany.value
    ? companyStore.edit(currentCompany.value)
    : companyStore.add(currentCompany.value))
  emit('stop-showing')
  isSubmitting.value = false
}

function refresh() {
  currentCompany.value = props.company ? { ...props.company } : getEmptyCompany()
}

watch(
  () => props.company,
  () => refresh()
)

onMounted(() => {
  refresh()
})
</script>

<template>
  <bootstrap-modal
    :is-showing="isShowing"
    :is-loading="isLoading"
    @stop-showing="emit('stop-showing')"
  >
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
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Adresse 1</label>
        <input
          type="text"
          class="form-control"
          v-model="currentCompany.street1"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Adresse 2</label>
        <input
          type="text"
          class="form-control"
          v-model="currentCompany.street2"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Ville</label>
        <input
          type="text"
          class="form-control"
          v-model="currentCompany.city"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Code postal</label>
        <input
          type="text"
          class="form-control"
          v-model="currentCompany.postCode"
          :disabled="isSubmitting"
        />
      </div>
    </template>
    <template v-slot:footer>
      <span class="text-danger">{{ errorMessage }}</span>
      <mp3000-button
        @click.prevent="emit('stop-showing')"
        class="btn-outline-primary"
        :disabled="isSubmitting"
        label="Annuler"
      />
      <mp3000-button
        @click.prevent="submit"
        :is-loading="isSubmitting"
        class="btn-primary"
        label="Valider"
      />
    </template>
  </bootstrap-modal>
</template>
