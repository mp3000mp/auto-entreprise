import { defineStore } from 'pinia'
import type { WorkedTime, NewWorkedTime, WorkedTimeDtoIn } from '@/stores/workedTime/types'
import ApiClient, { HttpMethodEnum } from '@/misc/api-client'
import { convertWorkedTimeIn, convertWorkedTimeOut } from '@/stores/workedTime/dto'
import { notifyError } from '@/stores/notification/utils'
import { useOpportunityStore } from '@/stores/opportunity'

const urlPrefix = '/api/worked_times'
export const useWorkedTimeStore = defineStore('workedTime', {
  state: () => ({
    workedTimes: [] as WorkedTime[]
  }),
  actions: {
    async fetch() {
      try {
        const rawWorkedTimes = (await ApiClient.query(
          HttpMethodEnum.GET,
          urlPrefix
        )) as WorkedTimeDtoIn[]
        this.workedTimes = rawWorkedTimes.map((rawWorkedTime) => convertWorkedTimeIn(rawWorkedTime))
      } catch (err: unknown) {
        notifyError('Error while fetching worked times: ', err)
      }
    },
    async add(workedTime: NewWorkedTime) {
      try {
        const rawWorkedTime = await ApiClient.query(
          HttpMethodEnum.POST,
          urlPrefix,
          convertWorkedTimeOut(workedTime)
        )
        const newWorkedTime = convertWorkedTimeIn(rawWorkedTime)
        this.workedTimes.push(newWorkedTime)

        const opportunityStore = useOpportunityStore()
        if (opportunityStore.currentOpportunity) {
          opportunityStore.currentOpportunity.workedTimes.push(newWorkedTime)
        }
      } catch (err: unknown) {
        notifyError('Error while adding worked time: ', err)
      }
    },
    async edit(workedTime: WorkedTime) {
      try {
        const rawWorkedTime = await ApiClient.query(
          HttpMethodEnum.PUT,
          urlPrefix + '/' + workedTime.id,
          convertWorkedTimeOut(workedTime)
        )
        const editedWorkedTime = convertWorkedTimeIn(rawWorkedTime)
        let workedTimeIdx = this.workedTimes.findIndex((c) => c.id === workedTime.id)
        this.workedTimes.splice(workedTimeIdx, 1, editedWorkedTime)

        const opportunityStore = useOpportunityStore()
        if (opportunityStore.currentOpportunity) {
          workedTimeIdx = opportunityStore.currentOpportunity.workedTimes.findIndex(
            (c) => c.id === workedTime.id
          )
          opportunityStore.currentOpportunity.workedTimes.splice(workedTimeIdx, 1, editedWorkedTime)
        }
      } catch (err: unknown) {
        notifyError('Error while editing worked time: ', err)
      }
    },
    async delete(id: number) {
      try {
        await ApiClient.query(HttpMethodEnum.DELETE, urlPrefix + '/' + id)
        let workedTimeIdx = this.workedTimes.findIndex((workedTime) => workedTime.id === id)
        this.workedTimes.splice(workedTimeIdx, 1)

        const opportunityStore = useOpportunityStore()
        if (opportunityStore.currentOpportunity) {
          workedTimeIdx = opportunityStore.currentOpportunity.workedTimes.findIndex(
            (c) => c.id === id
          )
          opportunityStore.currentOpportunity.workedTimes.splice(workedTimeIdx, 1)
        }
      } catch (err: unknown) {
        notifyError('Error while deleting worked time: ', err)
      }
    }
  }
})
