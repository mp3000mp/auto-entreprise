<script lang="ts" setup>
import Mp3000ConfirmModal from '@/components/Mp3000ConfirmModal.vue'
import type { ConfirmModalConfig } from '@/components/types'
import { ref } from 'vue'
import type { Ref } from 'vue'

const emit = defineEmits(['click'])
defineOptions({
  inheritAttrs: false
})
const props = withDefaults(
  defineProps<{
    confirmConfig?: ConfirmModalConfig | null
    disabled?: boolean
    isLoading?: boolean
    label: string
  }>(),
  {
    confirmConfig: null,
    disabled: false,
    isLoading: false
  }
)

const showConfirm = ref(false)
const confirmEvent = ref(null) as Ref<MouseEvent | null>

function click(event: MouseEvent) {
  if (props.disabled) {
    return
  }
  confirmEvent.value = event
  props.confirmConfig ? (showConfirm.value = true) : confirm()
}
function cancel() {
  showConfirm.value = false
}
function confirm() {
  showConfirm.value = false
  emit('click', confirmEvent.value)
}
</script>

<template>
  <slot name="button" :click="click">
    <button
      class="btn"
      type="submit"
      v-bind="$attrs"
      :class="{ disabled: disabled || isLoading }"
      :disabled="disabled || isLoading"
      @click="click($event)"
    >
      <span v-if="isLoading" class="spinner-border spinner-border-sm" role="status"></span>
      <span v-else>{{ label }}</span>
    </button>
  </slot>
  <slot name="confirm-modal">
    <mp3000-confirm-modal
      v-if="confirmConfig"
      :is-showing="showConfirm"
      :confirm-config="confirmConfig"
      @cancel="cancel"
      @confirm="confirm"
    />
  </slot>
</template>
