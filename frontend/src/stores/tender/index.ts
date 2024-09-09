import { defineStore } from 'pinia'
import type {
  Tender,
  ListTender,
  NewTender,
  TenderRow,
  TenderStatus,
  ListTenderDtoIn,
  NewTenderRow
} from '@/stores/tender/types'
import ApiClient, { HttpMethodEnum } from '@/misc/api-client'
import {
  convertListTenderIn,
  convertTenderIn,
  convertTenderOut,
  convertTenderRowOut
} from '@/stores/tender/dto'
import { notifyError } from '@/stores/notification/utils'
import { useOpportunityStore } from '@/stores/opportunity'
import type { TenderDtoIn, TenderFileDtoIn, TenderFileTypeEnum } from '@/stores/tender/types'
import { convertTenderFileIn } from '@/stores/tender/dto'

const urlPrefix = '/api/tenders'
const rowUrlPrefix = '/api/tender_rows'
const fileUrlPrefix = '/api/tender_files'
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
        const rawTenders = await ApiClient.query<ListTenderDtoIn[]>(HttpMethodEnum.GET, urlPrefix)
        this.tenders = rawTenders.map((rawTender) => convertListTenderIn(rawTender))
      } catch (err: unknown) {
        notifyError('Error while fetching tenders: ', err)
      }
    },
    async fetchStatuses() {
      try {
        this.statuses = await ApiClient.query<TenderStatus[]>(
          HttpMethodEnum.GET,
          '/api/tender_statuses'
        )
      } catch (err: unknown) {
        notifyError('Error while fetching tender statuses: ', err)
      }
    },
    async fetchOne(id: number) {
      try {
        this.currentTender = convertTenderIn(
          await ApiClient.query<TenderDtoIn>(HttpMethodEnum.GET, urlPrefix + '/' + id)
        )
      } catch (err: unknown) {
        notifyError('Error while fetching tender: ', err)
      }
    },
    resetCurrentTender() {
      this.currentTender = null
    },
    async add(tender: NewTender): Promise<Tender | null> {
      try {
        const rawTender = await ApiClient.query<TenderDtoIn>(
          HttpMethodEnum.POST,
          urlPrefix,
          convertTenderOut(tender)
        )
        const newTender = convertTenderIn(rawTender)
        this.tenders.push(newTender)

        const opportunityStore = useOpportunityStore()
        if (opportunityStore.currentOpportunity) {
          opportunityStore.currentOpportunity.tenders.push(newTender)
        }

        return newTender
      } catch (err: unknown) {
        notifyError('Error while adding tender: ', err)
        return null
      }
    },
    async edit(tender: Tender): Promise<Tender | null> {
      try {
        const rawTender = await ApiClient.query<TenderDtoIn>(
          HttpMethodEnum.PUT,
          urlPrefix + '/' + tender.id,
          convertTenderOut(tender)
        )
        const editedTender = convertTenderIn(rawTender)
        if (this.currentTender?.id === editedTender.id) {
          this.currentTender = editedTender
        }

        let tenderIdx = this.tenders.findIndex((c) => c.id === tender.id)
        this.tenders.splice(tenderIdx, 1, editedTender)

        const opportunityStore = useOpportunityStore()
        if (opportunityStore.currentOpportunity) {
          tenderIdx = opportunityStore.currentOpportunity.tenders.findIndex(
            (tender) => tender.id === tender.id
          )
          opportunityStore.currentOpportunity.tenders.splice(tenderIdx, 1, editedTender)
        }

        return editedTender
      } catch (err: unknown) {
        notifyError('Error while editing tender: ', err)
        return null
      }
    },
    async fetchDeletables() {
      try {
        this.deletableIds = await ApiClient.query<number[]>(
          HttpMethodEnum.GET,
          urlPrefix + '/deletable'
        )
      } catch (err: unknown) {
        notifyError('Error while fetching deletable tenders: ', err)
      }
    },
    async delete(id: number) {
      try {
        await ApiClient.query(HttpMethodEnum.DELETE, urlPrefix + '/' + id)
        let tenderIdx = this.tenders.findIndex((tender) => tender.id === id)
        this.tenders.splice(tenderIdx, 1)
        const opportunityStore = useOpportunityStore()

        if (opportunityStore.currentOpportunity) {
          tenderIdx = opportunityStore.currentOpportunity.tenders.findIndex(
            (tender) => tender.id === id
          )
          opportunityStore.currentOpportunity.tenders.splice(tenderIdx, 1)
        }
      } catch (err: unknown) {
        notifyError('Error while deleting tender: ', err)
      }
    },
    async addTenderRow(tenderRow: NewTenderRow, tender: Tender) {
      try {
        const rawTenderRow = await ApiClient.query<TenderRow>(
          HttpMethodEnum.POST,
          rowUrlPrefix,
          convertTenderRowOut(tenderRow, tender)
        )
        if (null === this.currentTender) {
          return
        }
        this.currentTender.tenderRows.push(rawTenderRow)
      } catch (err: unknown) {
        notifyError('Error while adding tender row: ', err)
      }
    },
    async editTenderRow(tenderRow: TenderRow, tender: Tender) {
      try {
        const rawTenderRow = await ApiClient.query<TenderRow>(
          HttpMethodEnum.PUT,
          rowUrlPrefix + '/' + tenderRow.id,
          convertTenderRowOut(tenderRow, tender)
        )
        if (null === this.currentTender) {
          return
        }
        const editedTenderRow = rawTenderRow
        const tenderRowIdx = this.currentTender.tenderRows.findIndex((c) => c.id === tenderRow.id)
        this.currentTender.tenderRows.splice(tenderRowIdx, 1, editedTenderRow)
      } catch (err: unknown) {
        notifyError('Error while editing tender row: ', err)
      }
    },
    async deleteTenderRow(id: number) {
      try {
        await ApiClient.query(HttpMethodEnum.DELETE, rowUrlPrefix + '/' + id)
        if (null === this.currentTender) {
          return
        }
        const idx = this.currentTender.tenderRows.findIndex((tenderRow) => tenderRow.id === id)
        this.currentTender.tenderRows.splice(idx, 1)
      } catch (err: unknown) {
        notifyError('Error while deleting tender row: ', err)
      }
    },
    async addTenderFile(formData: FormData, type: TenderFileTypeEnum, tenderId: number) {
      try {
        const url = new URLSearchParams({ type, tenderId: String(tenderId) })
        const rawFile = await ApiClient.query<TenderFileDtoIn>(
          HttpMethodEnum.POST,
          fileUrlPrefix + '?' + url.toString(),
          formData
        )
        const newFile = convertTenderFileIn(rawFile)
        if (this.currentTender) {
          this.currentTender.tenderFiles.push(newFile)
        }
      } catch (err: unknown) {
        notifyError('Error while uploading tender file: ', err)
      }
    },
    async removeTenderFile(fileId: number) {
      try {
        await ApiClient.query(HttpMethodEnum.DELETE, fileUrlPrefix + '/' + fileId)
        if (this.currentTender) {
          const idx = this.currentTender.tenderFiles.findIndex((file) => (file.id = fileId))
          this.currentTender.tenderFiles.splice(idx, 1)
        }
      } catch (err: unknown) {
        notifyError('Error while removing tender file: ', err)
      }
    }
  }
})
