import { defineStore } from 'pinia'
import type { ListCompany, NewCompany } from '@/stores/company/types'
import ApiClient, { HttpMethodEnum } from '@/misc/api-client'
import type { Company } from '@/stores/company/types'
import { notifyError } from '@/stores/notification/utils'

const urlPrefix = '/api/companies'
export const useCompanyStore = defineStore('company', {
  state: () => ({
    companies: [] as ListCompany[],
    currentCompany: null as Company | null,
    deletableIds: [] as number[]
  }),
  actions: {
    async fetch() {
      try {
        this.companies = (await ApiClient.query(HttpMethodEnum.GET, urlPrefix)) as ListCompany[]
      } catch (err: unknown) {
        notifyError('Error while fetching companies: ', err)
      }
    },
    async fetchOne(id: number) {
      try {
        this.currentCompany = await ApiClient.query(HttpMethodEnum.GET, urlPrefix + '/' + id)
      } catch (err: unknown) {
        notifyError('Error while fetching company: ', err)
      }
    },
    async add(company: NewCompany) {
      try {
        const rawCompany = await ApiClient.query(HttpMethodEnum.POST, urlPrefix, company)
        this.companies.push(rawCompany)
      } catch (err: unknown) {
        notifyError('Error while adding company: ', err)
      }
    },
    async edit(company: Company) {
      try {
        const rawCompany = await ApiClient.query(
          HttpMethodEnum.PUT,
          urlPrefix + '/' + company.id,
          company
        )
        const companyIdx = this.companies.findIndex((c) => c.id === company.id)
        this.companies.splice(companyIdx, 1, rawCompany)
      } catch (err: unknown) {
        notifyError('Error while editing company: ', err)
      }
    },
    async fetchDeletables() {
      try {
        this.deletableIds = (await ApiClient.query(
          HttpMethodEnum.GET,
          urlPrefix + '/deletable'
        )) as number[]
      } catch (err: unknown) {
        notifyError('Error while fetching deletable companies: ', err)
      }
    },
    async delete(id: number) {
      try {
        await ApiClient.query(HttpMethodEnum.DELETE, urlPrefix + '/' + id)
        const idx = this.companies.findIndex((company) => company.id === id)
        this.companies.splice(idx, 1)
      } catch (err: unknown) {
        notifyError('Error while deleting company: ', err)
      }
    }
  }
})
