<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue'
import type { Ref } from 'vue'
import { useTenderStore } from '@/stores/tender'

import BootstrapModal from '@/components/BootstrapModal.vue'
import type { Tender, NewTender } from '@/stores/tender/types'

import Mp3000Button from '@/components/Mp3000Button.vue'
import BootstrapLoader from '@/components/BootstrapLoader.vue'
import DatePicker from '@/components/DatePicker.vue'
import type { ListOpportunity } from '@/stores/opportunity/types'

const tenderStore = useTenderStore()
const emit = defineEmits(['stop-showing'])
const props = withDefaults(
  defineProps<{
    tender: Tender | null
    opportunity: ListOpportunity
    isShowing: boolean
    isLoading?: boolean
  }>(),
  {
    isLoading: false
  }
)

const isSubmitting = ref(false)
const areRelationshipsLoading = ref(false)
const currentTender = ref(getEmptyTender()) as Ref<Tender | NewTender>
const errorMessage = ref('')

const statuses = computed(() => tenderStore.statuses)

function getEmptyTender(): NewTender {
  return {
    version: (props.opportunity.lastTender?.version ?? 0) + 1,
    averageDailyRate: 0,
    status: { id: 0, position: 0, label: '' },
    opportunity: props.opportunity,
    sentAt: null,
    acceptedAt: null,
    refusedAt: null,
    canceledAt: null,
    comments: ''
  }
}

function validate(tender: Tender | NewTender): string {
  if (tender.averageDailyRate === 0) {
    return 'TJM non valide'
  }
  if (tender.opportunity.id === 0) {
    return 'Opportunité non valide'
  }
  if (tender.status.id === 0) {
    return 'Statut non valide'
  }
  return ''
}

async function submit() {
  errorMessage.value = validate(currentTender.value)
  if (errorMessage.value !== '') {
    return
  }
  isSubmitting.value = true
  await ('id' in currentTender.value
    ? tenderStore.edit(currentTender.value)
    : tenderStore.add(currentTender.value))
  emit('stop-showing')
  isSubmitting.value = false
}

function refresh() {
  if (props.tender) {
    currentTender.value = { ...props.tender }
  } else {
    currentTender.value = getEmptyTender()
    currentTender.value.status = statuses.value[0]
  }
}

watch(
  () => props.tender,
  () => refresh()
)

onMounted(async () => {
  areRelationshipsLoading.value = true
  await (statuses.value.length ? null : tenderStore.fetchStatuses())
  refresh()
  areRelationshipsLoading.value = false
})
</script>

<template>
  <bootstrap-modal
    :is-showing="isShowing"
    :is-loading="isLoading"
    @stop-showing="emit('stop-showing')"
  >
    <template v-slot:header>
      <h5>{{ tender ? 'Edition' : 'Nouveau' }} devis (version {{ currentTender.version }})</h5>
    </template>
    <template v-slot:body>
      <div class="form-group">
        <label>TJM</label>
        <input
          type="number"
          class="form-control"
          v-model="currentTender.averageDailyRate"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Statut</label>
        <bootstrap-loader v-if="areRelationshipsLoading" />
        <select
          v-else
          class="form-select"
          v-model="currentTender.status.id"
          :disabled="isSubmitting"
        >
          <option v-for="status in statuses" :key="status.id" :value="status.id">
            {{ status.label }}
          </option>
        </select>
      </div>
      <div class="form-group">
        <label>Date envoi</label>
        <date-picker v-model="currentTender.sentAt" :disabled="isSubmitting" />
      </div>
      <div class="form-group">
        <label>Date commande</label>
        <date-picker v-model="currentTender.acceptedAt" :disabled="isSubmitting" />
      </div>
      <div class="form-group">
        <label>Date refus</label>
        <date-picker v-model="currentTender.refusedAt" :disabled="isSubmitting" />
      </div>
      <div class="form-group" v-if="tender">
        <label>Date d'annulation</label>
        <date-picker v-model="currentTender.canceledAt" :disabled="isSubmitting" />
      </div>
      <div class="form-group">
        <label>Commentaires</label>
        <input
          type="number"
          class="form-control"
          v-model="currentTender.comments"
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
