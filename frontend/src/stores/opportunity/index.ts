import { defineStore } from 'pinia'
import type {
  Opportunity,
  ListOpportunity,
  NewOpportunity,
  MeanOfPayment,
  OpportunityStatus,
  OpportunityDtoIn,
  ListOpportunityDtoIn
} from '@/stores/opportunity/types'
import ApiClient, { HttpMethodEnum } from '@/misc/api-client'
import {
  convertListOpportunityIn,
  convertOpportunityIn,
  convertOpportunityOut
} from '@/stores/opportunity/dto'
import { notifyError } from '@/stores/notification/utils'
import { useCompanyStore } from '@/stores/company'
import { useContactStore } from '@/stores/contact'

const urlPrefix = '/api/opportunities'
export const useOpportunityStore = defineStore('opportunity', {
  state: () => ({
    opportunities: [] as ListOpportunity[],
    meanOfPayments: [] as MeanOfPayment[],
    statuses: [] as OpportunityStatus[],
    currentOpportunity: null as Opportunity | null,
    deletableIds: [] as number[]
  }),
  actions: {
    async fetch() {
      try {
        const rawOpportunities = (await ApiClient.query(
          HttpMethodEnum.GET,
          urlPrefix
        )) as ListOpportunityDtoIn[]
        this.opportunities = rawOpportunities.map((rawOpportunity) =>
          convertListOpportunityIn(rawOpportunity)
        )
      } catch (err: unknown) {
        notifyError('Error while fetching opportunities: ', err)
      }
    },
    async fetchMeanOfPayments() {
      try {
        this.meanOfPayments = (await ApiClient.query(
          HttpMethodEnum.GET,
          '/api/mean_of_payments'
        )) as MeanOfPayment[]
      } catch (err: unknown) {
        notifyError('Error while fetching mean of payments: ', err)
      }
    },
    async fetchStatuses() {
      try {
        this.statuses = (await ApiClient.query(
          HttpMethodEnum.GET,
          '/api/opportunity_statuses'
        )) as OpportunityStatus[]
      } catch (err: unknown) {
        notifyError('Error while fetching opportunity statuses: ', err)
      }
    },
    async fetchOne(id: number) {
      try {
        this.currentOpportunity = convertOpportunityIn(
          await ApiClient.query(HttpMethodEnum.GET, urlPrefix + '/' + id)
        )
      } catch (err: unknown) {
        notifyError('Error while fetching opportunity: ', err)
      }
    },
    resetCurrentOpportunity() {
      this.currentOpportunity = null
    },
    async add(opportunity: NewOpportunity) {
      try {
        const rawOpportunity = (await ApiClient.query(
          HttpMethodEnum.POST,
          urlPrefix,
          convertOpportunityOut(opportunity)
        )) as OpportunityDtoIn
        const newOpportunity = convertOpportunityIn(rawOpportunity)
        this.opportunities.push(newOpportunity)

        const companyStore = useCompanyStore()
        if (companyStore.currentCompany) {
          companyStore.currentCompany.opportunities.push(newOpportunity)
        }

        const contactStore = useContactStore()
        if (contactStore.currentContact) {
          contactStore.currentContact.opportunities.push(newOpportunity)
        }
      } catch (err: unknown) {
        notifyError('Error while adding opportunity: ', err)
      }
    },
    async edit(opportunity: Opportunity) {
      try {
        const rawOpportunity = await ApiClient.query(
          HttpMethodEnum.PUT,
          urlPrefix + '/' + opportunity.id,
          convertOpportunityOut(opportunity)
        )
        const editedOpportunity = convertOpportunityIn(rawOpportunity)
        if (this.currentOpportunity?.id === editedOpportunity.id) {
          this.currentOpportunity = editedOpportunity
        }
        let opportunityIdx = this.opportunities.findIndex((c) => c.id === opportunity.id)
        this.opportunities.splice(opportunityIdx, 1, editedOpportunity)

        const companyStore = useCompanyStore()
        if (companyStore.currentCompany) {
          opportunityIdx = companyStore.currentCompany.opportunities.findIndex(
            (c) => c.id === opportunity.id
          )
          companyStore.currentCompany.opportunities.splice(opportunityIdx, 1, editedOpportunity)
        }

        const contactStore = useContactStore()
        if (contactStore.currentContact) {
          opportunityIdx = contactStore.currentContact.opportunities.findIndex(
            (c) => c.id === opportunity.id
          )
          contactStore.currentContact.opportunities.splice(opportunityIdx, 1, editedOpportunity)
        }
      } catch (err: unknown) {
        notifyError('Error while editing opportunity: ', err)
      }
    },
    async fetchDeletables() {
      try {
        this.deletableIds = (await ApiClient.query(
          HttpMethodEnum.GET,
          urlPrefix + '/deletable'
        )) as number[]
      } catch (err: unknown) {
        notifyError('Error while fetching deletable opportunities: ', err)
      }
    },
    async delete(id: number) {
      try {
        await ApiClient.query(HttpMethodEnum.DELETE, urlPrefix + '/' + id)
        let opportunityIdx = this.opportunities.findIndex((opportunity) => opportunity.id === id)
        this.opportunities.splice(opportunityIdx, 1)

        const companyStore = useCompanyStore()
        if (companyStore.currentCompany) {
          opportunityIdx = companyStore.currentCompany.opportunities.findIndex((c) => c.id === id)
          companyStore.currentCompany.opportunities.splice(opportunityIdx, 1)
        }

        const contactStore = useContactStore()
        if (contactStore.currentContact) {
          opportunityIdx = contactStore.currentContact.opportunities.findIndex((c) => c.id === id)
          contactStore.currentContact.opportunities.splice(opportunityIdx, 1)
        }
      } catch (err: unknown) {
        notifyError('Error while deleting opportunity: ', err)
      }
    },
    async linkContact(opportunityId: number, contactId: number) {
      try {
        const rawOpportunity = await ApiClient.query(
          HttpMethodEnum.POST,
          urlPrefix + '/' + opportunityId + '/contacts/' + contactId
        )
        const editedOpportunity = convertOpportunityIn(rawOpportunity)
        if (this.currentOpportunity?.id === editedOpportunity.id) {
          this.currentOpportunity = editedOpportunity
        }
        const opportunityIdx = this.opportunities.findIndex((c) => c.id === opportunityId)
        this.opportunities.splice(opportunityIdx, 1, editedOpportunity)
      } catch (err: unknown) {
        notifyError('Error while linking contact to opportunity: ', err)
      }
    },
    async unlinkContact(opportunityId: number, contactId: number) {
      try {
        const rawOpportunity = await ApiClient.query(
          HttpMethodEnum.DELETE,
          urlPrefix + '/' + opportunityId + '/contacts/' + contactId
        )
        const editedOpportunity = convertOpportunityIn(rawOpportunity)
        if (this.currentOpportunity?.id === editedOpportunity.id) {
          this.currentOpportunity = editedOpportunity
        }
        const opportunityIdx = this.opportunities.findIndex((c) => c.id === opportunityId)
        this.opportunities.splice(opportunityIdx, 1, editedOpportunity)
      } catch (err: unknown) {
        notifyError('Error while unlinking contact to opportunity: ', err)
      }
    }
  }
})
