<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue'
import type { Ref } from 'vue'

import BootstrapModal from '@/components/BootstrapModal.vue'
import type { WorkedTime, NewWorkedTime } from '@/stores/workedTime/types'

import Mp3000Button from '@/components/Mp3000Button.vue'
import type { Opportunity } from '@/stores/opportunity/types'
import dayjs from '@/misc/dayjs'
import { useSecurityStore } from '@/stores/security'
import { useWorkedTimeStore } from '@/stores/workedTime'
import DatePicker from '@/components/DatePicker.vue'

const workedTimeStore = useWorkedTimeStore()
const securityStore = useSecurityStore()
const emit = defineEmits(['stop-showing'])
const props = defineProps<{
  workedTime: WorkedTime | null
  opportunity: Opportunity
  isShowing: boolean
}>()

const currentUser = computed(() => securityStore.currentUser)

const isSubmitting = ref(false)
const currentWorkedTime = ref(getEmptyWorkedTime()) as Ref<WorkedTime | NewWorkedTime>
const errorMessage = ref('')

function getEmptyWorkedTime(): NewWorkedTime {
  return {
    opportunity: { id: props.opportunity.id },
    user: { id: currentUser.value.id },
    workedDays: 0,
    date: dayjs()
  }
}

function validate(workedTime: WorkedTime | NewWorkedTime): string {
  if (workedTime.workedDays === 0) {
    return 'Temps non valide'
  }
  return ''
}

async function submit() {
  errorMessage.value = validate(currentWorkedTime.value)
  if (errorMessage.value !== '') {
    return
  }
  isSubmitting.value = true
  await ('id' in currentWorkedTime.value
    ? workedTimeStore.edit(currentWorkedTime.value)
    : workedTimeStore.add(currentWorkedTime.value))
  emit('stop-showing')
  isSubmitting.value = false
}

function refresh() {
  if (props.workedTime) {
    currentWorkedTime.value = {
      ...props.workedTime,
      opportunity: { id: props.opportunity.id },
      user: { id: currentUser.value.id }
    }
  } else {
    currentWorkedTime.value = getEmptyWorkedTime()
  }
}

watch(
  () => props.workedTime,
  () => refresh()
)

onMounted(() => {
  refresh()
})
</script>

<template>
  <bootstrap-modal :is-showing="isShowing" :z-index="1100" @stop-showing="emit('stop-showing')">
    <template v-slot:header>
      <h5>{{ workedTime ? 'Edition' : 'Nouveau' }} temps</h5>
    </template>
    <template v-slot:body>
      <div class="form-group">
        <label>Date</label>
        <date-picker v-model="currentWorkedTime.date" :disabled="isSubmitting" />
      </div>
      <div class="form-group">
        <label>Temps</label>
        <input
          type="number"
          min="0"
          class="form-control"
          v-model="currentWorkedTime.workedDays"
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
