import { defineStore } from 'pinia'
import type { BaseCompany } from '@/stores/company/types'
import ApiClient, { ApiError, HttpMethodEnum } from '@/misc/api-client'
import { useNotificationStore } from '@/stores/notification'
import type {Company} from "@/stores/company/types";

const urlPrefix = '/api/companies'
export const useCompanyStore = defineStore('company', {
    state: () => ({
        companies: [] as BaseCompany[]
    }),
    actions: {
        async fetchCompanies() {
            try {
                this.companies = (await ApiClient.query(HttpMethodEnum.GET, urlPrefix)) as BaseCompany[]
            } catch (err: unknown) {
                if (err instanceof ApiError) {
                    const notificationStore = useNotificationStore()
                    notificationStore.addError('Error while loading companies: ' + err.message)
                }
            }
        },
        async addCompany(company: Company) {
            try {
                const rawCompany = await ApiClient.query(HttpMethodEnum.POST, urlPrefix, company)
                this.companies.push(rawCompany)
            } catch (err: unknown) {
                if (err instanceof ApiError) {
                    const notificationStore = useNotificationStore()
                    notificationStore.addError('Error while adding company: ' + err.message)
                }
            }
        },
        async editCompany(company: Company) {
            try {
                const rawCompany = await ApiClient.query(HttpMethodEnum.PUT, urlPrefix+'/'+company.id, company)
                const companyIdx = this.companies.findIndex(c => c.id === company.id)
                this.companies.splice(companyIdx, 1, rawCompany)
            } catch (err: unknown) {
                if (err instanceof ApiError) {
                    const notificationStore = useNotificationStore()
                    notificationStore.addError('Error while editing company: ' + err.message)
                }
            }
        },
    }
})
