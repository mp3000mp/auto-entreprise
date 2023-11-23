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
        this.opportunities.push(convertOpportunityIn(rawOpportunity))
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
        const opportunityIdx = this.opportunities.findIndex((c) => c.id === opportunity.id)
        this.opportunities.splice(opportunityIdx, 1, editedOpportunity)
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
        const idx = this.opportunities.findIndex((opportunity) => opportunity.id === id)
        this.opportunities.splice(idx, 1)
      } catch (err: unknown) {
        notifyError('Error while deleting opportunity: ', err)
      }
    }
  }
})
