<script lang="ts" setup>
import { onMounted, onUnmounted } from 'vue'

const emit = defineEmits(['stop-showing'])
defineProps<{
  isShowing: boolean
}>()

function handleKeypress(e: KeyboardEvent) {
  console.log(e.code)
  if (e.code === 'Escape') {
    emit('stop-showing')
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeypress)
})
onUnmounted(() => {
  window.removeEventListener('keydown', handleKeypress)
})
</script>

<template>
  <div>
    <div class="modal" :class="{ hidden: !isShowing, show: isShowing }">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <slot name="header"></slot>
            <font-awesome-icon
              :icon="['fa', 'xmark']"
              class="cp"
              @click.prevent="$emit('stop-showing')"
            />
          </div>
          <div class="modal-body">
            <slot name="body"></slot>
          </div>
          <div class="modal-footer">
            <slot name="footer"></slot>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-backdrop fade show" v-if="isShowing"></div>
  </div>
</template>
