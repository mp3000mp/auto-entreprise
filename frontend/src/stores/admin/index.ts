import { defineStore } from 'pinia'
import type { User } from '@/stores/admin/types'
import { ApiClient, ApiError, HttpMethodEnum } from '@/misc/api-client'
import { useNotificationStore } from '@/stores/notification'

export const useAdminStore = defineStore('admin', {
  state: () => ({
    currentUser: null as User | null,
    users: [] as User[]
  }),
  actions: {
    async fetchUsers() {
      try {
        this.users = await ApiClient.query(HttpMethodEnum.GET, '/api/users')
      } catch (err: unknown) {
        if (err instanceof ApiError) {
          const notificationStore = useNotificationStore()
          notificationStore.addError('Error while loading users: ' + err.message)
        }
      }
    },
    async login(name: string, password: string) {
      // todo
    }
  }
})
