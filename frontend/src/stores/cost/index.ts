import { defineStore } from 'pinia'
import type {Cost, CostDTO, CostType} from '@/stores/cost/types'
import ApiClient, { ApiError, HttpMethodEnum } from '@/misc/api-client'
import { useNotificationStore } from '@/stores/notification'
import {convertCost} from "@/stores/cost/dto";

const urlPrefix = '/api/costs'
export const useCostStore = defineStore('cost', {
    state: () => ({
        costTypes: [] as CostType[],
        costs: [] as Cost[],
    }),
    actions: {
        async fetchCosts() {
            try {
                const rawCosts = await ApiClient.query(HttpMethodEnum.GET, urlPrefix) as CostDTO[]
                this.costs = rawCosts.map(rawCost => convertCost(rawCost))
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
    }
})
