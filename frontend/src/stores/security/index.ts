import { defineStore } from 'pinia'
import ApiClient, { HttpMethodEnum } from '@/misc/api-client'
import { notifyError } from '@/stores/notification/utils'
import type { User } from '@/stores/user/types'
import config from '@/misc/config'

type LoginResponse = {
  twoFactorAuthRequired: boolean
  me?: User
}

type EditPasswordPayload = {
  currentPassword: string
  newPassword: string
}

type CheckTwoFactorAuthResponse = {
  success: boolean
  message: string
}

export const useSecurityStore = defineStore('security', {
  state: () => ({
    currentUser: null as User | null,
    loggedInChecked: false,
    qrCodeUrl: config.backendBaseUrl + '/api/2fa/qr-code',
    twoFactorAuthRequired: false
  }),
  actions: {
    async login(username: string, password: string) {
      try {
        const response = await ApiClient.query<LoginResponse>(HttpMethodEnum.POST, '/api/login', {
          username,
          password
        })
        this.twoFactorAuthRequired = response.twoFactorAuthRequired
        if (this.twoFactorAuthRequired) {
          return
        }
        if (response.me) {
          this.currentUser = response.me
          this.loggedInChecked = true
        } else {
          await this.checkIsLoggedIn()
        }
      } catch (err: unknown) {
        this.currentUser = null
        notifyError('Authentication error: ', err)
        throw err
      }
    },
    async twoFactorAuth(twoFactorAuthToken: string) {
      try {
        this.currentUser = await ApiClient.query<User>(HttpMethodEnum.POST, '/api/2fa', {
          twoFactorAuthToken
        })
        this.loggedInChecked = true
      } catch (err: unknown) {
        this.currentUser = null
        notifyError('Two factor authentication error: ', err)
        throw err
      }
    },
    async checkIsLoggedIn() {
      try {
        this.currentUser = await ApiClient.query<User>(HttpMethodEnum.GET, '/api/me')
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
    },
    async getTwoFactorAuthEnable() {
      try {
        await ApiClient.query(HttpMethodEnum.POST, '/api/2fa/enable')
        if (this.currentUser) {
          this.currentUser.isTotpAuthenticationEnabled = true
        }
      } catch (err: unknown) {
        notifyError('Error while enabling QR code: ', err)
      }
    },
    async getTwoFactorAuthDisable() {
      try {
        await ApiClient.query(HttpMethodEnum.POST, '/api/2fa/disable')
        if (this.currentUser) {
          this.currentUser.isTotpAuthenticationEnabled = false
        }
      } catch (err: unknown) {
        notifyError('Error while disabling QR code: ', err)
      }
    },
    async checkTwoFactorAuth(twoFactorAuthToken: string) {
      try {
        return await ApiClient.query<CheckTwoFactorAuthResponse>(
          HttpMethodEnum.POST,
          '/api/2fa/check-code',
          {
            twoFactorAuthToken
          }
        )
      } catch (err: unknown) {
        notifyError('Error while checking QR code: ', err)
      }
    }
  }
})
