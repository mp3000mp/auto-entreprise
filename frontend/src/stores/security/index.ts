import { defineStore } from 'pinia'
import ApiClient, { HttpMethodEnum } from '@/misc/api-client'
import { notifyError } from '@/stores/notification/utils'
import type { User } from '@/stores/user/types'

type EditPasswordPayload = {
  currentPassword: string
  newPassword: string
}

export const useSecurityStore = defineStore('security', {
  state: () => ({
    currentUser: null as User | null,
    loggedInChecked: false
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
        notifyError('Authentication error: ', err)
        throw err
      }
    },
    async checkIsLoggedIn() {
      try {
        this.currentUser = await ApiClient.query(HttpMethodEnum.GET, '/api/me')
      } catch (err: unknown) {
        this.currentUser = null
      } finally {
        this.loggedInChecked = true
      }
    },
    async logout() {
      try {
        await ApiClient.query(HttpMethodEnum.GET, '/api/logout', null, { ignoreResponse: true })
        this.currentUser = null
      } catch (err: unknown) {
        notifyError('Logout error: ', err)
      }
    },
    async editPassword(payload: EditPasswordPayload) {
      try {
        await ApiClient.query(HttpMethodEnum.PUT, '/api/password', payload)
      } catch (err: unknown) {
        notifyError('Error while updating password: ', err)
      }
    }
  }
})
