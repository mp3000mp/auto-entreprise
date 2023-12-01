<script lang="ts" setup>
import { computed, ref } from 'vue'
import { useWorkedTimeStore } from '@/stores/workedTime'

import type { WorkedTime } from '@/stores/workedTime/types'
import Mp3000Icon from '@/components/Mp3000Icon.vue'

const workedTimeStore = useWorkedTimeStore()
defineEmits(['show-form'])
const props = defineProps<{
  workedTime: WorkedTime
}>()

const isRemoving = ref(false)
const confirmMessage = computed(() => 'Confirmer la suppression ?')

async function remove() {
  isRemoving.value = true
  await workedTimeStore.delete(props.workedTime.id)
  isRemoving.value = false
}
</script>

<template>
  <div>
    <a href="#" class="me-1" @click.prevent="$emit('show-form')" title="Editer">
      <font-awesome-icon :icon="['fa', 'pen-to-square']" />
    </a>
    <mp3000-icon
      class="me-1"
      :confirm-message="confirmMessage"
      @click="remove()"
      icon="trash"
      title="Supprimer"
      :is-loading="isRemoving"
    />
    <span>{{ workedTime.date.format('YYYY-MM-DD') }} - {{ workedTime.workedDays }}</span>
  </div>
</template>
