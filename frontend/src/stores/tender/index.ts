import { defineStore } from 'pinia'
import type {
  Tender,
  ListTender,
  NewTender,
  TenderStatus,
  ListTenderDtoIn
} from '@/stores/tender/types'
import ApiClient, { HttpMethodEnum } from '@/misc/api-client'
import { convertListTenderIn, convertTenderIn, convertTenderOut } from '@/stores/tender/dto'
import { notifyError } from '@/stores/notification/utils'

const urlPrefix = '/api/tenders'
export const useTenderStore = defineStore('tender', {
  state: () => ({
    tenders: [] as ListTender[],
    statuses: [] as TenderStatus[],
    currentTender: null as Tender | null,
    deletableIds: [] as number[]
  }),
  actions: {
    async fetch() {
      try {
        const rawTenders = (await ApiClient.query(
          HttpMethodEnum.GET,
          urlPrefix
        )) as ListTenderDtoIn[]
        this.tenders = rawTenders.map((rawTender) => convertListTenderIn(rawTender))
      } catch (err: unknown) {
        notifyError('Error while fetching tenders: ', err)
      }
    },
    async fetchStatuses() {
      try {
        this.statuses = (await ApiClient.query(
          HttpMethodEnum.GET,
          '/api/tender_statuses'
        )) as TenderStatus[]
      } catch (err: unknown) {
        notifyError('Error while fetching tender statuses: ', err)
      }
    },
    async fetchOne(id: number) {
      try {
        this.currentTender = convertTenderIn(
          await ApiClient.query(HttpMethodEnum.GET, urlPrefix + '/' + id)
        )
      } catch (err: unknown) {
        notifyError('Error while fetching tender: ', err)
      }
    },
    resetCurrentTender() {
      this.currentTender = null
    },
    async add(tender: NewTender) {
      try {
        const rawTender = await ApiClient.query(
          HttpMethodEnum.POST,
          urlPrefix,
          convertTenderOut(tender)
        )
        this.tenders.push(convertTenderIn(rawTender))
      } catch (err: unknown) {
        notifyError('Error while adding tender: ', err)
      }
    },
    async edit(tender: Tender) {
      try {
        const rawTender = await ApiClient.query(
          HttpMethodEnum.PUT,
          urlPrefix + '/' + tender.id,
          convertTenderOut(tender)
        )
        const editedTender = convertTenderIn(rawTender)
        if (this.currentTender?.id === editedTender.id) {
          this.currentTender = editedTender
        }
        const tenderIdx = this.tenders.findIndex((c) => c.id === tender.id)
        this.tenders.splice(tenderIdx, 1, editedTender)
      } catch (err: unknown) {
        notifyError('Error while editing tender: ', err)
      }
    },
    async fetchDeletables() {
      try {
        this.deletableIds = (await ApiClient.query(
          HttpMethodEnum.GET,
          urlPrefix + '/deletable'
        )) as number[]
      } catch (err: unknown) {
        notifyError('Error while fetching deletable tenders: ', err)
      }
    },
    async delete(id: number) {
      try {
        await ApiClient.query(HttpMethodEnum.DELETE, urlPrefix + '/' + id)
        const idx = this.tenders.findIndex((tender) => tender.id === id)
        this.tenders.splice(idx, 1)
      } catch (err: unknown) {
        notifyError('Error while deleting tender: ', err)
      }
    }
  }
})
