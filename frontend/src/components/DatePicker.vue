<script setup lang="ts">
import VueDatePicker from '@vuepic/vue-datepicker'
import type { Dayjs } from 'dayjs'
import { computed } from 'vue'
import dayjs from '@/misc/dayjs'

const emit = defineEmits(['update:modelValue'])
const props = withDefaults(
  defineProps<{
    disabled?: boolean
    modelValue: Dayjs | null
  }>(),
  {
    disabled: false
  }
)

const value = computed({
  get() {
    return props.modelValue ? new Date(props.modelValue.format('YYYY-MM-DD')) : null
  },
  set(value) {
    emit('update:modelValue', dayjs(value))
  }
})
</script>

<template>
  <vue-date-picker
    v-model="value"
    :disabled="disabled"
    :enable-time-picker="false"
    format="dd/MM/yyyy"
    locale="fr"
  />
</template>
