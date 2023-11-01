<script setup lang="ts">
import { computed } from 'vue'
import { useNotificationStore } from '@/stores/notification'

const notificationStore = useNotificationStore()

const notifications = computed(() => notificationStore.notifications)

function removeNotification(id: number) {
  notificationStore.removeNotification(id)
}
</script>

<template>
  <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div
      class="toast show"
      role="alert"
      aria-live="assertive"
      aria-atomic="true"
      v-for="notification in notifications"
      :key="notification.id"
    >
      <div class="toast-header p-2">
        <strong class="me-auto" :class="'text-' + notification.type">{{
          notification.title
        }}</strong>
        <small>{{ notification.subTitle }}</small>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="toast"
          aria-label="Close"
          @click="removeNotification(notification.id)"
        ></button>
      </div>
      <div class="toast-body p-2">
        {{ notification.content }}
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.toast-container {
  z-index: 11;
}
</style>
