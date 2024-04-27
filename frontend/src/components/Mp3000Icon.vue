<script lang="ts" setup>
import type { ConfirmModalConfig } from '@/components/types'
import Mp3000Button from '@/components/Mp3000Button.vue'

defineEmits(['click'])
defineOptions({
  inheritAttrs: false
})
withDefaults(
  defineProps<{
    confirmConfig?: ConfirmModalConfig | null
    disabled?: boolean
    isLoading?: boolean
    icon: string
  }>(),
  {
    confirmConfig: null,
    disabled: false,
    isLoading: false
  }
)
</script>

<template>
  <mp3000-button
    :label="String($attrs.title)"
    :confirm-config="confirmConfig"
    :disabled="disabled"
    :is-loading="isLoading"
    @click="$emit('click')"
  >
    <template v-slot:button="scope">
      <a href="#" v-bind="$attrs" @click.prevent="scope.click($event)">
        <span v-if="isLoading" class="spinner-border spinner-border-sm" role="status"></span>
        <font-awesome-icon v-else :icon="['fa', icon]" />
      </a>
    </template>
  </mp3000-button>
</template>
