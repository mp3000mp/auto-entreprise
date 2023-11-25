import { defineStore } from 'pinia'
import type { WorkedTime, NewWorkedTime, WorkedTimeDtoIn } from '@/stores/workedTime/types'
import ApiClient, { HttpMethodEnum } from '@/misc/api-client'
import { convertWorkedTimeIn, convertWorkedTimeOut } from '@/stores/workedTime/dto'
import { notifyError } from '@/stores/notification/utils'

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
        this.workedTimes.push(convertWorkedTimeIn(rawWorkedTime))
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
        const workedTimeIdx = this.workedTimes.findIndex((c) => c.id === workedTime.id)
        this.workedTimes.splice(workedTimeIdx, 1, editedWorkedTime)
      } catch (err: unknown) {
        notifyError('Error while editing worked time: ', err)
      }
    },
    async delete(id: number) {
      try {
        await ApiClient.query(HttpMethodEnum.DELETE, urlPrefix + '/' + id)
        const idx = this.workedTimes.findIndex((workedTime) => workedTime.id === id)
        this.workedTimes.splice(idx, 1)
      } catch (err: unknown) {
        notifyError('Error while deleting worked time: ', err)
      }
    }
  }
})
