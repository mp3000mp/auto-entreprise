<script lang="ts" setup>
import BootstrapModal from '@/components/BootstrapModal.vue'
import Mp3000Button from '@/components/Mp3000Button.vue'
import type { ConfirmModalConfig } from '@/components/types'

defineEmits(['cancel', 'confirm'])
defineProps<{
  confirmConfig: ConfirmModalConfig
  isShowing: boolean
}>()
</script>

<template>
  <bootstrap-modal :is-showing="isShowing" @stop-showing="$emit('cancel')">
    <template v-slot:header>
      <h5>{{ confirmConfig.title }}</h5>
    </template>
    <template v-slot:body>
      <p>{{ confirmConfig.message }}</p>
    </template>
    <template v-slot:footer>
      <mp3000-button
        @click.prevent="$emit('cancel')"
        :class="confirmConfig?.cancelButtonClass ?? 'btn-outline-primary'"
        :label="confirmConfig?.cancelButtonMessage ?? 'Annuler'"
      />
      <mp3000-button
        @click.prevent="$emit('confirm')"
        :class="confirmConfig?.confirmButtonClass ?? 'btn-primary'"
        :label="confirmConfig?.confirmButtonMessage ?? 'Valider'"
      />
    </template>
  </bootstrap-modal>
</template>
