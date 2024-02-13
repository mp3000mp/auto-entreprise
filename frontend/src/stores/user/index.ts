import { defineStore } from 'pinia'
import type { User } from '@/stores/user/types'
import ApiClient, { HttpMethodEnum } from '@/misc/api-client'
import { notifyError } from '@/stores/notification/utils'

const urlPrefix = '/api/users'
export const useUserStore = defineStore('user', {
  state: () => ({
    users: [] as User[]
  }),
  actions: {
    async fetch() {
      try {
        this.users = await ApiClient.query<User[]>(HttpMethodEnum.GET, urlPrefix)
      } catch (err: unknown) {
        notifyError('Error while loading users: ', err)
      }
    }
  }
})
