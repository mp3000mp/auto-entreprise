import { defineStore } from 'pinia'
import type { Cost, CostDtoIn, CostType, NewCost } from '@/stores/cost/types'
import ApiClient, { HttpMethodEnum } from '@/misc/api-client'
import { convertCostIn, convertCostOut } from '@/stores/cost/dto'
import { notifyError } from '@/stores/notification/utils'

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
        notifyError('Error while fetching costs: ', err)
      }
    },
    async fetchCostTypes() {
      try {
        this.costTypes = await ApiClient.query(HttpMethodEnum.GET, '/api/cost_types')
      } catch (err: unknown) {
        notifyError('Error while fetching cost types: ', err)
      }
    },
    async addCost(cost: NewCost) {
      try {
        const rawCost = await ApiClient.query(HttpMethodEnum.POST, urlPrefix, convertCostOut(cost))
        this.costs.push(convertCostIn(rawCost))
      } catch (err: unknown) {
        notifyError('Error while adding cost: ', err)
      }
    },
    async editCost(cost: Cost) {
      try {
        const rawCost = await ApiClient.query(
          HttpMethodEnum.PUT,
          urlPrefix + '/' + cost.id,
          convertCostOut(cost)
        )
        const costIdx = this.costs.findIndex((c) => c.id === cost.id)
        this.costs.splice(costIdx, 1, convertCostIn(rawCost))
      } catch (err: unknown) {
        notifyError('Error while editing cost: ', err)
      }
    },
    async deleteCost(id: number) {
      try {
        await ApiClient.query(HttpMethodEnum.DELETE, urlPrefix + '/' + id)
        const idx = this.costs.findIndex((cost) => cost.id === id)
        this.costs.splice(idx, 1)
      } catch (err: unknown) {
        notifyError('Error while deleting cost: ', err)
      }
    }
  }
})
