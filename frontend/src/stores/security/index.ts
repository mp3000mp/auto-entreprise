import { defineStore } from 'pinia'
import ApiClient, { HttpMethodEnum } from '@/misc/api-client'
import { notifyError } from '@/stores/notification/utils'
import type { User } from '@/stores/user/types'

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
    async checkisLoggedIn() {
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
    }
  }
})
