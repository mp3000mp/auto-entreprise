import { defineStore } from 'pinia'
import type { User } from '@/stores/user/types'
import ApiClient, { ApiError, HttpMethodEnum } from '@/misc/api-client'
import { useNotificationStore } from '@/stores/notification'

const urlPrefix = '/api/users'
export const useUserStore = defineStore('user', {
    state: () => ({
        users: [] as User[]
    }),
    actions: {
        async fetchUsers() {
            try {
                this.users = await ApiClient.query(HttpMethodEnum.GET, urlPrefix)
            } catch (err: unknown) {
                if (err instanceof ApiError) {
                    const notificationStore = useNotificationStore()
                    notificationStore.addError('Error while loading users: ' + err.message)
                }
            }
        },
    }
})
