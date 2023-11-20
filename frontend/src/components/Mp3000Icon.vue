<script lang="ts" setup>
import { ref } from 'vue'
import BootstrapModal from '@/components/BootstrapModal.vue'
import Mp3000Button from '@/components/Mp3000Button.vue'

const emit = defineEmits(['click'])
const props = withDefaults(
  defineProps<{
    confirmMessage?: string | null
    disabled?: boolean
    isLoading?: boolean
    icon: string
    title?: string | undefined // todo inherit
  }>(),
  {
    confirmMessage: null,
    disabled: false,
    isLoading: false,
    title: undefined // todo inherit
  }
)

const showConfirm = ref(false)

function click() {
  if (props.disabled) {
    return
  }
  props.confirmMessage ? (showConfirm.value = true) : confirm()
}
function cancel() {
  showConfirm.value = false
}
function confirm() {
  emit('click')
}
</script>

<template>
  <a href="#" @click="click()" :title="title">
    <span v-if="isLoading" class="spinner-border spinner-border-sm" role="status"></span>
    <font-awesome-icon v-else :icon="['fa', icon]" />
  </a>
  <bootstrap-modal v-if="confirmMessage" :is-showing="showConfirm" @stop-showing="cancel()">
    <template v-slot:header>
      <h5>Etes-vous sûr(e)</h5>
    </template>
    <template v-slot:body>
      <p>{{ confirmMessage }}</p>
    </template>
    <template v-slot:footer>
      <mp3000-button @click.prevent="cancel()" :outline="true" label="Annuler" />
      <mp3000-button @click.prevent="confirm()" label="Valider" />
    </template>
  </bootstrap-modal>
</template>
