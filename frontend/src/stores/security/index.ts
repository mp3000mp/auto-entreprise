import { defineStore } from 'pinia'
import ApiClient, { HttpMethodEnum } from '@/misc/api-client'
import { useNotificationStore } from '@/stores/notification'
import { initCurrentUser, persistCurrentUser } from '@/stores/security/utils'

export const useSecurityStore = defineStore('security', {
  state: () => ({
    currentUser: initCurrentUser()
  }),
  actions: {
    async login(username: string, password: string) {
      try {
        this.currentUser = await ApiClient.query(HttpMethodEnum.POST, '/api/login', {
          username,
          password
        })
      } catch (err: unknown) {
        this.currentUser = null
        const notificationStore = useNotificationStore()
        notificationStore.addError('Authentication error: ' + (err.message ?? 'unexpected'))
        throw err
      } finally {
        persistCurrentUser(this.currentUser)
      }
    },
    async logout() {
      try {
        await ApiClient.query(HttpMethodEnum.GET, '/api/logout', null, { ignoreResponse: true })
        this.currentUser = null
      } catch (err: unknown) {
        const notificationStore = useNotificationStore()
        notificationStore.addError('Logout error: ' + (err.message ?? 'unexpected'))
      } finally {
        persistCurrentUser(this.currentUser)
      }
    }
  }
})
