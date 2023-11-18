import { defineStore } from 'pinia'
import type { Cost, CostDtoIn, CostType } from '@/stores/cost/types'
import ApiClient, { ApiError, HttpMethodEnum } from '@/misc/api-client'
import { useNotificationStore } from '@/stores/notification'
import {convertCostIn, convertCostOut} from '@/stores/cost/dto'

const urlPrefix = '/api/costs'
export const useCostStore = defineStore('cost', {
  state: () => ({
    costTypes: [] as CostType[],
    costs: [] as Cost[]
  }),
  actions: {
    async fetchCosts() {
      try {
        const rawCosts = (await ApiClient.query(HttpMethodEnum.GET, urlPrefix)) as CostDtoIn[]
        this.costs = rawCosts.map((rawCost) => convertCostIn(rawCost))
      } catch (err: unknown) {
        if (err instanceof ApiError) {
          const notificationStore = useNotificationStore()
          notificationStore.addError('Error while loading costs: ' + err.message)
        }
      }
    },
    async fetchCostTypes() {
      try {
        this.costTypes = await ApiClient.query(HttpMethodEnum.GET, '/api/cost_types')
      } catch (err: unknown) {
        if (err instanceof ApiError) {
          const notificationStore = useNotificationStore()
          notificationStore.addError('Error while loading cost types: ' + err.message)
        }
      }
    },
    async addCost(cost: Cost) {
      try {
        const rawCost = await ApiClient.query(HttpMethodEnum.POST, urlPrefix, convertCostOut(cost))
        this.costs.push(convertCostIn(rawCost))
      } catch (err: unknown) {
        if (err instanceof ApiError) {
          const notificationStore = useNotificationStore()
          notificationStore.addError('Error while adding cost: ' + err.message)
        }
      }
    },
    async editCost(cost: Cost) {
      try {
        const rawCost = await ApiClient.query(HttpMethodEnum.PUT, urlPrefix+'/'+cost.id, convertCostOut(cost))
        const costIdx = this.costs.findIndex(c => c.id === cost.id)
        this.costs.splice(costIdx, 1, convertCostIn(rawCost))
      } catch (err: unknown) {
        if (err instanceof ApiError) {
          const notificationStore = useNotificationStore()
          notificationStore.addError('Error while editing cost: ' + err.message)
        }
      }
    },
  }
})
