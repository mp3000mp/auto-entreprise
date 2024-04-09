<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue'
import type { Ref } from 'vue'
import { useCostStore } from '@/stores/cost'

import BootstrapModal from '@/components/BootstrapModal.vue'
import type { Cost, NewCost } from '@/stores/cost/types'

import Mp3000Button from '@/components/Mp3000Button.vue'
import dayjs from '@/misc/dayjs'
import DatePicker from '@/components/DatePicker.vue'
const costStore = useCostStore()
const emit = defineEmits(['stop-showing'])
const props = defineProps<{
  cost: Cost | null
  isShowing: boolean
}>()

const isSubmitting = ref(false)
const currentCost = ref(getEmptyCost()) as Ref<Cost | NewCost>
const errorMessage = ref('')

const costTypes = computed(() => costStore.costTypes)

function getEmptyCost(): NewCost {
  return {
    type: { id: 0, label: '' },
    date: dayjs(),
    amount: 0,
    description: ''
  }
}

function validate(cost: Cost | NewCost): string {
  if (cost.type.id === 0) {
    return 'Type non valide'
  }
  return ''
}

async function submit() {
  errorMessage.value = validate(currentCost.value)
  if (errorMessage.value !== '') {
    return
  }
  isSubmitting.value = true
  await ('id' in currentCost.value
    ? costStore.editCost(currentCost.value)
    : costStore.addCost(currentCost.value))
  emit('stop-showing')
  isSubmitting.value = false
}

function refresh() {
  currentCost.value = props.cost ? { ...props.cost } : getEmptyCost()
}

watch(
  () => props.cost,
  () => refresh()
)

onMounted(() => {
  refresh()
})
</script>

<template>
  <bootstrap-modal :is-showing="isShowing" @stop-showing="emit('stop-showing')">
    <template v-slot:header>
      <h5>{{ cost ? 'Edition' : 'Nouveau' }} coût</h5>
    </template>
    <template v-slot:body>
      <div class="form-group">
        <label>Type</label>
        <select class="form-select" v-model="currentCost.type.id" :disabled="isSubmitting">
          <option v-for="costType in costTypes" :key="costType.id" :value="costType.id">
            {{ costType.label }}
          </option>
        </select>
      </div>
      <div class="form-group">
        <label>Date</label>
        <date-picker v-model="currentCost.date" :disabled="isSubmitting" />
      </div>
      <div class="form-group">
        <label>Montant</label>
        <input
          type="number"
          min="0"
          class="form-control"
          v-model="currentCost.amount"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Description</label>
        <input
          type="text"
          class="form-control"
          v-model="currentCost.description"
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
      <mp3000-button @click.prevent="submit" :is-loading="isSubmitting" label="Valider" />
    </template>
  </bootstrap-modal>
</template>
