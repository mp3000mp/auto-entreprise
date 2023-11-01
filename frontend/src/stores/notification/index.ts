import { defineStore } from 'pinia'
import type { Notification } from '@/stores/notification/types'
import { NotificationTypeEnum } from '@/stores/notification/types'

let i = 1

export const useNotificationStore = defineStore('notification', {
  state: () => ({
    notifications: [] as Notification[]
  }),
  actions: {
    addError(message: string) {
      this.addNotification(NotificationTypeEnum.DANGER, 'Error', message)
    },
    addNotification(
      type: NotificationTypeEnum,
      title: string,
      content: string,
      subTitle: string | null = null
    ) {
      this.notifications.push({
        id: i++,
        type,
        title,
        subTitle,
        content
      })
    },
    removeNotification(id: number) {
      this.notifications = this.notifications.filter((notification) => notification.id !== id)
    }
  }
})
