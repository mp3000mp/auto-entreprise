<script lang="ts" setup>
import { onMounted, ref, watch } from 'vue'
import type { Ref } from 'vue'
import { useTenderStore } from '@/stores/tender'

import BootstrapModal from '@/components/BootstrapModal.vue'
import type { TenderRow, NewTenderRow, Tender } from '@/stores/tender/types'

import Mp3000Button from '@/components/Mp3000Button.vue'

const tenderStore = useTenderStore()
const emit = defineEmits(['stop-showing'])
const props = withDefaults(
  defineProps<{
    tenderRow: TenderRow | null
    tender: Tender
    isShowing: boolean
    position?: number
  }>(),
  {
    position: 10
  }
)

const isSubmitting = ref(false)
const currentTenderRow = ref(getEmptyTenderRow()) as Ref<TenderRow | NewTenderRow>
const errorMessage = ref('')

function getEmptyTenderRow(): NewTenderRow {
  return {
    tender: { id: props.tender.id },
    position: props.position,
    title: '',
    description: '',
    soldDays: 0,
    fixedRate: 0
  }
}

function validate(tenderRow: TenderRow | NewTenderRow): string {
  if (tenderRow.title === '') {
    return 'Titre non valide'
  }
  if (tenderRow.description === '') {
    return 'Description non valide'
  }
  return ''
}

async function submit() {
  errorMessage.value = validate(currentTenderRow.value)
  if (errorMessage.value !== '') {
    return
  }
  isSubmitting.value = true
  await ('id' in currentTenderRow.value
    ? tenderStore.editTenderRow(currentTenderRow.value, props.tender)
    : tenderStore.addTenderRow(currentTenderRow.value, props.tender))
  emit('stop-showing')
  isSubmitting.value = false
}

function refresh() {
  currentTenderRow.value = props.tenderRow ? { ...props.tenderRow } : getEmptyTenderRow()
}

watch(
  () => props.tenderRow,
  () => refresh()
)

onMounted(() => {
  refresh()
})
</script>

<template>
  <bootstrap-modal :is-showing="isShowing" @stop-showing="emit('stop-showing')">
    <template v-slot:header>
      <h5>{{ tenderRow ? 'Edition' : 'Nouveau' }} ligne</h5>
    </template>
    <template v-slot:body>
      <div class="form-group">
        <label>Position</label>
        <input
          type="number"
          min="1"
          class="form-control"
          v-model="currentTenderRow.position"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Titre</label>
        <input
          type="text"
          class="form-control"
          v-model="currentTenderRow.title"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Description</label>
        <input
          type="text"
          class="form-control"
          v-model="currentTenderRow.description"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Jours vendus</label>
        <input
          type="number"
          class="form-control"
          v-model="currentTenderRow.soldDays"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Coût fixe</label>
        <input
            type="number"
            class="form-control"
            v-model="currentTenderRow.fixedRate"
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
